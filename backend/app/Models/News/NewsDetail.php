<?php

namespace App\Models\News;

use App\Models\News\News;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewsDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'news_id',
        'name',
        'intro',
        'language',
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

    /**
     * news
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function news() {
        return $this->belongsTo(News::class);
    }
}
