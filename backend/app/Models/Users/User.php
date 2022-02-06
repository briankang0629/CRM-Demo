<?php

namespace App\Models\Users;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use App\Traits\BaseModel;
use App\Traits\HostScope;
use App\Repositories\Systems\LogRecordRepository;

class User extends Authenticatable
{
    use HasFactory, LogRecordRepository, Notifiable, HasApiTokens, HostScope, BaseModel;

    // 預設會員群組
    const DEFAULT_GROUP_NAME = 'common.userGroup.default';

    // 啟用狀態
    const ENABLE = 'Y';

    // 進用狀態
    const DISABLE = 'N';

    // 狀態
    const STATUSES = [
        self::ENABLE,
        self::DISABLE,
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'account',
        'email',
        'password',
        'ip',
        'mobile',
        'status',
        'upload_id',
        'user_group_id',
        'last_login_time',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * @var string
     */
    protected $primaryKey = 'user_id';

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
                case 'account':
                case 'name':
                case 'email':
                    $query = $query->where($field, 'like', '%' . $value . '%');
                    continue 2;
                case 'status':
                case 'user_group_id':
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

    // start related region

    public function group()
    {
        return $this->hasOne(\App\Models\Users\UserGroup::class, 'user_group_id', 'user_group_id');
    }

    // end related region
    // start attribute region

    public function getUserGroupAttribute()
    {
        if(!array_get($this->attributes, 'user_group_id')) {
            return trans(self::DEFAULT_GROUP_NAME);
        }

        return $this->group->find($this->attributes['user_group_id'])->name;
    }

    // end attribute region
}
