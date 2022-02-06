<?php

namespace App\Models\Orders;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\BaseModel;
use App\Traits\HostScope;

class OrderTotal extends Model
{
    use HasFactory, HostScope, BaseModel;

    protected $primaryKey = 'order_total_id';

    protected $fillable = [];
}
