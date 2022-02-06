<?php

namespace App\Models\Reports;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\BaseModel;
use App\Traits\HostScope;

class Report extends Model
{
    use HasFactory, HostScope, BaseModel;
}
