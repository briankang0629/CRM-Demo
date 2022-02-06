<?php

namespace App\Models\Systems;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\BaseModel;
use App\Traits\HostScope;

class SystemSetting extends Model
{
    use HasFactory, HostScope, BaseModel;

    protected $primaryKey = 'system_setting_id';

    protected $fillable = [
        'code',
        'key',
        'value'
    ];
}
