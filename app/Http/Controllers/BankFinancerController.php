<?php

namespace App\Http\Controllers;

use App\Models\BankFinancer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class BankFinancerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = BankFinancer::select('*');
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('active_status', function ($row) {
                    if ($row->active_status == '1') {
                        return "<label class='switch'><input type='checkbox' value='$row->id' data-type='bankFinancer' class='active_status' checked><span class='slider round'></span></label>";
                    } else {
                        return "<label class='switch'><input type='checkbox' value='$row->id' data-type='bankFinancer' class='active_status'><span class='slider round'></span></label>";
                    }
                })
                ->addColumn('action', function ($row) {
                    $btn = $this->getActions($row['id']);
                    return $btn;
                })
                ->rawColumns(['action', 'active_status'])
                ->make(true);
        } else {
            $formDetails = [
                'title' => 'Bank Financer',
            ];
            return view('admin.bankFinancers.index', $formDetails);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        try {
            return response()->json([
                'status'     => true,
                'statusCode' => 200,
                'message'    => trans('messages.ajax_model_loaded'),
                'data'       => view('admin.bankFinancers.ajaxModal', ['action' => route('bankFinancers.store'), 'method' => 'POST'])->render()
            ]);
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
            $validateArray = [
                'bank_name' => 'required|unique:bank_financers,bank_name',
                'bank_branch_code' => 'nullable',
                'bank_contact_number' => 'nullable|digits:10',
                'bank_email_address' => 'nullable|email',
                'bank_full_address' => 'nullable',
                'bank_manager_name' => 'nullable',
                'bank_manager_contact' => 'nullable|digits:10',
                'bank_manager_email' => 'nullable|email',
                'bank_financer_name' => 'nullable',
                'bank_financer_contact' => 'nullable|digits:10',
                'bank_financer_email' => 'nullable|email',
                'bank_financer_address' => 'nullable',
                'bank_financer_aadhar_card' => 'nullable|min:12|max:12',
                'bank_financer_pan_card' => 'nullable',
                'financer_type' => 'required|in:0,1,2',
                'more_details' => 'nullable',
                'active_status' => 'required|in:0,1'
            ];
            $postData = $request->all();
            $validator = Validator::make($postData, $validateArray);

            //If Validation failed
            if ($validator->fails()) {
                return response()->json([
                    'status'     => false,
                    'statusCode' => 419,
                    'message'    => $validator->errors()->first(),
                    'errors'     => $validator->errors(),
                ]);
            }

            BankFinancer::create($request->only(['bank_name', 'bank_branch_code', 'bank_contact_number', 'bank_email_address', 'bank_full_address', 'bank_manager_name', 'bank_manager_contact', 'bank_manager_email', 'bank_financer_name', 'bank_financer_contact', 'bank_financer_email', 'bank_financer_address', 'bank_financer_aadhar_card', 'bank_financer_pan_card', 'more_details', 'financer_type', 'active_status']));
            DB::commit();
            return response()->json([
                'status'     => true,
                'statusCode' => 200,
                'message'    => trans('messages.create_success'),
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
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
        $bankFinancer = BankFinancer::find($id);
        return view('admin.bankFinancers.show', ['bankFinance' => $bankFinancer]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try {
            $bankFinancer = BankFinancer::find($id);
            return response()->json([
                'status'     => true,
                'statusCode' => 200,
                'message'    => trans('messages.ajax_model_loaded'),
                'data'       => view('admin.bankFinancers.ajaxModal', ['data' => $bankFinancer, 'action' => route('bankFinancers.update', ['bankFinancer' => $id]), 'method' => 'PUT'])->render()
            ]);
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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            DB::beginTransaction();
            $postData = $request->all();
            $validateArray = [
                'bank_name' => 'required|unique:bank_financers,bank_name,' . $id . ',id',
                'bank_branch_code' => 'nullable',
                'bank_contact_number' => 'nullable|digits:10',
                'bank_email_address' => 'nullable|email',
                'bank_full_address' => 'nullable',
                'bank_manager_name' => 'nullable',
                'bank_manager_contact' => 'nullable|digits:10',
                'bank_manager_email' => 'nullable|email',
                'bank_financer_name' => 'nullable',
                'bank_financer_contact' => 'required|digits:10',
                'bank_financer_email' => 'nullable|email',
                'bank_financer_address' => 'nullable',
                'bank_financer_aadhar_card' => 'nullable|min:12|max:12',
                'bank_financer_pan_card' => 'nullable',
                'more_details' => 'nullable',
                'active_status' => 'required|in:0,1',
                'financer_type' => 'required|in:0,1',
            ];
            $validator = Validator::make($postData, $validateArray);
            if ($validator->fails()) {
                return response()->json(['status' => false, 'statusCode' => 419, 'message' => $validator->errors()->first(), 'errors' => $validator->errors()]);
            }
            $bankFinancer = BankFinancer::find($id);
            if (!$bankFinancer) {
                return response()->json(['status' => false, 'statusCode' => 419, 'message' => trans('messages.bank_financer_not_found')]);
            }
            $bankFinancer->update($request->only(['bank_name', 'bank_branch_code', 'bank_contact_number', 'bank_email_address', 'bank_full_address', 'bank_manager_name', 'bank_manager_contact', 'bank_manager_email', 'bank_financer_name', 'bank_financer_contact', 'bank_financer_email', 'bank_financer_address', 'bank_financer_aadhar_card', 'bank_financer_pan_card', 'more_details', 'active_status', 'financer_type']));
            DB::commit();
            return response()->json(['status' => true, 'statusCode' => 200, 'message' => trans('messages.update_success'),], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status'     => false,
                'statusCode' => 419,
                'message'    => $e->getMessage(),
                'data'       => ['file' => $e->getFile(), 'line' => $e->getLine()]
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            $bankFinancer = BankFinancer::find($id);
            if (!$bankFinancer) {
                return response()->json(['status' => false, 'statusCode' => 419, 'message' => trans('messages.brand_not_found')]);
            }
            $bankFinancer->delete();
            DB::commit();
            return response()->json(['status' => true, 'statusCode' => 200, 'message' => trans('messages.delete_success'),], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status'     => false,
                'statusCode' => 419,
                'message'    => $e->getMessage(),
                'data'       => ['file' => $e->getFile(), 'line' => $e->getLine()]
            ]);
        }
    }

    public function getActions($id)
    {
        $action = '<div class="action-btn-container">';
        $action .= '<a href="' . route('bankFinancers.edit', ['bankFinancer' => $id]) . '" class="btn btn-sm btn-primary ajaxModalPopup" data-modal_title="UPDATE BANK FINANCER" data-modal_size="modal-lg"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>';
        //$action .= '<a href="' . route('bankFinancers.destroy', ['bankFinancer' => $id]) . '" class="btn btn-sm btn-danger ajaxModalDelete"  data-id="' . $id . '" data-redirect="' . route('bankFinancers.index') . '"><i class="fa fa-trash-o" aria-hidden="true"> </i></a>';
        $action .= '</div>';
        return $action;
    }
}
