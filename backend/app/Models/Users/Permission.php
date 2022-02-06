<?php

namespace App\Models\Users;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Repositories\Systems\LogRecordRepository;
use App\Traits\BaseModel;
use App\Traits\HostScope;

class Permission extends Model
{
    use HasFactory, LogRecordRepository, HostScope, BaseModel;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'permission',
        'status'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'permission' => 'json',
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
    ];

    /**
     * @var string
     */
    protected $primaryKey = 'permission_id';

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
                case 'name':
                    $query = $query->where($field, 'like', '%' . $value . '%');
                    continue 2;
                case 'status':
                    if (is_array($value)) {
                        $query = $query->whereIn($field, $value);
                    } else {
                        $query = $query->where($field, $value);
                    }
                    continue 2;
            }
        }

        return $query;
    }

    /**
     * admin
     *
     * @return \Illuminate\Database\Eloquent\Relations\hasMany
     */
    public function admin()
    {
        return $this->hasMany('App\Models\Users\Admin', 'permission_id', 'permission_id');
    }
}
