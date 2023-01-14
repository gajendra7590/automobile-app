<?php

namespace App\Http\Controllers;

use App\Models\DocumentSectionTypes;
use App\Models\DocumentUploads;
use App\Models\Purchase;
use App\Models\Quotation;
use App\Models\Sale;
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
    public function index()
    {
        if (!request()->ajax()) {
            return view('admin.documentUploads.index');
        } else {
            $data = DocumentUploads::with(['sectionType'])->select('*');
            return DataTables::of($data)
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

    public function getActions($row)
    {
        $action = '<div class="action-btn-container">';
        $action .= '<a href="' . route('documentUploads.edit', ['documentUpload' => $row->id]) . '" class="btn btn-sm btn-warning ajaxModalPopup" data-modal_title="Update GST Rate"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>';
        // $action .= '<a href="' . route('gst-rates.destroy', ['gst-rate' => $row->id]) . '" data-id="' . $row->id . '" class="btn btn-sm btn-danger ajaxModalDelete" data-modal_title="Delete State"><i class="fa fa-trash-o" aria-hidden="true"></i></a>';
        $action .= '</div>';
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
                        $data = $data->where('customer_name	', 'LIKE', '%' . $postData['search'] . '%');
                    }
                    $data = $data->get();
                    break;
                case '4':
                    $data = Sale::select('id', 'purchase_id', DB::raw('CONCAT(customer_name) AS text'))->with(['purchase']);
                    if (isset($postData['search']) && ($postData['search'] != "")) {
                        $data = $data->where('customer_gender', 'LIKE', '%' . $postData['search'] . '%')
                            ->orwhere('customer_relationship', 'LIKE', '%' . $postData['search'] . '%')
                            ->orwhere('customer_relationship', 'LIKE', '%' . $postData['search'] . '%')
                            ->orwhere('customer_guardian_name', 'LIKE', '%' . $postData['search'] . '%');
                    }
                    $data = $data->get();
                    dd($data->toArray());
                    break;
                case '5':
                    # code...
                    break;
                case '6':
                    # code...
                    break;
                case '7':
                    # code...
                    break;
                case '8':
                    # code...
                    break;
                case '9':
                    # code...
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
