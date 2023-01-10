<?php

namespace App\Http\Controllers;

use App\Models\BikeAgent;
use App\Models\City;
use App\Models\District;
use App\Models\GstRtoRates;
use App\Models\RtoAgent;
use App\Models\RtoRegistration;
use App\Models\Sale;
use App\Models\State;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

//
use App\Traits\CommonHelper;
use Illuminate\Support\Facades\Validator;

class RtoRegistrationController extends Controller
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
            $data = RtoRegistration::with([
                'agent' => function ($agent) {
                    $agent->select('id', 'agent_name');
                },
                'sale' => function ($sale) {
                    $sale->select('id', 'sale_uuid', 'branch_id')->with([
                        'branch' => function ($branch) {
                            $branch->select('id', 'branch_name');
                        }
                    ]);
                }
            ]);
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('agentName', function ($row) {
                    return isset($row->agent->agent_name) ? $row->agent->agent_name : '---';
                })
                ->addColumn('branchName', function ($row) {
                    return isset($row->sale->branch->branch_name) ? $row->sale->branch->branch_name : '---';
                })
                ->addColumn('contact_city', function ($row) {
                    return $row->contact_city ? $row->contact_city->city_name : '---';
                })
                ->addColumn('rc_status', function ($row) {
                    if (isset($row->rc_status) && ($row->rc_status == '1')) {
                        return 'Yes';
                    } else if (isset($row->rc_status) && ($row->rc_status == '0')) {
                        return 'No';
                    }
                })
                ->addColumn('action', function ($row) {
                    $btn = $this->getActions($row['id']);
                    return $btn;
                })
                ->rawColumns(['agentName', 'branchName', 'action'])
                ->make(true);
        } else {
            $formDetails = [
                'title' => 'RTO Registration',
            ];
            return view('admin.rto-registration.index', $formDetails);
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
            'action' => route('rtoRegistration.store'),
            'method' => 'POST',
        ];
        $data['sales'] = Sale::select(['id', 'customer_name'])->where('rto_account_id', '0')->orWhereNull('rto_account_id')->get();
        $data['rto_agents'] = RtoAgent::select(['id', 'agent_name'])->get();
        // $data['gst_rto_rates'] = GstRtoRates::select(['id', 'gst_rate'])->get();
        // $data['states'] = State::select(['id', 'state_name'])->get();
        // $data['states'] = State::select(['id', 'state_name'])->get();
        // $data['districts'] = [];
        // $data['cities'] = [];
        // if (count($data['states'])) {
        //     $data['districts'] = District::select(['id', 'district_name'])->where('state_id', $data['states'][0]['id'])->get();
        // }
        // if (count($data['districts'])) {
        //     $data['cities'] = City::select(['id', 'city_name'])->where('district_id', $data['districts'][0]['id'])->get();
        // }
        return response()->json([
            'status'     => true,
            'statusCode' => 200,
            'message'    => trans('messages.ajax_model_loaded'),
            'data'       => view('admin.rto-registration.ajaxModal', $data)->render()
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
            $validator = Validator::make($postData, [
                'sale_id' => 'required|exists:sales,id',
                'rto_agent_id' => 'required|exists:rto_agents,id',
                'contact_name' => 'required|string',
                'contact_mobile_number' => 'required|numeric|min:10',
                'contact_address_line'  => 'required|string',
                'contact_state_id'     => "required|exists:u_states,id",
                'contact_district_id'  => "required|exists:u_districts,id",
                'contact_city_id'  => "required|exists:u_cities,id",
                'contact_zipcode' => "required|numeric|min:6",
                'financer_name' => "nullable|string",
                'sku'             => "required",
                'gst_rto_rate_id' => "required|exists:gst_rto_rates,id",
                'gst_rto_rate_percentage' => "required|numeric|min:1",
                'ex_showroom_amount' => "required|numeric|min:1",
                'tax_amount' => "required|numeric|min:1",
                'hyp_amount' => "required|numeric",
                'tr_amount' => "nullable|numeric",
                'fees' => "nullable|numeric",
                'total_amount' => "required|numeric",
                'payment_amount' => "nullable|numeric",
                'payment_date' => "nullable|date",
                'outstanding' => "nullable|numeric",
                'rc_number' => "nullable|string",
                'rc_status' => "required|in:0,1",
                'submit_date' => "nullable|date",
                'bike_number' => "nullable|string",
                'recieved_date' => "nullable|date",
                'customer_given_date' => "nullable|date",
                'remark' => "nullable|string"
            ], [
                'sale_id.required' => "The sales field is required.",
                'rto_agent_id.required' => "The RTO agent field is required.",
                'contact_state_id.required' => "The state field is required.",
                'contact_district_id.required' => "The district field is required.",
                'contact_city_id.required' => "The city field is required.",
                'gst_rto_rate_id.required' => "The RTO Gst Rate field is required."
            ]);

            //If Validation failed
            if ($validator->fails()) {
                return response()->json([
                    'status'     => false,
                    'statusCode' => 419,
                    'message'    => $validator->errors()->first(),
                    'errors'     => $validator->errors()
                ]);
            }
            DB::beginTransaction();
            $rtoRegistration = RtoRegistration::create($postData);
            if ($rtoRegistration) {
                Sale::where(['id' => $postData['sale_id']])->update(['rto_account_id' => $rtoRegistration->id]);
            }
            DB::commit();
            return response()->json([
                'status'     => true,
                'statusCode' => 200,
                'message'    => trans('messages.create_success'),
                'data'       => $rtoRegistration
            ]);
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
        try {
            $rtoModel = RtoRegistration::with([
                'agent' => function ($agent) {
                    $agent->select('id', 'agent_name');
                },
                'state' => function ($state) {
                    $state->select('id', 'state_name');
                },
                'district' => function ($district) {
                    $district->select('id', 'district_name');
                },
                'city' => function ($city) {
                    $city->select('id', 'city_name');
                },
                'tax' => function ($tax) {
                    $tax->select('id', 'gst_rate');
                },
                'sale' => function ($sale) {
                    $sale->select('id', 'sale_uuid', 'branch_id')->with([
                        'branch' => function ($branch) {
                            $branch->select('id', 'branch_name');
                        }
                    ]);
                }
            ])->where('id', $id)->first();
            if (!$rtoModel) {
                return response()->json([
                    'status'     => false,
                    'statusCode' => 419,
                    'message'    => "Opps! Invalid request."
                ]);
            }


            return response()->json([
                'status'     => true,
                'statusCode' => 200,
                'message'    => trans('messages.ajax_model_loaded'),
                'data'       => view('admin.rto-registration.show', ['data' => $rtoModel])->render()
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
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $rtoModel = RtoRegistration::find($id);
        $responsePayload  = [
            'action' => route('rtoRegistration.update', ['rtoRegistration' => $id]),
            'method' => 'PUT',
        ];
        $responsePayload['sales'] = Sale::select(['id', 'customer_name'])->where('id', $rtoModel->sale_id)->get();
        $responsePayload['rto_agents'] = RtoAgent::select(['id', 'agent_name'])->where('id', $rtoModel->rto_agent_id)->get();
        $htmlData = array(
            'states' => self::_getStates(1),
            'districts' => self::_getDistricts($rtoModel->contact_state_id),
            'cities' => self::_getCities($rtoModel->contact_district_id),
            'gst_rto_rates' => self::_getRtoGstRates(),
            'data' => $rtoModel,
            'action' => "edit"
        );
        //Get Raw Html
        $responsePayload['htmlData'] = (view('admin.rto-registration.ajax-change')->with($htmlData)->render());
        $responsePayload['data'] = $rtoModel;
        //Return Response
        return response()->json([
            'status'     => true,
            'statusCode' => 200,
            'message'    => trans('messages.ajax_model_loaded'),
            'data'       => view('admin.rto-registration.ajaxModal', $responsePayload)->render()
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
            $rtoModel = RtoRegistration::find($id);
            if (!$rtoModel) {
                return response()->json([
                    'status'     => false,
                    'statusCode' => 419,
                    'message'    => "Opps! Invalid request."
                ]);
            }
            $postData = $request->all();
            $validator = Validator::make($postData, [
                'sale_id' => 'nullable|exists:sales,id',
                'rto_agent_id' => 'nullable|exists:rto_agents,id',
                'contact_name' => 'required|string',
                'contact_mobile_number' => 'required|numeric|min:10',
                'contact_address_line'  => 'required|string',
                'contact_state_id'     => "nullable|exists:u_states,id",
                'contact_district_id'  => "nullable|exists:u_districts,id",
                'contact_city_id'  => "nullable|exists:u_cities,id",
                'contact_zipcode' => "required|numeric|min:6",
                'financer_name' => "nullable|string",
                'sku'             => "required",
                'gst_rto_rate_id' => "nullable|exists:gst_rto_rates,id",
                'gst_rto_rate_percentage' => "required|numeric|min:1",
                'ex_showroom_amount' => "required|numeric|min:1",
                'tax_amount' => "required|numeric|min:1",
                'hyp_amount' => "required|numeric",
                'tr_amount' => "nullable|numeric",
                'fees' => "nullable|numeric",
                'total_amount' => "required|numeric",
                'payment_amount' => "nullable|numeric",
                'payment_date' => "nullable|date",
                'outstanding' => "nullable|numeric",
                'rc_number' => "nullable|string",
                'rc_status' => "required|in:0,1",
                'submit_date' => "nullable|date",
                'bike_number' => "nullable|string",
                'recieved_date' => "nullable|date",
                'customer_given_date' => "nullable|date",
                'remark' => "nullable|string"
            ], [
                'sale_id.required' => "The sales field is required.",
                'rto_agent_id.required' => "The RTO agent field is required.",
                'contact_state_id.required' => "The state field is required.",
                'contact_district_id.required' => "The district field is required.",
                'contact_city_id.required' => "The city field is required.",
                'gst_rto_rate_id.required' => "The RTO Gst Rate field is required."
            ]);

            //If Validation failed
            if ($validator->fails()) {
                return response()->json([
                    'status'     => false,
                    'statusCode' => 419,
                    'message'    => $validator->errors()->first(),
                    'errors'     => $validator->errors()
                ]);
            }
            DB::beginTransaction();
            $rtoModel->update($postData);
            DB::commit();
            return response()->json([
                'status'     => true,
                'statusCode' => 200,
                'message'    => trans('messages.update_success'),
                'data'       => $rtoModel
            ]);
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
        //
    }

    public function ajaxChangeContent(Request $request)
    {
        $postData = $request->all();
        $salesModel = Sale::find($postData['id']);
        $data = array(
            'states' => self::_getStates(1),
            'districts' => self::_getDistricts($salesModel->customer_state),
            'cities' => self::_getCities($salesModel->customer_district),
            'gst_rto_rates' => self::_getRtoGstRates(),
            'data' => sales2RtoPayload($salesModel),
            'action' => "add"
        );
        return response()->json([
            'status'     => true,
            'statusCode' => 200,
            'message'    => 'Load Form',
            'data'       => (view('admin.rto-registration.ajax-change')->with($data)->render())
        ]);
    }

    public function getActions($id)
    {
        $action = '<div class="action-btn-container">';
        $action .= '<a href="' . route('rtoRegistration.edit', ['rtoRegistration' => $id]) . '" class="btn btn-sm btn-warning ajaxModalPopup" data-modal_title="Edit Registration Data" data-title="Edit" data-modal_size="modal-lg"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>';
        $action .= '<a href="' . route('rtoRegistration.show', ['rtoRegistration' => $id]) . '" class="btn btn-sm btn-success ajaxModalPopup"  data-modal_size="modal-lg" data-modal_title="Preview Registration Data" data-title="View"><i class="fa fa-eye" aria-hidden="true"> </i></a>';
        $action .= '</div>';
        return $action;
    }
}
