<?php

namespace App\Models\Users;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use App\Repositories\Systems\LogRecordRepository;
use App\Traits\BaseModel;
use App\Traits\HostScope;

class Admin extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens, LogRecordRepository, HostScope, BaseModel;

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
        'status',
        'is_sub',
        'ip',
        'permission_id',
        'last_login_time',
        'upload_id',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'email_verified_at'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'family' => 'json',
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
    ];

    /**
     * @var string
     */
    protected $primaryKey = 'admin_id';

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
                case 'parent_id':
                case 'permission_id':
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
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function permission()
    {
        return $this->hasOne('App\Models\Users\Permission', 'permission_id', 'permission_id');
    }
}
