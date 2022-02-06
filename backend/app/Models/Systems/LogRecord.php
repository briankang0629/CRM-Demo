<?php

namespace App\Models\Systems;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\BaseModel;
use App\Traits\HostScope;

class LogRecord extends Model
{
    use HasFactory, HostScope, BaseModel;

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
    protected $primaryKey = 'log_record_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'log_id',
        'admin_id',
        'user_id',
        'account',
        'class',
        'remote_ip',
        'server_addr',
        'server_info',
        'host',
        'old_content',
        'new_content',
        'path',
        'new_date',
    ];

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
            switch ($field) {
                case 'log_id':
                case 'class':
                case 'new_date':
                case 'remote_ip':
                    if (is_array($value)) {
                        $query = $query->whereIn($field, $value);
                    } else {
                        $query = $query->where($field, $value);
                    }
                    continue 2;
                case 'account':
                    $query = $query->where('account', 'like', '%' . $value . '%');
                    continue 2;
                case 'start_date':
                    $query = $query->where('created_at', '>', $value);
                    continue 2;
                case 'end_date':
                    $query = $query->where('created_at', '<',  $value);
                    continue 2;
            }
        }

        return $query;
    }

    public function getOldContentAttribute()
    {
        if (!array_get($this->attributes, 'old_content')) {
            return [];
        }

        return json_decode($this->attributes['old_content'], true);
    }

    public function getNewContentAttribute()
    {
        if (!array_get($this->attributes, 'new_content')) {
            return [];
        }

        return json_decode($this->attributes['new_content'], true);
    }
}
