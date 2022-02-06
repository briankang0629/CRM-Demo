<?php

namespace App\Services\Products;

use App\Models\Products\ProductOptionValueDetail;

class ProductOptionValueService
{
    /**
     * 用選項更新選項值
     * @param $option
     * @param $values
     */
    public function updateValueByOptions($option, $values)
    {
        $valueIds = collect($values)->pluck('product_option_value_id');
        $this->deleteDetailByValueId($valueIds);

        foreach ($values as $value) {
            $optionValue = $option->value()->findOrNew($value['product_option_value_id'] ?? null);
            $optionValue->fill($value);
            $optionValue->product_option_id = $option->product_option_id;
            $optionValue->save();

            //選項值語系
            foreach ($value['details'] as $detail) {
                $detail['product_option_value_id'] = $optionValue->product_option_value_id;
                $optionValue->detail->create($detail);
            }
        }
    }

    /**
     * 刪除指定的選項值語系
     * @param $valueId
     */
    public function deleteDetailByValueId($valueId)
    {
        ProductOptionValueDetail::whereIn('product_option_value_id', $valueId)->delete();
    }
}
