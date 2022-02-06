<?php

namespace App\Models\Medias;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\BaseModel;
use App\Traits\HostScope;

class Folder extends Model
{
    use HasFactory, HostScope, BaseModel;

    protected $primaryKey = 'upload_folder_id';

    protected $table = 'upload_folders';

    protected $fillable = [
        'code',
        'name',
        'category'
    ];
}
