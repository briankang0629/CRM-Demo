<?php

namespace App\Models\Products;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'name',
        'intro',
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
     * $timestamps
     * @var bool
     */
    public $timestamps = false;

    /**
     * product
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product () {
        return $this->belongsTo(Product::class);
    }
}
