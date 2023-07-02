<?php

namespace App\Http\Controllers;

use App\Models\BankFinancer;
use App\Models\BikeBrand;
use App\Models\Branch;
use App\Models\Broker;
use App\Models\RtoAgent;
use App\Models\Sale;
use App\Models\Salesman;
use App\Traits\CommonHelper;
use App\Traits\DownloadReportHelper;
use Exception;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    use DownloadReportHelper,CommonHelper;

    public function index()
    {
        return view('admin.reports.index');
    }

    public function loadReportSection(Request $request)
    {
        $postData = $request->all();
        $type = isset($postData['type']) ? $postData['type'] : 'purchase';
        $view = 'purchase';
        $data = array();
        $dropdowns = [];
        switch ($type) {
            case 'vehicle_purchase_register':
                $view = 'vehicle-purchase-register';
                $dropdowns = ['brokers', 'brands'];
                break;
            case 'pending_purchase_invoice':
                $view = 'pending-purchase-invoice';
                $dropdowns = ['brokers', 'brands'];
                break;
            case 'vehicle_stock_inventory':
                $view = 'vehicle-stock-inventory';
                $dropdowns = ['brokers', 'brands', 'brokers'];
                break;
            case 'quotation_list':
                $view = 'quotation-list';
                $dropdowns = ['brokers', 'brands', 'financers'];
                break;
            case 'sales_register':
                $view = 'sales-register';
                $dropdowns = ['branches','brands', 'brokers', 'financers', 'salesmans'];
                break;
            case 'brokers_agents':
                $view = 'brokers-agents';
                $dropdowns = ['brokers', 'brands'];
                break;
            case 'financers':
                $view = 'financers';
                $dropdowns = ['brokers', 'branches'];
                break;
            case 'rto':
                $view = 'rto';
                $dropdowns = ['rto_agents', 'brands'];
                break;
            case 'accounts':
                $view = 'accounts';
                $dropdowns = ['brokers', 'branches','financers'];
                break;
            case 'customer_wise_payment':
                $view = 'customer-wise-payment';
                $dropdowns = ['branches'];
                break;
            case 'financer_wise_payment':
                $view = 'financer-wise-payment';
                $dropdowns = ['branches','financers'];
                break;
            case 'rto_agent_payment':
                $view = 'rto-agent-payment';
                $dropdowns = ['rto_agents', 'depositeSources'];
                break;
            case 'cust_trans_report':
                $view = 'customer-transaction-report';
                $dropdowns = ['branches', 'depositeSources'];
                break;
            default:
                $view = 'purchase';
                break;
        }

        if (in_array('branches', $dropdowns)) {
            $data['branches'] = Branch::whereActiveStatus(1)->get();
        }

        if (in_array('brands', $dropdowns)) {
            $data['brands'] = BikeBrand::whereActiveStatus(1)->get();
        }

        if (in_array('brokers', $dropdowns)) {
            $data['brokers'] = Broker::whereActiveStatus(1)->get();
        }

        if (in_array('financers', $dropdowns)) {
            $data['financers'] = BankFinancer::whereActiveStatus(1)->get();
        }

        if (in_array('salesmans', $dropdowns)) {
            $data['salesmans'] = Salesman::whereActiveStatus(1)->get();
        }

        if (in_array('customers', $dropdowns)) {
            $data['customers'] = Sale::select('id','sp_account_id','customer_name','customer_relationship','customer_guardian_name')->get();
        }

        if (in_array('rto_agents', $dropdowns)) {
            $data['rto_agents'] = RtoAgent::select('id','agent_name')->get();
        }

        if (in_array('depositeSources', $dropdowns)) {
            $data['depositeSources'] = depositeSources();
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

    public function downloadReport(Request $request)
    {
        try {
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
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage(), 'file' => $e->getFile(), 'line' => $e->getLine()], 422);
        }
    }
}
