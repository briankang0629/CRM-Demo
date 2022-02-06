<?php

namespace App\Models\Orders;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\BaseModel;
use App\Traits\HostScope;

class Order extends Model
{
    use HasFactory, HostScope, BaseModel;

    protected $primaryKey = 'order_id';

    protected $fillable = [];

    /**
     * total
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function total()
    {
        return $this->hasMany(OrderTotal::class, 'order_id', 'order_id');
    }

    public function getOrderGroupByMonth()
    {
        return \DB::select(\DB::raw("SELECT COUNT(order_id) AS total, created_at FROM `orders` WHERE YEAR(created_at) = YEAR(NOW()) AND host_id = " . host()->host_id . " GROUP BY MONTH(created_at)"));
    }
}
