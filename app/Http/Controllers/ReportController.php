<?php

namespace App\Http\Controllers;

use App\Models\BikeBrand;
use App\Models\BikeModel;
use App\Models\Purchase;
use App\Models\Quotation;
use App\Models\RtoRegistration;
use App\Models\Sale;
use App\Traits\DownloadReportHelper;
use Database\Seeders\BrandSeeder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use League\CommonMark\Extension\SmartPunct\Quote;

class ReportController extends Controller
{
    use DownloadReportHelper;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.reports.index', ['data' => []]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function loadReportSection(Request $request)
    {
        $postData = $request->all();
        $type = isset($postData['type']) ? $postData['type'] : 'purchase';
        $view = 'purchase';
        $data = array();
        $dropdowns = [];
        switch ($type) {
            case 'purchase':
                $view = 'purchase';
                $dropdowns = ['brands'];
                break;
            case 'sales':
                $view = 'sales';
                break;
            case 'quotations':
                $view = 'quotations';
                break;
            case 'dues':
                $view = 'dues';
                break;
            case 'rto':
                $view = 'rto';
                break;
            default:
                $view = 'purchase';
                break;
        }
        if (in_array('brands', $dropdowns)) {
            $data['brands'] = BikeBrand::get();
        }
        $data['action'] = route('downloadReport');

        return response()->json([
            'status'     => true,
            'statusCode' => 200,
            'message'    => ucfirst($view) . " report data loaded.",
            'data'       => view('admin.reports.ajax.' . $view, $data)->render()
        ]);
    }

    public function downloadReport(Request $request)
    {
        $postData = $request->all();
        $type = isset($postData['type']) ? $postData['type'] : 'purchase';
        $report = self::getReport($postData);
        $result = [];
        foreach($report as $value) {
            $result[] = (array)$value;
        }
        $filename = $type . "_report.csv";
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
