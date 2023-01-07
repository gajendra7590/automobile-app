<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Traits\CronHelper;

class DashboardController extends Controller
{
    use CronHelper;
    /**
     * Function for load dashboard view
     * @method GET
     */
    public function dashboardIndex()
    {
        return view('admin.dashboard.index');
    }
}
