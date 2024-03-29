<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\City;
use App\Models\District;
use App\Models\State;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class BranchController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Branch::select('*');
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('active_status', function ($row) {
                    if ($row->active_status == '1') {
                        return "<label class='switch'><input type='checkbox' value='$row->id' data-type='branch' class='active_status' checked><span class='slider round'></span></label>";
                    } else {
                        return "<label class='switch'><input type='checkbox' value='$row->id' data-type='branch' class='active_status'><span class='slider round'></span></label>";
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
                'title' => 'Branch',
            ];
            return view('admin.branches.index', $formDetails);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $states = State::where('active_status', '1')->select('id', 'state_name')->get();
        return response()->json([
            'status'     => true,
            'statusCode' => 200,
            'message'    => trans('messages.ajax_model_loaded'),
            'data'       => view('admin.branches.ajaxModal', ['action' => route('branches.store'), 'method' => 'POST', 'states' => $states])->render()
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
                'branch_name' => 'required|string|unique:branches,branch_name,NULL,id',
                'branch_email' => 'required|email',
                'branch_phone' => 'required|numeric|digits:10',
                'branch_phone2' => 'nullable|numeric|digits:10',
                'branch_address_line' => 'nullable|string',
                'branch_city' => 'nullable|string',
                'branch_district' => 'nullable|string',
                'branch_state' => 'nullable|string',
                'branch_county' => 'nullable|string',
                'branch_pincode' => 'nullable|string',
                'gstin_number' => 'nullable|string',
                'branch_more_detail' => 'nullable|string',
                'account_number' => 'nullable|string',
                'ifsc_code' => 'nullable|string',
                'bank_name' => 'nullable|string',
                'bank_branch' => 'nullable|string',
                'active_status'      => 'required|in:0,1'
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
                'branch_name',
                'branch_email',
                'branch_phone',
                'branch_phone',
                'branch_address_line',
                'branch_city',
                'branch_district',
                'branch_state',
                'branch_county',
                'branch_pincode',
                'branch_more_detail',
                'gstin_number',
                'account_number',
                'ifsc_code',
                'bank_name',
                'bank_branch',
                'active_status'
            ]);

            Branch::create($createData);
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
        $branch = Branch::find($id);
        return view('admin.branches.show', ['bikeBrand' => $branch]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $branch = Branch::find($id);
        // dd($branch->toArray());
        $data = array(
            'data' => $branch,
            'action' => route('branches.update', ['branch' => $id]),
            'method' => 'PUT',
            'states' => State::where('active_status', '1')->select('id', 'state_name')->get(),
            'districts' => District::where('active_status', '1')->where('state_id', $branch->branch_state)->select('id', 'district_name')->get(),
            'cities' => City::where('active_status', '1')->where('district_id', $branch->branch_district)->select('id', 'city_name')->get(),
        );
        return response()->json([
            'status'     => true,
            'statusCode' => 200,
            'message'    => trans('messages.ajax_model_loaded'),
            'data'       => view('admin.branches.ajaxModal', $data)->render()
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
                'branch_name'           => 'required|string|unique:branches,branch_name,' . $id . ',id',
                'branch_email'          => 'required|email',
                'branch_phone'          => 'required|numeric|digits:10',
                'branch_phone2'         => 'nullable|numeric|digits:10',
                'branch_address_line'   => 'nullable|string',
                'branch_city'           => 'nullable|string',
                'branch_district'       => 'nullable|string',
                'branch_state'          => 'nullable|string',
                'branch_county'         => 'nullable|string',
                'branch_pincode'        => 'nullable|string',
                'gstin_number'          => 'nullable|string',
                'branch_more_detail'    => 'nullable|string',
                'account_number' => 'nullable|string',
                'ifsc_code' => 'nullable|string',
                'bank_name' => 'nullable|string',
                'bank_branch' => 'nullable|string',
                'active_status'         => 'required|in:0,1',
                'branch_logo_image'     => 'nullable|mimes:jpg,jpeg,png'
            ]);
            if ($validator->fails()) {
                return response()->json(['status' => false, 'statusCode' => 419, 'message' => $validator->errors()->first(), 'errors' => $validator->errors()]);
            }
            $branch = Branch::find($id);
            if (!$branch) {
                return response()->json(['status' => false, 'statusCode' => 419, 'message' => trans('messages.brand_not_found')]);
            }

            // dd($postData);
            $createData = $request->only([
                'branch_name',
                'branch_email',
                'branch_phone',
                'branch_phone',
                'branch_address_line',
                'branch_city',
                'branch_district',
                'branch_state',
                'branch_county',
                'branch_pincode',
                'branch_more_detail',
                'gstin_number',
                'account_number',
                'ifsc_code',
                'bank_name',
                'bank_branch',
                'active_status'
            ]);

            //UPLOAD LOGO
            if (request()->file('branch_logo_image')) {
                $imageName = strtolower(Str::slug($postData['branch_name'])) . '-' . date('YmdHis');
                $imageName .= '.' . $request->branch_logo_image->getClientOriginalExtension();
                $request->branch_logo_image->move(public_path('/uploads'), $imageName);
                $createData['branch_logo'] = 'uploads/' . $imageName;
            }

            $branch->update($createData);
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
            $branch = Branch::find($id);
            if (!$branch) {
                return response()->json(['status' => false, 'statusCode' => 419, 'message' => trans('messages.brand_not_found')]);
            }
            $branch->delete();
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
        //data-modal_size="modal-lg"
        $action = '<div class="action-btn-container">';
        $action .= '<a href="' . route('branches.edit', ['branch' => $id]) . '" class="btn btn-sm btn-primary ajaxModalPopup" data-modal_title="Update Branch" data-modal_size="modal-lg"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>';
        //$ .= '<a href="' . route('branches.destroy', ['branch' => $id]) . '" class="btn btn-sm btn-danger ajaxModalDelete"  data-id="' . $id . '" data-redirect="' . route('branches.index') . '"><i class="fa fa-trash-o" aria-hidden="true"> </i></a>';
        $action .= '</div>';
        return $action;
    }
}
