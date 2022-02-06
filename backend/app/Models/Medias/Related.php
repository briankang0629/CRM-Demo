<?php

namespace App\Models\Medias;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Related extends Model
{
    use HasFactory;

    protected $table = 'upload_related';

    public $timestamps = false;

    public function upload()
    {
        return $this->belongsTo(Image::class, 'upload_id', 'upload_id');
    }
}
