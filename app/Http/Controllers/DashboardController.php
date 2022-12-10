<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Function for load dashboard view
     * @method GET
    */
    public function dashboardGet() {
        return view('admin.dashboard.index');
    }

}
