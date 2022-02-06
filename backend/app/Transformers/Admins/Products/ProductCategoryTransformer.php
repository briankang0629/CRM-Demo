<?php

namespace App\Transformers\Admins\Products;

use App\Models\Products\Product;
use App\Models\Products\ProductCategory;
use League\Fractal\TransformerAbstract;

class ProductCategoryTransformer extends TransformerAbstract
{
    public function __construct ($isTree = false) {
        $this->isTree = $isTree;
    }

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
    public function transform(ProductCategory $category)
    {
        $response = [
            'product_category_id' => $category->product_category_id,
            'name' => $category->name,
            'parent_id' => $category->parent_id,
            'count' => $category->products->where('status', Product::ENABLE)->count(),
            "sort_order" => $category->sort_order,
            "status" => $category->status,
            "created_at" => $category->created_at,
            "updated_at" => $category->updated_at,
        ];

        return $response;
    }
}
