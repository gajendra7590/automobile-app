<?php

namespace App\Http\Controllers;

use App\Models\BikeDealer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class BikeDealerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = BikeDealer::select('*');
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = $this->getActions($row['id']);
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        } else {
            $formDetails = [
                'title' => 'Bike Dealer',
            ];
            return view('admin.dealers.index',$formDetails);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return response()->json([
            'status'     => true,
            'statusCode' => 200,
            'message'    => 'AjaxModal Loaded',
            'data'       => view('admin.dealers.ajaxModal',['action' => route('dealers.store'),'method' => 'POST'])->render()
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
        $postData = $request->all();
        $validator = Validator::make($postData, [
            'company_name' => 'required|string',
            'company_email' => 'required|string',
            'company_office_phone' => 'required|string',
            'company_address' => 'nullable|string',
            'company_gst_no' => 'nullable|string',
            'company_more_detail' => 'nullable|string',
            'contact_person' => 'nullable|string',
            'contact_person_email' => 'nullable|string',
            'contact_person_phone' => 'nullable|string',
            'contact_person_phone2' => 'nullable|string',
            'contact_person_address' => 'nullable|string',
            'contact_person_document_file' => 'file|max:10000',
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

        $createData = $request->only([
            'company_name',
            'company_email',
            'company_office_phone',
            'company_address',
            'company_gst_no',
            'company_more_detail',
            'contact_person',
            'contact_person_email',
            'contact_person_phone',
            'contact_person_phone2',
            'contact_person_address',
        ]);

        if(request()->hasFile('contact_person_document_file')){
            $file = request()->file('contact_person_document_file');
            $createData['contact_person_document_type'] = $file->getMimeType();
            $path = 'uploads/'. time() . '-' . $file->getClientOriginalName();
            // $path = $file->storeAs('uploads', $filename);
            $file = Storage::disk('public')->put($path, file_get_contents($file));
            $createData['contact_person_document_file'] = $path;
        }

        BikeDealer::create($createData);

        return response()->json([
            'status'     => true,
            'statusCode' => 200,
            'message'    => 'Created Successfully'
        ],200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $bikeDealer = BikeDealer::find($id);
        return view('admin.dealers.show', ['bikeBrand' => $bikeDealer]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $bikeDealer = BikeDealer::find($id);
        return response()->json([
            'status'     => true,
            'statusCode' => 200,
            'message'    => 'AjaxModal Loaded',
            'data'       => view('admin.dealers.ajaxModal',['data' => $bikeDealer,'action' => route('dealers.update',['dealer' => $id]),'method' => 'PUT'])->render()
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
        $postData = $request->all();
        $validator = Validator::make($postData, [
            'company_name' => 'required|string',
            'company_email' => 'required|string',
            'company_office_phone' => 'required|string',
            'company_address' => 'nullable|string',
            'company_gst_no' => 'nullable|string',
            'company_more_detail' => 'nullable|string',
            'contact_person' => 'nullable|string',
            'contact_person_email' => 'nullable|string',
            'contact_person_phone' => 'nullable|string',
            'contact_person_phone2' => 'nullable|string',
            'contact_person_address' => 'nullable|string',
            'contact_person_document_file' => 'file|max:10000',
        ]);
        if ($validator->fails()) {
            return response()->json(['status'=> false,'statusCode' => 419,'message' => $validator->errors()->first(),'errors' => $validator->errors()]);
        }
        $branch = BikeDealer::find($id);
        if(!$branch){
            return response()->json(['status'=> false,'statusCode' => 419,'message' => 'Brand Not Found']);
        }
        $createData = $request->only([
            'company_name',
            'company_email',
            'company_office_phone',
            'company_address',
            'company_gst_no',
            'company_more_detail',
            'contact_person',
            'contact_person_email',
            'contact_person_phone',
            'contact_person_phone2',
            'contact_person_address',
        ]);
        $branch->update($createData);
        return response()->json(['status'=> true,'statusCode' => 200,'message'=> 'Updated Successfully',],200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $bikeDealer = BikeDealer::find($id);
        if(!$bikeDealer){
            return response()->json(['status'=> false,'statusCode' => 419,'message' => 'Brand Not Found']);
        }
        $bikeDealer->delete();
        return response()->json(['status'=> true,'statusCode' => 200,'message'=> 'Deleted Successfully',],200);

    }

    public function getActions($id)
    {
        return '<div class="action-btn-container">'.
            '<a href="'. route('dealers.edit',['dealer' => $id]). '" class="btn btn-sm btn-warning ajaxModalPopup" data-modal_title="Update Dealer"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>'.
            '<a href="'. route('dealers.destroy',['dealer' => $id]) .'" class="btn btn-sm btn-danger ajaxModalDelete"  data-id="'.$id.'" data-redirect="'.route('dealers.index').'"><i class="fa fa-trash-o" aria-hidden="true"> </i></a>'.
            '</div>';
    }
}
