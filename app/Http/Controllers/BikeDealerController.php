<?php

namespace App\Http\Controllers;

use App\Models\BikeDealer;
use App\Traits\CommonHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class BikeDealerController extends Controller
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
            $data = BikeDealer::with([
                'branch' => function ($branch) {
                    $branch->select('id', 'branch_name');
                }

            ])->select('*');
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('active_status', function ($row) {
                    if ($row->active_status == '1') {
                        return "<label class='switch'><input type='checkbox' value='$row->id' data-type='dealer' class='active_status' checked><span class='slider round'></span></label>";
                    } else {
                        return "<label class='switch'><input type='checkbox' value='$row->id' data-type='dealer' class='active_status'><span class='slider round'></span></label>";
                    }
                })
                ->addColumn('action', function ($row) {
                    $btn = $this->getActions($row['id']);
                    return $btn;
                })
                ->rawColumns(['active_status', 'action'])
                ->make(true);
        } else {
            $formDetails = [
                'title' => 'Bike Dealer',
            ];
            return view('admin.dealers.index', $formDetails);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data = array(
            'action'   => route('dealers.store'),
            'method'   => 'POST',
            'branches' => self::_getBranches()
        );
        return response()->json([
            'status'     => true,
            'statusCode' => 200,
            'message'    => trans('messages.ajax_model_loaded'),
            'data'       => view('admin.dealers.ajaxModal', $data)->render()
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
            DB::beginTransaction();
            $postData = $request->all();
            $validator = Validator::make($postData, [
                'branch_id'   => 'required|exists:branches,id',
                'dealer_code' => 'required|string',
                'company_name' => 'required|string',
                'company_email' => 'required|email',
                'company_office_phone' => 'nullable|numeric|digits:10',
                'company_address' => 'nullable|string',
                'company_gst_no' => 'nullable|string',
                'company_more_detail' => 'nullable|string',
                'contact_person' => 'nullable|string',
                'contact_person_email' => 'nullable|string',
                'contact_person_phone' => 'nullable|numeric|digits:10',
                'contact_person_phone2' => 'nullable|numeric|digits:10',
                'contact_person_address' => 'nullable|string',
                'contact_person_document_file' => 'file|max:10000',
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
                'branch_id',
                'dealer_code',
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
                'active_status'
            ]);

            if (request()->hasFile('contact_person_document_file')) {
                $file = request()->file('contact_person_document_file');
                $createData['contact_person_document_type'] = $file->getMimeType();
                $path = 'uploads/' . time() . '-' . $file->getClientOriginalName();
                // $path = $file->storeAs('uploads', $filename);
                $file = Storage::disk('public')->put($path, file_get_contents($file));
                $createData['contact_person_document_file'] = $path;
            }

            BikeDealer::create($createData);
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
        $data = array(
            'action' => route('dealers.update', ['dealer' => $id]),
            'method'   => 'PUT',
            'branches' => (intval($bikeDealer->branch_id) > 0) ? self::_getBranchById($bikeDealer->branch_id) : self::_getBranches(),
            'data' => $bikeDealer
        );
        return response()->json([
            'status'     => true,
            'statusCode' => 200,
            'message'    => trans('messages.ajax_model_loaded'),
            'data'       => view('admin.dealers.ajaxModal', $data)->render()
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
                'branch_id'   => 'nullable|exists:branches,id',
                'dealer_code' => 'required',
                'company_name' => 'required',
                'company_email' => 'required|email',
                'company_office_phone' => 'nullable|numeric|digits:10',
                'company_address' => 'nullable|string',
                'company_gst_no' => 'nullable|string',
                'company_more_detail' => 'nullable|string',
                'contact_person' => 'nullable|string',
                'contact_person_email' => 'nullable|string',
                'contact_person_phone' => 'nullable|numeric|digits:10',
                'contact_person_phone2' => 'nullable|numeric|digits:10',
                'contact_person_address' => 'nullable|string',
                'contact_person_document_file' => 'file|max:10000',
                'active_status'      => 'required|in:0,1'
            ]);
            if ($validator->fails()) {
                return response()->json(['status' => false, 'statusCode' => 419, 'message' => $validator->errors()->first(), 'errors' => $validator->errors()]);
            }
            $branch = BikeDealer::find($id);
            if (!$branch) {
                return response()->json(['status' => false, 'statusCode' => 419, 'message' => trans('messages.brand_not_found')]);
            }
            $createData = $request->only([
                'branch_id',
                'dealer_code',
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
                'active_status'
            ]);
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
            $bikeDealer = BikeDealer::find($id);
            if (!$bikeDealer) {
                return response()->json(['status' => false, 'statusCode' => 419, 'message' => trans('messages.brand_not_found')]);
            }
            $bikeDealer->delete();
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
        $action  = '<div class="action-btn-container">';
        $action .= '<a href="' . route('dealers.edit', ['dealer' => $id]) . '" class="btn btn-sm btn-primary ajaxModalPopup" data-modal_size="modal-lg" data-modal_title="UPDATE DEALER DETAIL"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>';
        //$action .= '<a href="' . route('dealers.destroy', ['dealer' => $id]) . '" class="btn btn-sm btn-danger ajaxModalDelete"  data-id="' . $id . '" data-redirect="' . route('dealers.index') . '"><i class="fa fa-trash-o" aria-hidden="true"> </i></a>';
        $action .= '</div>';
        return $action;
    }
}
