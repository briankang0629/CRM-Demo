<?php

namespace App\Models\Products;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\BaseModel;
use App\Traits\HostScope;

class ProductOptionValue extends Model
{
    use HasFactory, HostScope, BaseModel;

    /**
     * @var $fillable
     */
    protected $fillable = [
        'product_option_id',
        'price',
        'quantity',
        'point',
        'weight',
        'is_stock',
        'sort_order',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'price' => 'double',
        'point' => 'double',
        'weight' => 'double',
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
    ];

    /**
     * @var string
     */
    protected $primaryKey = 'product_option_value_id';

    /**
     * $timestamps
     * @var bool
     */
    public $timestamps = false;

    /**
     * details
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function details() :\Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ProductOptionValueDetail::class, 'product_option_value_id', 'product_option_value_id');
    }

    /**
     * detail
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function detail() :\Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(ProductOptionValueDetail::class, 'product_option_value_id', 'product_option_value_id')->withDefault();
    }
}
