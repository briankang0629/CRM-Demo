<?php

namespace App\Models\News;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewsCategoryDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'news_category_id',
        'language',
        'name',
        'description',
        'meta_title',
        'meta_description',
        'meta_keyword',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [];

    /**
     * $timestamps
     * @var bool
     */
    public $timestamps = false;
}
