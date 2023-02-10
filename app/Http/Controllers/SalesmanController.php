<?php

namespace App\Http\Controllers;

use App\Models\Salesman;
use App\Models\City;
use App\Models\District;
use App\Models\State;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class SalesmanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Salesman::select('*');
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('active_status', function ($row) {
                    if ($row->active_status == '1') {
                        return "<label class='switch'><input type='checkbox' value='$row->id' data-type='salesman' class='active_status' checked><span class='slider round'></span></label>";
                    } else {
                        return "<label class='switch'><input type='checkbox' value='$row->id' data-type='salesman' class='active_status'><span class='slider round'></span></label>";
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
                'title' => 'Salesman',
            ];
            return view('admin.salesmans.index', $formDetails);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data  = [
            'action' => route('salesmans.store'),
            'method' => 'POST',
        ];
        $data['states'] = State::select(['id', 'state_name'])->get();
        $data['districts'] = [];
        $data['cities'] = [];
        if (count($data['states'])) {
            $data['districts'] = District::select(['id', 'district_name'])->where('state_id', $data['states'][0]['id'])->get();
        }
        if (count($data['districts'])) {
            $data['cities'] = City::select(['id', 'city_name'])->where('district_id', $data['districts'][0]['id'])->get();
        }
        return response()->json([
            'status'     => true,
            'statusCode' => 200,
            'message'    => trans('messages.ajax_model_loaded'),
            'data'       => view('admin.salesmans.ajaxModal', $data)->render()
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
                'name' => "required|string",
                'email' => "required|email",
                'mobile_number' => 'required|string|digits:10',
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

            Salesman::create($request->only([
                'name',
                'email',
                'mobile_number',
                'active_status'
            ]));

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
        $salesman = Salesman::find($id);
        return view('admin.salesmans.show', ['salesmans' => $salesman]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $salesman = Salesman::find($id);
        $data = ['data' => $salesman, 'action' => route('salesmans.update', ['salesman' => $id]), 'method' => 'PUT'];
        $salesman = Salesman::find($id);
        return response()->json([
            'status'     => true,
            'statusCode' => 200,
            'message'    => trans('messages.ajax_model_loaded'),
            'data'       => view('admin.salesmans.ajaxModal', $data)->render()
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
                'name' => "nullable|string",
                'email' => "nullable|email",
                'mobile_number' => 'nullable|string|digits:10',
                'active_status'      => 'required|in:0,1'
            ]);
            if ($validator->fails()) {
                return response()->json(['status' => false, 'statusCode' => 419, 'message' => $validator->errors()->first(), 'errors' => $validator->errors()]);
            }
            $salesman = Salesman::find($id);
            if (!$salesman) {
                return response()->json(['status' => false, 'statusCode' => 419, 'message' => trans('messages.brand_not_found')]);
            }
            $salesman->update($request->all());
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
            $salesman = Salesman::find($id);
            if (!$salesman) {
                return response()->json(['status' => false, 'statusCode' => 419, 'message' => trans('messages.brand_not_found')]);
            }
            $salesman->delete();
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
        $action .= '<a href="' . route('salesmans.edit', ['salesman' => $id]) . '" class="btn btn-sm btn-primary ajaxModalPopup" data-modal_title="UPDATE SALESMAN DETAIL" data-modal_size="modal-lg"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>';
        // $action .= '<a href="' . route('salesmans.destroy', ['salesman' => $id]) . '" class="btn btn-sm btn-danger ajaxModalDelete"  data-id="' . $id . '" data-redirect="' . route('salesmans.index') . '"><i class="fa fa-trash-o" aria-hidden="true"> </i></a>';
        $action .= '</div>';
        return $action;
    }
}
