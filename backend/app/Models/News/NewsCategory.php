<?php

namespace App\Models\News;

use App\Repositories\Systems\LogRecordRepository;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\BaseModel;
use App\Traits\HostScope;

class NewsCategory extends Model
{
    use HasFactory, LogRecordRepository, HostScope, BaseModel;

    /**
     * @var $fillable
     */
    protected $fillable = [
        'sort_order',
        'status',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
    ];

    /**
     * @var string
     */
    protected $primaryKey = 'news_category_id';

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
                case 'status':
                    if (is_array($value)) {
                        $query = $query->whereIn($field, $value);
                    } else {
                        $query = $query->where($field, $value);
                    }
                    continue 2;
                case 'language':
                    $query = $query->join('news_category_details', function ($query) use ($field, $value) {
                        $query->on(
                            'news_categories.news_category_id', '=', 'news_category_details.news_category_id'
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
     * news
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function news() :\Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(News::class, 'news_to_category', 'news_category_id', 'news_id');
    }

    /**
     * details
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function details() :\Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(NewsCategoryDetail::class, 'news_category_id');
    }

    /**
     * detail
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function detail() :\Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(NewsCategoryDetail::class, 'news_category_id');
    }
}
