<?php

namespace App\Models\News;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Repositories\Systems\LogRecordRepository;
use App\Traits\BaseModel;
use App\Traits\HostScope;

class News extends Model
{
    use HasFactory, LogRecordRepository, HostScope, BaseModel;

    /**
     * @var $fillable
     */
    protected $fillable = [
        'upload_id',
        'sort_order',
        'top',
        'status',
        'view',
        'available_time',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'available_time' => 'datetime:Y-m-d H:i:s',
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
    ];

    /**
     * @var string
     */
    protected $primaryKey = 'news_id';

    /**
     * 客製化搜尋
     *
     * @param array $request
     * @return object
     */
    public function search (array $request) :object
    {
        $query = $this;
        foreach ($request as $field => $value) {
            if (!$value) {
                continue;
            }

            switch ($field) {
                case 'upload_id':
                case 'status':
                    if (is_array($value)) {
                        $query = $query->whereIn($field, $value);
                    } else {
                        $query = $query->where($field, $value);
                    }
                    continue 2;
                case 'start_time':
                    $query = $query->where('available_time', '>=', $value);
                    continue 2;
                case 'end_time':
                    $query = $query->where('available_time', '<', $value);
                    continue 2;
                case 'language':
                    $query = $query->join('news_details', function ($query) use ($field, $value) {
                        $query->on(
                            'news.news_id', '=', 'news_details.news_id'
                        )->where($field, $value);
                    });
                    continue 2;
                case 'name':
                    $query = $query->where('name', 'like', '%' . $value . '%');
                    continue 2;
            }
        }

        return $query;
    }

    /**
     * newsCategories
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function categories()
    {
        return $this->belongsToMany(NewsCategory::class, 'news_to_category', 'news_id', 'news_category_id');
    }

    /**
     * newsDetails
     *
     * @return \Illuminate\Database\Eloquent\Relations\hasMany
     */
    public function details()
    {
        return $this->hasMany(NewsDetail::class, 'news_id', 'news_id');
    }

    /**
     * detail
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function detail() :\Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(NewsDetail::class, 'news_id');
    }
}
