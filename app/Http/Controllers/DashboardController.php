<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use App\Models\Quotation;
use App\Models\RtoRegistration;
use App\Models\Sale;
use Illuminate\Http\Request;


use App\Traits\DashboardHelper;

class DashboardController extends Controller
{
    use DashboardHelper;
    /**
     * Function for load dashboard view
     * @method GET
     */
    public function dashboardIndex()
    {

        $total_sales = self::totalSales();
        $total_registration = self::totalRtoRegistrations();

        $data = array(
            'totalPurchases'        => self::totalPurchases(),
            'totalSales'            => self::totalSales(),
            'totalQuotations'       => self::totalQuotations(),
            'totalRtoRegistrations' => self::totalRtoRegistrations(),

            'totalPurchasesInStock'        => self::totalPurchasesInStock(),
            'totalPurchasesSoldOut'        => self::totalPurchasesSoldOut(),

            'totalSalesOpen'        =>  self::totalSalesOpen(),
            'totalSalesClose'        =>  self::totalSalesClose(),

            'totalQuotationOpen'        => self::totalQuotationOpen(),
            'totalQuotationClose'        => self::totalQuotationClose(),

            'totalRegistionDone'        => $total_registration,
            'totalRegistionPending'       => ($total_sales - $total_registration),
        );
        return view('admin.dashboard.index', ['data' => $data]);
    }
}
