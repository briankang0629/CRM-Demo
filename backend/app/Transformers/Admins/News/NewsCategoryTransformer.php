<?php

namespace App\Transformers\Admins\News;

use League\Fractal\TransformerAbstract;

class NewsCategoryTransformer extends TransformerAbstract
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

    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform($newsCategory)
    {
        return [
            'news_category_id' => $newsCategory->news_category_id,
            'name' => $newsCategory->name,
            'count' => $newsCategory->news->count(),
            'status' => $newsCategory->status,
            'sort_order' => $newsCategory->sort_order,
            "created_at" => $newsCategory->created_at,
            "updated_at" => $newsCategory->updated_at,
        ];
    }
}
