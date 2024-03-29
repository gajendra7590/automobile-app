<?php

namespace App\Http\Controllers;

use App\Models\BikeColor;
use App\Models\SkuSalePrice;
use App\Traits\CommonHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class SkuSalePriceController extends Controller
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
            $data = SkuSalePrice::select('*');
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('active_status', function ($row) {
                    if ($row->active_status == '1') {
                        return "<label class='switch'><input type='checkbox' value='$row->id' data-type='skuSalesPrice' class='active_status' checked><span class='slider round'></span></label>";
                    } else {
                        return "<label class='switch'><input type='checkbox' value='$row->id' data-type='skuSalesPrice' class='active_status'><span class='slider round'></span></label>";
                    }
                })
                ->addColumn('action', function ($row) {
                    $btn = $this->getActions($row['id']);
                    return $btn;
                })
                ->rawColumns(['active_status', 'action'])
                ->make(true);
        } else {
            return view('admin.skuSalePrices.index');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $skuCodes = BikeColor::with([
            'model:id,model_name',
            'variant:id,variant_name'
        ])
            ->select('id', 'sku_code', 'sku_sale_price_id', 'bike_model', 'model_variant_id')
            ->where('sku_sale_price_id', '0')
            ->where(function ($model) {
                $model->where('sku_code', '!=', "")
                    ->orWhereNotNull('sku_code');
            })
            ->get();
        // dd($skuCodes->toArray());
        $data = array(
            'action'    => route('skuSalesPrice.store'),
            'method'    => 'POST',
            'sku_codes' => $skuCodes
        );
        return response()->json([
            'status'     => true,
            'statusCode' => 200,
            'message'    => trans('messages.ajax_model_loaded'),
            'data'       => view('admin.skuSalePrices.ajaxModal', $data)->render()
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
            $postData = $request->only('model_color_id', 'ex_showroom_price', 'registration_amount', 'insurance_amount', 'hypothecation_amount', 'accessories_amount', 'other_charges', 'total_amount', 'active_status');
            $validator = Validator::make($postData, [
                'model_color_id'       => "required|unique:sku_sale_prices,model_color_id",
                'ex_showroom_price'    => "required|numeric|min:1",
                'registration_amount'  => "required|numeric|min:1",
                'insurance_amount'     => "required|numeric|min:1",
                'hypothecation_amount' => "required|numeric|min:1",
                'accessories_amount'   => "required|numeric|min:1",
                'other_charges'        => "nullable|numeric|min:1",
                'total_amount'         => "required|numeric|min:1",
                'active_status'        => 'required|in:0,1'
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

            $postData['sku_code'] = BikeColor::where('id', $postData['model_color_id'])->value('sku_code');
            SkuSalePrice::create($postData);
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
        $model = SkuSalePrice::find($id);
        $sku_codes = (object) [];
        if (!empty($model->model_color_id)) {
            $sku_codes = BikeColor::select('id', 'sku_code', 'sku_sale_price_id', 'bike_model', 'model_variant_id')
                ->with([
                    'model:id,model_name',
                    'variant:id,variant_name'
                ])->where('id', $model->model_color_id)->get();
        } else {
            $sku_codes = BikeColor::select('id', 'sku_code', 'sku_sale_price_id', 'bike_model', 'model_variant_id')
                ->with([
                    'model:id,model_name',
                    'variant:id,variant_name'
                ])
                ->where('sku_sale_price_id', '0')
                ->where(function ($model) {
                    $model->where('sku_code', '!=', "")
                        ->orWhereNotNull('sku_code');
                })->get();
        }
        $data = array(
            'data' => $model,
            'action' => route('skuSalesPrice.update', ['skuSalesPrice' => $id]),
            'method' => 'PUT',
            'sku_codes' => $sku_codes
        );
        return response()->json([
            'status'     => true,
            'statusCode' => 200,
            'message'    => trans('messages.ajax_model_loaded'),
            'data'       => view('admin.skuSalePrices.ajaxModal', $data)->render()
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
            $postData = $request->only('model_color_id', 'ex_showroom_price', 'registration_amount', 'insurance_amount', 'hypothecation_amount', 'accessories_amount', 'other_charges', 'total_amount', 'active_status');
            $validator = Validator::make($postData, [
                'model_color_id'       => "required|unique:sku_sale_prices,model_color_id," . $id,
                'ex_showroom_price'    => "required|numeric|min:1",
                'registration_amount'  => "required|numeric|min:1",
                'insurance_amount'     => "required|numeric|min:1",
                'hypothecation_amount' => "required|numeric|min:1",
                'accessories_amount'   => "required|numeric|min:1",
                'other_charges'        => "nullable|numeric|min:1",
                'total_amount'         => "required|numeric|min:1",
                'active_status'        => 'required|in:0,1'
            ]);
            if ($validator->fails()) {
                DB::rollBack();
                return response()->json([
                    'status' => false, 'statusCode' => 419,
                    'message' => $validator->errors()->first(),
                    'errors' => $validator->errors()
                ]);
            }
            $model = SkuSalePrice::find($id);
            if (!$model) {
                DB::rollBack();
                return response()->json([
                    'status' => false,
                    'statusCode' => 419,
                    'message' => trans('messages.brand_not_found')
                ]);
            }
            $postData['sku_code'] = BikeColor::where('id', $postData['model_color_id'])->value('sku_code');
            $model->update($postData);
            DB::commit();
            return response()->json([
                'status' => true,
                'statusCode' => 200,
                'message' => trans('messages.update_success'),
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
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function getActions($id)
    {
        $action = '<div class="action-btn-container">';
        $action .= '<a href="' . route('skuSalesPrice.edit', ['skuSalesPrice' => $id]) . '" data-modal_size="modal-lg" class="btn btn-sm btn-primary ajaxModalPopup" data-modal_title="UPDATE SKU SALES PRICE"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>';
        $action .= '</div>';
        return $action;
    }
}
