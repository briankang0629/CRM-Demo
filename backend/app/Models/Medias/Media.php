<?php

namespace App\Models\Medias;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Repositories\Systems\LogRecordRepository;
use App\Traits\BaseModel;
use App\Traits\HostScope;

abstract class Media extends Model
{
    use HasFactory, LogRecordRepository, HostScope, BaseModel;

    /**
     * 多媒體類型
     */
    protected $type;

    /**
     * @var string
     */
    protected $table = 'uploads';

    /**
     * @var string
     */
    protected $primaryKey = 'upload_id';

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
    ];

    protected $fillable = [
        'file_name',
        'origin_name',
        'type',
        'extension',
        'size',
        'height',
        'width',
        'folder',
    ];

    /**
     *
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
                case 'type':
                case 'folder':
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
}
