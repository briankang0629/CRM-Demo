<?php

namespace App\Transformers\Users\Reports;

use App\Services\Medias\ImageService;
use League\Fractal\TransformerAbstract;
use Carbon\Carbon;

class SaleHotTransformer extends TransformerAbstract
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
    public function transform($report)
    {
        return [
            "product_id" => $report->product_id,
            "model" => $report->product->model,
            "name" => $report->product->detail->name,
            "intro" => $report->product->detail->intro,
            "cost_price" => $report->product->cost_price,
            "price" => $report->product->price,
            "categories" => $report->product->categories->pluck('product_category_id'),
            "url" => app(ImageService::class)->getUrlByUploadId($report->product->upload_id),
        ];
    }
}
