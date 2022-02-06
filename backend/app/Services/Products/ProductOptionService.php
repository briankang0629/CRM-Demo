<?php

namespace App\Services\Products;

use App\Models\Products\Product;
use App\Models\Products\ProductOptionDetail;

class ProductOptionService
{
    /**
     * 用商品更新選項
     * @param $product
     * @param $options
     * @return void
     */
    public function updateOptionByProduct(Product $product, $options)
    {
        $optionIds = collect($options)->pluck('product_option_id');
        $this->deleteDetailByOptionId($optionIds);

        foreach ($options as $option) {
            $productOption = $product->option()->findOrNew($option['product_option_id'] ?? null);
            $productOption->fill($option);
            $productOption->product_id = $product->product_id;
            $productOption->save();

            //選項語系
            foreach ($option['details'] as $detail) {
                $detail['product_option_id'] = $productOption->product_option_id;
                $productOption->detail->create($detail);
            }

            // 選項值
            if ($values = array_get($option, 'values')) {
                app(\App\Services\Products\ProductOptionValueService::class)->updateValueByOptions($productOption, $values);
            }
        }
    }

    /**
     * 刪除指定的選項語系
     * @param $optionId
     * @return void
     */
    public function deleteDetailByOptionId($optionId)
    {
        ProductOptionDetail::whereIn('product_option_id', $optionId)->delete();
    }

}
