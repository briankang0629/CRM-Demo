<?php

namespace App\Http\Controllers\Admins\Systems;

use App\Http\Controllers\Controller;
use App\Services\Systems\LogRecordService;
use App\Transformers\Admins\Systems\LogRecordTransformer;

class LogRecordController extends Controller
{
    /**
     * @var $service
     */
    private $service;

    /**
     * LogRecordController constructor.
     *
     * @param LogRecordService $service
     */
    public function __construct(LogRecordService $service)
    {
        $this->service = $service;
    }

    /**
     * lists
     *
     * @param \App\Http\Requests\Admins\Systems\LogRecord\ListRequest $request
     * @return \Spatie\Fractal\Fractal
     */
    public function lists(\App\Http\Requests\Admins\Systems\LogRecord\ListRequest $request)
    {
        $logRecords = $this->service->lists($request->toArray());

        return fractal($logRecords, LogRecordTransformer::class);
    }

    /**
     * info
     *
     * @param $id
     * @return \Illuminate\Http\Response
     */
    public function info($id)
    {
        try {
            $logRecord = $this->service->info($id);

            return response($logRecord);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            abort(404, trans('common.noExists'));
        }
    }

    /**
     * setting
     *
     * @param $id
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function setting()
    {
        return response(config('system.logRecordSetting'));
    }
}
