<?php

namespace App\Http\Controllers\Admins\Systems;

use App\Http\Controllers\Controller;
use App\Services\Systems\SystemMenuService;

class SystemMenuController extends Controller
{
    /**
     * @var $service
     */
    private $service;

    /**
     * SystemMenuController constructor.
     *
     * @param SystemMenuService $service
     */
    public function __construct(SystemMenuService $service)
    {
        $this->service = $service;
    }

    /**
     * lists 取系統選單列表
     *
     * @return string
     */
    public function lists()
    {
        try {
            return response($this->service->lists());
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            abort(404, trans('common.noExists'));
        }
    }
}
