<?php

namespace App\Http\Controllers;

use App\Models\BankAccounts;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

use App\Traits\CommonHelper;

class PaidFromBankAccountController extends Controller
{
    use CommonHelper;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = BankAccounts::select('*')->with([
                'branch' => function ($branch) {
                    $branch->select('id', 'branch_name');
                }
            ]);
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('active_status', function ($row) {
                    if ($row->active_status == '1') {
                        return "<label class='switch'><input type='checkbox' value='$row->id' data-type='branch' class='active_status' checked><span class='slider round'></span></label>";
                    } else {
                        return "<label class='switch'><input type='checkbox' value='$row->id' data-type='branch' class='active_status'><span class='slider round'></span></label>";
                    }
                })
                ->addColumn('branch_name', function ($row) {
                    return (isset($row->branch)) ? $row->branch->branch_name : "--";
                })
                ->addColumn('action', function ($row) {
                    $btn = $this->getActions($row['id']);
                    return $btn;
                })
                ->rawColumns(['action', 'active_status'])
                ->make(true);
        } else {
            return view('admin.paidFromBankAccounts.index');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $branches = self::_getBranches();
        return response()->json([
            'status'     => true,
            'statusCode' => 200,
            'message'    => trans('messages.ajax_model_loaded'),
            'data'       => view('admin.paidFromBankAccounts.ajaxModal', [
                'action' => route('paidFromBankAccounts.store'),
                'method' => 'POST',
                'branches' => $branches
            ])->render()
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
            DB::beginTransaction();;
            $postData = $request->all();
            $validator = Validator::make($postData, [
                'branch_id'                => 'required|exists:bank_accounts,id',
                'bank_name'                => 'required',
                'bank_account_number'      => 'required',
                'bank_account_holder_name' => 'required',
                //'bank_ifsc_code'           => 'required',
                'bank_branch_name'         => 'required'
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
            $createData = $request->only(['branch_id', 'bank_name', 'bank_account_number', 'bank_account_holder_name', 'bank_ifsc_code', 'bank_branch_name']);
            BankAccounts::create($createData);
            DB::commit();
            return response()->json([
                'status'     => true,
                'statusCode' => 200,
                'message'    => trans('messages.create_success')
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
        $bankAccount = BankAccounts::find($id);
        return view('admin.paidFromBankAccounts.show', ['data' => $bankAccount]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $bankAccount = BankAccounts::find($id);
        $branches = (!empty($bankAccount->branch_id)) ? self::_getBranchById($bankAccount->branch_id) : self::_getBranches();
        $data = array(
            'data' => $bankAccount,
            'action' => route('paidFromBankAccounts.update', ['paidFromBankAccount' => $id]),
            'method' => 'PUT',
            'branches' => $branches
        );
        return response()->json([
            'status'     => true,
            'statusCode' => 200,
            'message'    => trans('messages.ajax_model_loaded'),
            'data'       => view('admin.paidFromBankAccounts.ajaxModal', $data)->render()
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
        try {
            DB::beginTransaction();
            $postData = $request->all();
            $validator = Validator::make($postData, [
                'branch_id'                => 'required|exists:bank_accounts,id',
                'bank_name'                => 'required',
                'bank_account_number'      => 'required',
                'bank_account_holder_name' => 'required',
                //'bank_ifsc_code'           => 'required',
                'bank_branch_name'         => 'required'
            ]);
            if ($validator->fails()) {
                return response()->json(['status' => false, 'statusCode' => 419, 'message' => $validator->errors()->first(), 'errors' => $validator->errors()]);
            }
            $branch = BankAccounts::find($id);
            if (!$branch) {
                return response()->json(['status' => false, 'statusCode' => 419, 'message' => trans('messages.brand_not_found')]);
            }
            $updateData = $request->only(['branch_id', 'bank_name', 'bank_account_number', 'bank_account_holder_name', 'bank_ifsc_code', 'bank_branch_name']);
            $branch->update($updateData);
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
    }

    public function getActions($id)
    {
        $action = '<div class="action-btn-container">';
        $action .= '<a href="' . route('paidFromBankAccounts.edit', ['paidFromBankAccount' => $id]) . '" class="btn btn-sm btn-primary ajaxModalPopup" data-modal_title="UPDATE BANK ACCOUNT DETAIL" data-modal_size="modal-lg"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>';
        $action .= '</div>';
        return $action;
    }
}
