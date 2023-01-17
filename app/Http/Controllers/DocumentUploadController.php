<?php

namespace App\Http\Controllers;

use App\Models\BankFinancer;
use App\Models\Broker;
use App\Models\BikeDealer;
use App\Models\DocumentSectionTypes;
use App\Models\DocumentUploads;
use App\Models\Purchase;
use App\Models\Quotation;
use App\Models\RtoRegistration;
use App\Models\Sale;
use App\Models\SalePaymentAccounts;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class DocumentUploadController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (!request()->ajax()) {
            return view('admin.documentUploads.index');
        } else {

            $postData = $request->all();
            $search_string = isset($postData['search']['value']) ? $postData['search']['value'] : "";

            $data = DocumentUploads::with(['sectionType'])->select('*');
            return DataTables::of($data)
                ->filter(function ($query) use ($search_string) {

                    if ($search_string != "") {
                        $query->where('file_name', 'LIKE', '%' . $search_string . '%')
                            ->orwhere('file_extention', 'LIKE', '%' . $search_string . '%')
                            ->orWhereHas('sectionType', function ($q) use ($search_string) {
                                $q->where('name', 'LIKE', '%' . $search_string . '%');
                            });
                    }
                })
                ->addIndexColumn()
                ->addColumn('section_type', function ($row) {
                    return isset($row->sectionType->name) ? $row->sectionType->name : '--';
                })
                ->addColumn('created_at', function ($row) {
                    return isset($row->created_at) ? date('Y-m-d', strtotime($row->created_at)) : '--';
                })
                ->addColumn('action', function ($row) {
                    return $this->getActions($row);
                })
                ->rawColumns(['section_type', 'created_at', 'action'])
                ->make(true);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $documentTypes = DocumentSectionTypes::select('id', 'name')->where('status', '1')->get();
        $data = array(
            'method' => 'POST',
            'action' => route('documentUploads.store'),
            'documentTypes' => $documentTypes
        );
        return view('admin.documentUploads.create', $data);
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
            $validator = Validator::make($postData, [
                'document_section_type' => 'required|exists:document_section_types,id',
                'document_section_id' => 'required|numeric',
                'document_section_title' => 'nullable|string',
                'document_file' => 'required',
            ]);
            //If Validation failed
            if ($validator->fails()) {
                return response()->json([
                    'status'     => false,
                    'statusCode' => 419,
                    'message'    => $validator->errors()->first(),
                    'errors'     => $validator->errors(),
                ]);
            }

            $document_type = DocumentSectionTypes::where('id', $postData['document_section_type'])->value('short_name');
            $document_type = strtolower($document_type);
            $document_type = str_replace(" ", "_", $document_type);
            $file_name = $document_type . '_' . str_pad($postData['document_section_id'], 6, '0', STR_PAD_LEFT) . '_' . date('dmYhis');

            // dd($request->document_file);
            $file_extention = $request->document_file->getClientOriginalExtension();
            $file_mime_type = $request->document_file->getClientMimeType();
            $file_size = $request->document_file->getSize();
            $file_size_mb = number_format($file_size / 1048576, 2) . ' MB';

            $file_name = $file_name . '.' . $file_extention;

            $request->document_file->move(public_path('/uploads'), $file_name);
            DocumentUploads::create([
                'section_type'      => isset($postData['document_section_type']) ? $postData['document_section_type'] : 0,
                'section_id'        => isset($postData['document_section_id']) ? $postData['document_section_id'] : 0,
                'file_name'         => $file_name,
                'file_extention'    => $file_extention,
                'file_mime_type'    => $file_mime_type,
                'file_size'         => $file_size_mb,
                'file_description'  => isset($postData['document_section_title']) ? $postData['document_section_title'] : ""
            ]);
            return response()->json([
                'status'     => true,
                'statusCode' => 200,
                'message'    => trans('messages.create_success')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status'     => false,
                'statusCode' => 419,
                'message'    => $e->getMessage()
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
        $docModel = DocumentUploads::where(['id' => $id])->select('*')->first();
        if (!$docModel) {
            return response()->json([
                'status'     => false,
                'statusCode' => 419,
                'message'    => trans('messages.id_not_exist', ['id' => $id])
            ]);
        }
        return response()->json([
            'status'     => true,
            'statusCode' => 200,
            'message'    => trans('messages.ajax_model_loaded'),
            'data'       => view('admin.documentUploads.ajaxModal', ['data' => $docModel])->render()
        ]);
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

    public function getActions($row)
    {
        $action  = '<div class="dropdown pull-right customDropDownOption"><button class="btn btn-xs btn-primary dropdown-toggle" type="button" data-toggle="dropdown" style="padding: 3px 10px !important;"><span class="caret"></span></button>';
        $action  .= '<ul class="dropdown-menu">';
        $action .= '<li><a href="' . route('documentUploads.edit', ['documentUpload' => $row->id]) . '" class="ajaxModalPopup" data-modal_title="Preview of uploaded document" data-modal_size="modal-lg">VIEW DOCUMENT</a></li>';
        $action  .= '</ul>';
        $action  .= '</div>';
        return $action;
    }

    public function getSectionTypeDropdown(Request $request)
    {
        $postData = $request->all();
        $data = array();
        if (isset($postData['type_id']) && (intval($postData['type_id']) > 0)) {
            switch ($postData['type_id']) {
                case '1':
                    $data = User::select('id', DB::raw('name as text'));
                    if (isset($postData['search']) && ($postData['search'] != "")) {
                        $data = $data->where('name', 'LIKE', '%' . $postData['search'] . '%');
                    }
                    $data = $data->get();
                    break;
                case '2':
                    $data = Purchase::select('id', DB::raw('CONCAT(sku,"-", dc_number, "-", vin_number,"-",hsn_number) AS text'));
                    if (isset($postData['search']) && ($postData['search'] != "")) {
                        $data = $data->where('sku', 'LIKE', '%' . $postData['search'] . '%')
                            ->orwhere('dc_number', 'LIKE', '%' . $postData['search'] . '%')
                            ->orwhere('vin_number', 'LIKE', '%' . $postData['search'] . '%')
                            ->orwhere('hsn_number', 'LIKE', '%' . $postData['search'] . '%');
                    }
                    $data = $data->get();
                    break;
                case '3':
                    $data = Quotation::select('id', DB::raw('customer_name	 as text'));
                    if (isset($postData['search']) && ($postData['search'] != "")) {
                        $data = $data->where('customer_name', 'LIKE', '%' . $postData['search'] . '%');
                    }
                    $data = $data->get();
                    break;
                case '4':
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
                case '5':
                    $Query = SalePaymentAccounts::select('id', 'sale_id')->with([
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
                case '6':
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
                case '7':
                    $data = Broker::select('id', DB::raw('name as text'));
                    if (isset($postData['search']) && ($postData['search'] != "")) {
                        $data = $data->where('name', 'LIKE', '%' . $postData['search'] . '%');
                    }
                    $data = $data->get();
                    break;
                case '8':
                    $data = BankFinancer::select('id', DB::raw('bank_name as text'));
                    if (isset($postData['search']) && ($postData['search'] != "")) {
                        $data = $data->where('bank_name', 'LIKE', '%' . $postData['search'] . '%');
                    }
                    $data = $data->get();
                    break;
                case '9':
                    $data = BikeDealer::select('id', DB::raw('company_name as text'));
                    if (isset($postData['search']) && ($postData['search'] != "")) {
                        $data = $data->where('company_name', 'LIKE', '%' . $postData['search'] . '%');
                    }
                    $data = $data->get();
                    break;
                case '10':
                    # code...
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
}
