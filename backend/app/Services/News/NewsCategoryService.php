<?php

namespace App\Services\News;

use App\Models\News\NewsCategory;
use App\Models\News\NewsCategoryDetail;
use App\Exceptions\CustomException;

class NewsCategoryService
{
    /**
     * @var object
     */
    private $newsCategory;

    /**
     * @var object
     */
    private $newsCategoryDetail;

    /**
     * NewsCategoryService constructor.
     *
     * @param NewsCategory $newsCategory
     * @param NewsCategoryDetail $newsCategoryDetail
     */
    public function __construct(
        NewsCategory $newsCategory,
        NewsCategoryDetail $newsCategoryDetail
    )
    {
        $this->newsCategory = $newsCategory;
        $this->newsCategoryDetail = $newsCategoryDetail;
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

        $newsCategories = $this->newsCategory->search($request)->paginate(per_page());

        return $newsCategories;
    }

    /**
     * info
     *
     * @param $id
     * @return NewsCategory
     */
    public function info(int $id) :NewsCategory
    {
        $newsCategory = $this->newsCategory->findOrFail($id);
        $newsCategory->details = $newsCategory->details()->get()->keyBy(function ($category) {
            return $category->language;
        });

        return $newsCategory;
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
        $newsCategory = $this->newsCategory->findOrNew($id);
        $newsCategory->fill($request);
        $newsCategory->save();

        //更新詳細內容
        // 語系
        foreach ($request['details'] as $detail) {
            $newsCategoryDetail = $this->newsCategoryDetail->where('news_category_id', $id)->where('language', $detail['language']);
            if (!$newsCategoryDetail->first()) {
                $detail['news_category_id'] = $newsCategory->news_category_id;
                $this->newsCategoryDetail->create($detail);
            } else {
                $newsCategoryDetail->update($detail);
            }
        }

        //操作記錄
        $this->newsCategory->writeLog($id ? 25 : 26, $newsCategory);
    }

    /**
     * delete
     *
     * @param array $id
     * @return void
     */
    public function delete(array $ids) :void
    {
        $newsCategories = $this->newsCategory->with('news')->whereIn('news_category_id', $ids);

        // 檢查分類有無包含消息
        $newsCategories->each(function ($category) {
            if ($category->news->count()) {
                throw new CustomException(trans('common.newsCategory.containNews'));
            }
        });

        // 執行刪除
        $this->newsCategory->destroy($ids);
        $this->newsCategoryDetail->whereIn('news_category_id', $ids)->delete();

        //操作記錄
        $this->newsCategory->writeLog(27);
    }
}
