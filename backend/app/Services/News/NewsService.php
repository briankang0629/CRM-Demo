<?php

namespace App\Services\News;

use App\Models\News\News;
use App\Models\News\NewsCategory;
use App\Models\News\NewsDetail;

class NewsService
{
    /**
     * @var object
     */
    private $news;

    /**
     * @var object
     */
    private $newsCategory;

    /**
     * @var object
     */
    private $newsDetail;

    /**
     * NewsService constructor.
     *
     * @param News $news
     * @param NewsCategory $newsCategory
     * @param NewsDetail $newsDetail
     */
    public function __construct(
        News $news,
        NewsCategory $newsCategory,
        NewsDetail $newsDetail
    )
    {
        $this->news = $news;
        $this->newsCategory = $newsCategory;
        $this->newsDetail = $newsDetail;
    }

    /**
     * lists
     *
     * @param array $request
     * @return mixed
     */
    public function lists(array $request)
    {
        $request['language'] = \Arr::get($request, 'language', config('system.defaultLanguage'));

        $news = $this->news->search($request)->with('categories.details')->paginate(per_page());

        return $news;
    }

    /**
     * info
     *
     * @param $id
     * @return News
     */
    public function info(int $id, array $request) : News
    {
        if (\Arr::get($request, 'language')) {
            $news = $this->news->with(['detail' => function ($news) use ($request) {
                $news->where('language', $request['language']);
            }])->with(['categories.detail' => function ($news) use ($request) {
                $news->where('language', $request['language']);
            }])->findOrFail($id);
        } else {
            $news = $this->news->with(['details', 'categories.details'])->findOrFail($id);
        }

        return $news;
    }

    /**
     * store
     *
     * @param array $request
     * @return void
     */
    public function store(array $request) :void
    {
        $news = $this->news->create($request);

        collect($request['detail'])->map(function ($detail) use ($news) {
            $detail['news_id'] = $news->news_id;
            $this->newsDetail->create($detail);
        });

        //操作記錄
        $this->news->writeLog(22, $news);
    }

    /**
     * update
     *
     * @param array $request
     * @param int $id
     * @return void
     */
    public function update(array $request, int $id = null) :void
    {
        // 消息
        $news = $this->news->findOrNew($id);
        $news->fill($request);
        $news->save();

        // 語系
        foreach ($request['details'] as $detail) {
            $newsDetail = $this->newsDetail->where('news_id', $id)->where('language', $detail['language']);
            if (!$newsDetail->first()) {
                $detail['news_id'] = $news->news_id;
                $this->newsDetail->create($detail);
            } else {
                $newsDetail->update($detail);
            }
        }

        // 分類
        $news->categories()->detach();
        $news->categories()->attach($request['categories']);

        // 選項值

        // 關聯消息圖片

        //操作記錄
        $this->news->writeLog($id ? 22 : 23, $news);
    }

    /**
     * delete
     *
     * @param array $id
     * @return void
     */
    public function delete(array $ids) :void
    {
        // 消息相關刪除
        \DB::table('news_to_category')->whereIn('news_id', $ids)->delete();
        $this->newsDetail->whereIn('news_id', $ids)->delete();
        $this->news->whereIn('news_id', $ids)->delete();

        //操作記錄
        $this->news->writeLog(24);
    }
}
