<?php

namespace App\Models\Users;

use App\Repositories\Systems\LogRecordRepository;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\BaseModel;
use App\Traits\HostScope;

class UserGroup extends Model
{
    use HasFactory, LogRecordRepository, HostScope, BaseModel;

    protected $primaryKey = 'user_group_id';

    protected $fillable = [
        'name',
        'description'
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
                case 'name':
                    $query = $query->where($field, 'like', '%' . $value . '%');
                    continue 2;
            }
        }

        return $query;
    }

    // start related region

    /**
     * users
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function users()
    {
        return $this->hasMany(\App\Models\Users\User::class, 'user_group_id', 'user_group_id');
    }

    // end related region
    // start attribute region

    // end attribute region

}
