<?php

namespace App\Services\Systems;
use App\Models\Systems\LogRecord;

class LogRecordService
{
    const ORDER_BY = 'created_at';

    /**
     * @var object
     */
    private $logRecord;

    /**
     * LogRecordService constructor.
     *
     * @param LogRecord $logRecord
     */
    public function __construct(LogRecord $logRecord)
    {
        $this->logRecord = $logRecord;
    }

    /**
     * lists
     *
     * @param array $request
     * @return mixed
     */
    public function lists(array $request)
    {
        $logRecords = $this->logRecord->search($request)->orderByDesc(self::ORDER_BY)->paginate(per_page());

        return $logRecords;
    }

    /**
     * info
     *
     * @param integer $id
     * @return mixed
     */
    public function info(int $id)
    {
        $logRecord = $this->logRecord->findOrFail($id);

        return $logRecord;
    }
}
