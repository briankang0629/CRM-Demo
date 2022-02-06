<?php

namespace App\Transformers\Users\Products;

use League\Fractal\TransformerAbstract;

class ProductTransformer extends TransformerAbstract
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
    public function transform($product)
    {
        // @todo 待修 => 有必要考慮host->host_code
        $image_domain = config('app.image_domain') . '/' . host_code() . '/image/';
        $image = $product->image;
        $url = $image ? $image_domain . $image->folder . '/' . $image->file_name : null;

        $response = [
            'product_id' => $product->product_id,
            'model' => $product->model,
            'name' => $product->name,
            'cost_price' => $product->cost_price,
            'price' => $product->price,
            'sort_order' => $product->sort_order,
            'status' => $product->status,
            'view' => $product->view,
            'intro' => $product->intro,
            'categories' => $product->categories->pluck('product_category_id'),
            'upload_id' => $product->upload_id,
            'url' => $url,
        ];

        if ($this->type == 'info') {
            unset($response['name']);

            // 相關聯圖片
            $relatedImage = $product->relateImage->each(function ($image) use ($image_domain) {
                $upload = $image->upload;
                $image->url = $image_domain . $upload->folder . '/' . $upload->file_name;
                return $image;
            });

            $response += [
                'detail' => $product->detail,
                'options' => $product->options,
                'relatedImage' => $relatedImage ,
            ];
        }

        return $response;
    }
}
