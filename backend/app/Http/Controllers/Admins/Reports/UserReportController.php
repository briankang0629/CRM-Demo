<?php

namespace App\Http\Controllers\Admins\Reports;

use App\Http\Controllers\Controller;

class UserReportController extends Controller
{
    /**
     * getUserTotal ζε‘ζΈι
     *
     * @since 0.0.1
     * @version 0.0.1
     * @return string
     */
    public function getUserTotal()
    {
        return (double)\App\Models\Users\User::count();
    }
}
