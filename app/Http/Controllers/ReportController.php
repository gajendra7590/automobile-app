<?php

namespace App\Http\Controllers;

use App\Models\BankFinancer;
use App\Models\BikeBrand;
use App\Models\Broker;
use App\Models\Purchase;
use App\Models\Salesman;
use App\Traits\DownloadReportHelper;
use Facade\Ignition\QueryRecorder\Query;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    use DownloadReportHelper;

    public function index()
    {
        return view('admin.reports.index', ['data' => []]);
    }

    public function loadReportSection(Request $request)
    {
        $postData = $request->all();
        $type = isset($postData['type']) ? $postData['type'] : 'purchase';
        $view = 'purchase';
        $data = array();
        $dropdowns = [];
        switch ($type) {
            case 'vehicle_purchase_register' :
                $view = 'vehicle-purchase-register';
                $dropdowns = ['brands'];
                break;
            case 'pending_purchase_invoice' :
                $view = 'pending-purchase-invoice';
                $dropdowns = ['brands'];
                break;
            case 'vehicle_stock_inventory' :
                $view = 'vehicle-stock-inventory';
                $dropdowns = ['brands','brokers'];
                break;
            case 'quotation_list' :
                $view = 'quotation-list';
                $dropdowns = ['brands','financers'];
                break;
            case 'sales_register' :
                $view = 'sales-register';
                $dropdowns = ['brands','financers','salesmans'];
                break;
            case 'brokers_agents' :
                $view = 'brokers-agents';
                $dropdowns = ['brands'];
                break;
            case 'financers' :
                $view = 'financers';
                $dropdowns = ['brands'];
                break;
            case 'accounts' :
                $view = 'accounts';
                $dropdowns = ['brands'];
                break;
            case 'rto' :
                $view = 'rto';
                $dropdowns = ['brands'];
                break;
            case 'receipt_voucher' :
                $view = 'receipt-voucher';
                $dropdowns = ['brands'];
                break;
            case 'payment_voucher' :
                $view = 'payment-voucher';
                $dropdowns = ['brands'];
                break;
            default:
                $view = 'purchase';
                break;
        }
        if (in_array('brands', $dropdowns)) {
            $data['brands'] = BikeBrand::get();
        }
        if (in_array('brokers', $dropdowns)) {
            $data['brokers'] = Broker::get();
        }
        if (in_array('financers', $dropdowns)) {
            $data['financers'] = BankFinancer::get();
        }
        if (in_array('salesmans', $dropdowns)) {
            $data['salesmans'] = Salesman::get();
        }
        $data['action'] = route('downloadReport');
        $data['type'] = $type;

        return response()->json([
            'status'     => true,
            'statusCode' => 200,
            'message'    => ucfirst($view) . " report data loaded.",
            'data'       => view('admin.reports.ajax.' . $view, $data)->render()
        ]);
    }

    public function downloadReport(Request $request) {
        $result = self::getReport();
        $filename = (request('type') ?? 'purchase') . "_report.csv";
        header('Content-Type: application/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '";');
        $f = fopen('php://output', 'w');
        foreach ($result as $line) {
            fputcsv($f, $line);
        }
        fclose($f);
        exit();
    }
}
