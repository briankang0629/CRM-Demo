<?php

namespace App\Models\Orders;

use App\Models\Products\Product;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\BaseModel;
use App\Traits\HostScope;

class OrderProduct extends Model
{
    use HasFactory, HostScope, BaseModel;

    protected $primaryKey = 'order_product_id';

    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'price',
        'total',
        'tac',
        'reward',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'order_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'product_id');
    }
}
