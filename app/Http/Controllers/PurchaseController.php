<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class PurchaseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Purchase::select('*');
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
                'title' => 'Purchase',
            ];
            return view('admin.purchases.index',$formDetails);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.purchases.create',['action' => route('purchases.store')]);
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
            'addressed' => 'nullable|string',
            'first_name' => 'nullable|string',
            'last_name' => 'nullable|string',
            'email' => 'nullable||email|string',
            'phone' => 'nullable|string',
            'gst_no' => 'nullable|string',
            'booking_type' => 'nullable|string',
            'state' => 'nullable|string',
            'district' => 'nullable|string',
            'city' => 'nullable|string',
            'address_line1' => 'nullable|string',
            'address_line2' => 'nullable|string',
            'pin_code' => 'nullable|string',
            'age' => 'nullable|numeric',
            'gender' => 'nullable|string|in:male,female',
            'occupation' => 'nullable|string',
            'model_in_inters' => 'nullable|string',
            'varient' => 'nullable|string',
            'color_code' => 'nullable|string',
            'quantity' => 'nullable|numeric',
            'existing_customer' => 'nullable|in:0,1',
            'exchange_enquiry' => 'nullable|in:0,1',
            'finance_requirement' => 'nullable|in:0,1',
            'loyalty_customer' => 'nullable|in:0,1',
            'enquiry_date' => 'nullable|date_format:Y-m-d',
            'expected_date_of_purchase' => 'nullable|date_format:Y-m-d',
            'next_follow_date' => 'nullable|date_format:Y-m-d',
            'dse_name' => 'nullable|string',
            'order_number' => 'nullable|string',
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

        $createData = $request->only(['addressed','first_name','last_name','email','phone','gst_no','booking_type','state','district','city','address_line1','address_line2','pin_code','age','gender','occupation','model_in_inters','varient','color_code','quantity','existing_customer','exchange_enquiry','finance_requirement','loyalty_customer','enquiry_date','expected_date_of_purchase','next_follow_date','dse_name','order_number']);

        $purchase = Purchase::create($createData);

        return response()->json([
            'status'     => true,
            'statusCode' => 200,
            'message'    => 'Created Successfully',
            'data' => $purchase
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
        $purchase = Purchase::find($id);
        return view('admin.purchases.show', ['data' => $purchase]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $purchase = Purchase::find($id);
        return view('admin.purchases.create', [
            'action' => route('purchases.update',['purchase' => $id]),
            'data' => $purchase,
            'method' => 'PUT'
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
            'addressed' => 'nullable|string',
            'first_name' => 'nullable|string',
            'last_name' => 'nullable|string',
            'email' => 'nullable||email|string',
            'phone' => 'nullable|string',
            'gst_no' => 'nullable|string',
            'booking_type' => 'nullable|string',
            'state' => 'nullable|string',
            'district' => 'nullable|string',
            'city' => 'nullable|string',
            'address_line1' => 'nullable|string',
            'address_line2' => 'nullable|string',
            'pin_code' => 'nullable|string',
            'age' => 'nullable|numeric',
            'gender' => 'nullable|string|in:male,female',
            'occupation' => 'nullable|string',
            'model_in_inters' => 'nullable|string',
            'varient' => 'nullable|string',
            'color_code' => 'nullable|string',
            'quantity' => 'nullable|numeric',
            'existing_customer' => 'nullable|in:0,1',
            'exchange_enquiry' => 'nullable|in:0,1',
            'finance_requirement' => 'nullable|in:0,1',
            'loyalty_customer' => 'nullable|in:0,1',
            'enquiry_date' => 'nullable|date_format:Y-m-d',
            'expected_date_of_purchase' => 'nullable|date_format:Y-m-d',
            'next_follow_date' => 'nullable|date_format:Y-m-d',
            'dse_name' => 'nullable|string',
            'order_number' => 'nullable|string',
        ]);
        if ($validator->fails()) {
            return response()->json(['status'=> false,'statusCode' => 419,'message' => $validator->errors()->first(),'errors' => $validator->errors()]);
        }
        $purchase = Purchase::find($id);
        if(!$purchase){
            return response()->json(['status'=> false,'statusCode' => 419,'message' => 'Brand Not Found']);
        }
        $createData = $request->only(['addressed','first_name','last_name','email','phone','gst_no','booking_type','state','district','city','address_line1','address_line2','pin_code','age','gender','occupation','model_in_inters','varient','color_code','quantity','existing_customer','exchange_enquiry','finance_requirement','loyalty_customer','enquiry_date','expected_date_of_purchase','next_follow_date','dse_name','order_number']);
        $purchase->update($createData);

        return response()->json([
            'status'=> true,
            'statusCode' => 200,
            'message'=> 'Updated Successfully',
            'data' => $purchase
        ],200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $purchase = Purchase::find($id);
        if(!$purchase){
            return response()->json(['status'=> false,'statusCode' => 419,'message' => 'Brand Not Found']);
        }
        $purchase->delete();
        return response()->json(['status'=> true,'statusCode' => 200,'message'=> 'Deleted Successfully',],200);

    }

    public function getActions($id)
    {
        return '<div class="action-btn-container">'.
            '<a href="'. route('purchases.edit',['purchase' => $id]). '" class="btn btn-sm btn-warning ajaxModalPopup" data-modal_title="Update Purchase"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>'.
            '<a href="'. route('purchases.destroy',['purchase' => $id]) .'" class="btn btn-sm btn-danger ajaxModalDelete"  data-id="'.$id.'" data-redirect="'.route('purchases.index').'"><i class="fa fa-trash-o" aria-hidden="true"> </i></a>'.
            '</div>';
    }
}
