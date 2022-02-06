<?php

namespace App\Models\Systems;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\BaseModel;
use App\Traits\HostScope;

class SystemMenu extends Model
{
    use HasFactory, HostScope, BaseModel;

    public $timestamps = false;
}
