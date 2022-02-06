<?php

namespace App\Models\Sites;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomDomain extends Model
{
    use HasFactory;

    protected $primaryKey = 'custom_domain_id';
}
