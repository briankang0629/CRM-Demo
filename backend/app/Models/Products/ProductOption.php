<?php

namespace App\Models\Products;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\BaseModel;
use App\Traits\HostScope;

class ProductOption extends Model
{
    use HasFactory, HostScope, BaseModel;

    const RADIO = 'radio';

    const CHECKBOX = 'checkbox';

    const SELECT = 'select';

    const TYPES = [
        self::RADIO,
        self::CHECKBOX,
        self::SELECT
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    /**
     * @var $fillable
     */
    protected $fillable = [
        'product_id',
        'type',
        'required',
        'sort_order',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [];

    /**
     * @var string
     */
    protected $primaryKey = 'product_option_id';

    /**
     * with
     * @var array
     */
    protected $with = [];

    /**
     * productOptionValues
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function values() :\Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ProductOptionValue::class, 'product_option_id', 'product_option_id');
    }

    /**
     * value
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function value() :\Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(ProductOptionValue::class, 'product_option_id')->withDefault();
    }

    /**
     * details
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function details() :\Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ProductOptionDetail::class, 'product_option_id', 'product_option_id');
    }

    /**
     * detail
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function detail() :\Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(ProductOptionDetail::class, 'product_option_id', 'product_option_id')->withDefault();
    }
}
