<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ProductCategoryPath implements Rule
{
    private $categoryId;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($id)
    {
        $this->categoryId = $id;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $paths = \App\Models\Products\ProductCategoryPath::where('path_id', $this->categoryId)->pluck('product_category_id')->toArray();
        return !in_array($value, $paths);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return '您所選擇的分類層級不能是此分類的子分類!';
    }
}
