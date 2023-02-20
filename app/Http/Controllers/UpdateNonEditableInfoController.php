<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use App\Models\Quotation;
use App\Models\RtoRegistration;
use App\Models\Sale;
use App\Traits\CommonHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UpdateNonEditableInfoController extends Controller
{
    use CommonHelper;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sectionTypes = [
            'quotaions' => 'Quotaions',
            'purchases' => 'Purchases',
            'sales' => 'Sales',
            'rto_registration' => 'RTO Registration'
        ];
        $data = array(
            'method' => 'POST',
            'action' => route('updateNonEditableDetail.store'),
            'sectionTypes' => $sectionTypes
        );
        return view('admin.updateNotEditableInfo.create', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $postData = $request->all();
        $data = array();
        switch (request('document_type')) {
            case 'quotaions':
                $data['data'] = Quotation::find(request('model_id'));
                $data['salesmans'] = self::_getSalesman();
                $data['states'] = self::_getStates();
                $data['districts'] = self::_getDistricts();
                $data['cities'] = self::_getCities();
                break;
            case 'purchases':
                # code...
                break;
            case 'sales':
                # code...
                break;
            case 'rto_registration':
                # code...
                break;
            default:
                # code...
                break;
        }

        return response()->json([
            'status'     => true,
            'statusCode' => 200,
            'message'    => trans('messages.ajax_model_loaded'),
            'data'       => view(
                'admin.updateNotEditableInfo.ajax.' . $postData['document_type'],
                ['action' => route('updateNonEditableDetail.store')],
                $data
            )->render()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $postData = $request->all();
            $status = false;
            if (request('type')) {
                switch (request('type')) {
                    case 'quotaions':
                        $qModel = Quotation::find(request('id'));
                        if ($qModel) {
                            unset($postData['id']);
                            unset($postData['type']);
                            $qModel->update($postData);
                            $status = true;
                        }
                        break;
                    case 'purchases':
                        $qModel = Purchase::find(request('id'));
                        if ($qModel) {
                            unset($postData['id']);
                            unset($postData['type']);
                            $qModel->update($postData);
                            $status = true;
                        }
                        break;
                    case 'sales':
                        $qModel = Sale::find(request('id'));
                        if ($qModel) {
                            unset($postData['id']);
                            unset($postData['type']);
                            $qModel->update($postData);
                            $status = true;
                        }
                        break;
                    case 'rto_registration':
                        $qModel = RtoRegistration::find(request('id'));
                        if ($qModel) {
                            unset($postData['id']);
                            unset($postData['type']);
                            $qModel->update($postData);
                            $status = true;
                        }
                        break;
                }
            }

            //IF Status Success
            if ($status == true) {
                return response()->json(['status' => true, 'statusCode' => 200, 'message' => trans('messages.update_success'),], 200);
            } else {
                return response()->json(['status' => false, 'statusCode' => 419, 'message' => trans('messages.update_error'),], 419);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status'     => false,
                'statusCode' => 419,
                'message'    => $e->getMessage(),
                'data'       => ['file' => $e->getFile(), 'line' => $e->getLine()]
            ]);
        }
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

    public function getDocumentTypeData(Request $request)
    {
        $postData = $request->all();
        $data = array();
        if (trim($postData['section_type'])) {
            switch ($postData['section_type']) {
                case 'purchases':
                    $data = Purchase::select('id', DB::raw('CONCAT(sku,"-", dc_number, "-", vin_number,"-",hsn_number) AS text'));
                    if (isset($postData['search']) && ($postData['search'] != "")) {
                        $data = $data->where('sku', 'LIKE', '%' . $postData['search'] . '%')
                            ->orwhere('dc_number', 'LIKE', '%' . $postData['search'] . '%')
                            ->orwhere('vin_number', 'LIKE', '%' . $postData['search'] . '%')
                            ->orwhere('hsn_number', 'LIKE', '%' . $postData['search'] . '%');
                    }
                    $data = $data->get();
                    break;
                case 'quotaions':
                    $data = Quotation::select('id', DB::raw('customer_name	 as text'));
                    if (isset($postData['search']) && ($postData['search'] != "")) {
                        $data = $data->where('customer_name', 'LIKE', '%' . $postData['search'] . '%');
                    }
                    $data = $data->get();
                    break;
                case 'sales':
                    $Query = Sale::select('id', 'purchase_id', 'customer_name')->with([
                        'purchases' => function ($q) {
                            $q->select('id', 'sku', 'vin_number', 'hsn_number', 'engine_number');
                        }
                    ]);
                    $data = array();
                    if (isset($postData['search']) && ($postData['search'] != "")) {
                        $search_string = $postData['search'];
                        $Query = $Query->where('customer_name', 'LIKE', '%' . $search_string . '%')
                            ->orWhereHas('purchases', function ($q) use ($search_string) {
                                $q->where('sku', 'LIKE', '%' . $search_string . '%')
                                    ->orWhere('vin_number', 'LIKE', '%' . $search_string . '%')
                                    ->orWhere('hsn_number', 'LIKE', '%' . $search_string . '%')
                                    ->orWhere('engine_number', 'LIKE', '%' . $search_string . '%');
                            });
                    }
                    $results = $Query->limit(100)->get();
                    foreach ($results as $k => $result) {
                        $data[$k] = array(
                            'id' => $result->id,
                            'text' => createStringSales($result)
                        );
                    }
                    break;
                case 'rto_registration':
                    $Query = RtoRegistration::select('id', 'sale_id')->with([
                        'sale' => function ($sale) {
                            $sale->with(['purchases' => function ($q) {
                                $q->select('id', 'sku', 'vin_number', 'hsn_number', 'engine_number');
                            }]);
                        }

                    ]);
                    $data = array();
                    if (isset($postData['search']) && ($postData['search'] != "")) {
                        $search_string = $postData['search'];
                        $Query = $Query->where('customer_name', 'LIKE', '%' . $search_string . '%')
                            ->orWhereHas('sale.purchases', function ($q) use ($search_string) {
                                $q->where('sku', 'LIKE', '%' . $search_string . '%')
                                    ->orWhere('vin_number', 'LIKE', '%' . $search_string . '%')
                                    ->orWhere('hsn_number', 'LIKE', '%' . $search_string . '%')
                                    ->orWhere('engine_number', 'LIKE', '%' . $search_string . '%');
                            });
                    }
                    $results = $Query->limit(100)->get();
                    foreach ($results as $k => $result) {
                        $data[$k] = array(
                            'id' => $result->id,
                            'text' => createStringSales($result->sale)
                        );
                    }
                    break;
                default:
                    # code...
                    break;
            }
        }

        return response()->json([
            'status'  => true,
            'results' => $data,
            'message' => "List retrieved successfully"
        ]);
    }

    public function getDocumentTypeModal(Request $request)
    {
        dd('getDocumentTypeModal');
        $postData = $request->all();
    }
}
