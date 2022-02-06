<?php

namespace App\Transformers\Users\Products;

use App\Models\Products\Product;
use App\Models\Products\ProductCategory;
use League\Fractal\TransformerAbstract;
use Carbon\Carbon;

class ProductCategoryTransformer extends TransformerAbstract
{
    private $isTree = false;

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
    public function transform(ProductCategory $productCategory)
    {
        $response = [
            'product_category_id' => $productCategory->product_category_id,
            'name' => $productCategory->name,
            'parent_id' => $productCategory->parent_id,
            'count' => $productCategory->products->where('status', Product::ENABLE)->count(),
            'family' => $productCategory->family,
        ];

        if ($this->isTree) {
            $response['key'] = $productCategory->product_category_id;
            $response['children'] = $productCategory->children;
        }

        return $response;
    }
}
