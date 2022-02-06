<?php

namespace App\Models\Products;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductOptionValueDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_option_value_id',
        'language',
        'name',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [];

    /**
     * $timestamps
     * @var bool
     */
    public $timestamps = false;
}
