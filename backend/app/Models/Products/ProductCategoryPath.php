<?php

namespace App\Models\Products;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductCategoryPath extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_category_id',
        'path_id',
        'level',
    ];

    public $timestamps = false;
}
