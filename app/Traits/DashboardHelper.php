<?php

namespace App\Traits;

use App\Models\Purchase;
use App\Models\Quotation;
use App\Models\RtoRegistration;
use App\Models\Sale;
use Illuminate\Support\Facades\Auth;

trait DashboardHelper
{

    public static function branchWhere($key = 'branch_id')
    {
        $branch_id = 0;
        if (Auth::user()) {
            $branch_id = intval(Auth::user()->branch_id);
        }

        $where = array();
        if ($branch_id > 0) {
            $where[$key] = $branch_id;
        }
        return $where;
    }

    // PURCHASE

    public static function totalPurchases()
    {
        $where = self::branchWhere('bike_branch');
        return Purchase::where($where)->count();
    }

    public static function totalPurchasesSoldOut()
    {
        $where = self::branchWhere('bike_branch');
        return Purchase::where($where)->where('status', '2')->count();
    }

    public static function totalPurchasesInStock()
    {
        $where = self::branchWhere('bike_branch');
        return Purchase::where($where)->where('status', '1')->count();
    }
    //// PURCHASE

    // SALES
    public static function totalSales()
    {
        $where = self::branchWhere('branch_id');
        return Sale::where($where)->count();
    }

    public static function totalSalesOpen()
    {
        $where = self::branchWhere('branch_id');
        return Sale::where($where)->where('status', 'open')->count();
    }

    public static function totalSalesClose()
    {
        $where = self::branchWhere('branch_id');
        return Sale::where($where)->where('status', 'close')->count();
    }

    //// SALES

    // QUOTATION

    public static function totalQuotations()
    {
        $where = self::branchWhere('branch_id');
        return Quotation::where($where)->count();
    }

    public static function totalQuotationOpen()
    {
        $where = self::branchWhere('branch_id');
        return Quotation::where($where)->where('status', 'open')->count();
    }

    public static function totalQuotationClose()
    {
        $where = self::branchWhere('branch_id');
        return Quotation::where($where)->where('status', 'close')->count();
    }

    //// QUOTATION

    public static function totalRtoRegistrations()
    {
        $where = self::branchWhere('branch_id');
        return RtoRegistration::whereHas('sale', function ($sale) use ($where) {
            $sale->where($where);
        })->count();
    }
}
