<?php

namespace App\Models\Products;

use App\Models\Medias\Image;
use App\Models\Medias\Related;
use App\Traits\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Repositories\Systems\LogRecordRepository;
use App\Traits\HostScope;

class Product extends Model
{
    use HasFactory, LogRecordRepository, HostScope, BaseModel;

    // 啟用狀態
    const ENABLE = 'Y';

    // 進用狀態
    const DISABLE = 'N';

    // 商品狀態
    const STATUSES = [
        self::ENABLE,
        self::DISABLE,
    ];

    /**
     * @var $fillable
     */
    protected $fillable = [
        'product_category_id',
        'model',
        'cost_price',
        'price',
        'sort_order',
        'status',
        'view',
        'upload_id',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'cost_price' => 'double',
        'price' => 'double',
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
    ];

    /**
     * @var string
     */
    protected $primaryKey = 'product_id';

    protected $with = [];

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
                case 'upload_id':
                case 'status':
                    if (is_array($value)) {
                        $query = $query->whereIn($field, $value);
                    } else {
                        $query = $query->where($field, $value);
                    }
                    continue 2;
                case 'language':
                    $query = $query->join('product_details', function ($query) use ($field, $value) {
                        $query->on(
                            'products.product_id', '=', 'product_details.product_id'
                        )->where($field, $value);
                    });
                    continue 2;
                case 'product_category_id':
                    $query = $query->whereHas('categories', function ($query) use ($value) {
                        $query->where('product_to_category.product_category_id', $value);
                    });
                    continue 2;
                case 'name':
                case 'model':
                    $query = $query->where($field, 'like', '%' . $value . '%');
                    continue 2;
            }
        }

        return $query;
    }

    /**
     * productCategories
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function categories()
    {
        return $this->belongsToMany(ProductCategory::class, 'product_to_category', 'product_id', 'product_category_id');
    }

    /**
     * productDetails
     *
     * @return \Illuminate\Database\Eloquent\Relations\hasMany
     */
    public function details()
    {
        return $this->hasMany(ProductDetail::class, 'product_id', 'product_id');
    }

    /**
     * detail
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function detail() :\Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(ProductDetail::class, 'product_id')->withDefault();
    }

    /**
     * productOptions
     *
     * @return \Illuminate\Database\Eloquent\Relations\hasMany
     */
    public function options()
    {
        return $this->hasMany(ProductOption::class, 'product_id', 'product_id');
    }

    /**
     * option
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function option() :\Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(ProductOption::class, 'product_id');
    }

    public function image()
    {
        return $this->hasOne(Image::class, 'upload_id', 'upload_id')->where('type', 'image');
    }

    public function relateImage()
    {
        return $this->hasMany(Related::class, 'id', 'product_id')->where('category', 'product');
    }

    public function relateToImage()
    {
        return $this->belongsToMany(Related::class, 'upload_related', 'id', 'upload_id')->where('category', 'product');
    }
}
