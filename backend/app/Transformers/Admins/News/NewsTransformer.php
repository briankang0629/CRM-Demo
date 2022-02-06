<?php

namespace App\Transformers\Admins\News;

use League\Fractal\TransformerAbstract;

class NewsTransformer extends TransformerAbstract
{
    /**
     * List of resources to automatically include
     *
     * @var array
     */
    protected $defaultIncludes = [
        //
    ];

    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected $availableIncludes = [
        //
    ];

    private $type;

    public function __construct ($type = 'list')
    {
        $this->type = $type;
    }

    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform($news)
    {
        $response =  [
            'news_id' => $news->news_id,
            'name' => $news->name,
            'sort_order' => $news->sort_order,
            'status' => $news->status,
            'view' => $news->view,
            'top' => $news->top,
            'categories' => $news->categories->pluck('news_category_id'),
            'upload_id' => $news->upload_id,//@todo 關聯
            "created_at" => $news->created_at,
            "updated_at" => $news->updated_at,
        ];

        if ($this->type == 'info') {
            unset($response['name']);
            $response['details'] = $news->details;
        }

        return $response;
    }
}
