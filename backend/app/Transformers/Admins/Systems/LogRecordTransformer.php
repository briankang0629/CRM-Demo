<?php

namespace App\Transformers\Admins\Systems;

use League\Fractal\TransformerAbstract;

class LogRecordTransformer extends TransformerAbstract
{
    /**
     * List of resources to automatically include
     *
     * @var array
     */
    protected $defaultIncludes = [
        //
    ];

    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected $availableIncludes = [
        //
    ];

    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform($logRecord)
    {
        return [
            'log_record_id' => $logRecord->log_record_id,
            'log_id' => $logRecord->log_id,
            'class' => $logRecord->class,
            'log_name' => config('system.logRecordSetting')[$logRecord->log_id]['class'],
            'admin_id' => $logRecord->admin_id,
            'user_id' => $logRecord->user_id,
            'account' => $logRecord->account,
            'remote_ip' => $logRecord->remote_ip,
            'server_addr' => $logRecord->server_addr,
            'host' => $logRecord->host,
            'path' => $logRecord->path,
            'new_date' => $logRecord->new_date,
            "created_at" => $logRecord->created_at,
            "updated_at" => $logRecord->updated_at,
        ];
    }
}
