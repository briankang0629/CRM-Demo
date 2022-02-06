<?php

namespace App\Models\Products;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductCategoryDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_category_id',
        'name',
        'language',
        'description',
        'meta_title',
        'meta_description',
        'meta_keyword',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [];

    /**
     * @var bool
     */
    public $timestamps = false;
}
