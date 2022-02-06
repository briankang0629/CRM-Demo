<?php

namespace App\Models\Products;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Repositories\Systems\LogRecordRepository;
use App\Traits\BaseModel;
use App\Traits\HostScope;

class ProductCategory extends Model
{
    use HasFactory, LogRecordRepository, HostScope, BaseModel;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'parent_id',
        'sort_order',
        'status',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'family' => 'json',
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
    ];

    /**
     * @var string
     */
    protected $primaryKey = 'product_category_id';

    protected $with = [
        'products',
        'paths',
    ];

    /**
     * 客製化搜尋
     *
     * @param array $request
     * @return object
     */
    public function search(array $request) :object
    {
        $query = $this;
        foreach ($request as $field => $value) {
            if (!$value) {
                continue;
            }

            switch ($field) {
                case 'family':
                    $query = $query->where($field, 'like', '%' . $value . '%');
                    continue 2;
                case 'parent_id':
                case 'status':
                case 'level':
                    if (is_array($value)) {
                        $query = $query->whereIn($field, $value);
                    } else {
                        $query = $query->where($field, $value);
                    }
                    continue 2;
                case 'language':
                    $query = $query->join('product_category_details', function ($query) use ($field, $value) {
                        $query->on(
                            'product_categories.product_category_id', '=', 'product_category_details.product_category_id'
                        )->where($field, $value);
                    });
                    continue 2;
            }
        }

        return $query;
    }

    /**
     * product
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function products() :\Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'product_to_category', 'product_category_id', 'product_id');
    }

    /**
     * details
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function details() :\Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ProductCategoryDetail::class, 'product_category_id');
    }

    /**
     * detail
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function detail() :\Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(ProductCategoryDetail::class, 'product_category_id');
    }

    /**
     * paths
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function paths()
    {
        return $this->hasMany(\App\Models\Products\ProductCategoryPath::class, 'path_id', 'product_category_id')->orderBy('level');
    }

    /**
     * families
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function families()
    {
        return $this->hasMany(\App\Models\Products\ProductCategoryPath::class, 'product_category_id', 'product_category_id')->orderBy('level');
    }
}
