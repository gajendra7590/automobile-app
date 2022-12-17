<?php

namespace App\Http\Controllers;

use App\Models\BikeBrand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class BikeBrandsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = BikeBrand::select('*');
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = $this->getActions($row['id']);
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        } else {
            return view('admin.bikeBrands.index');
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
            'data'       => view('admin.bikeBrands.ajaxModal',['action' => route('brands.store'),'method' => 'POST'])->render()
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
            'name' => "required|unique:bike_brands,name"
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

        BikeBrand::create($request->all());

        return response()->json([
            'status'     => true,
            'statusCode' => 200,
            'message'    => 'Created Successfully',
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
        $bikeBrand = BikeBrand::find($id);
        return view('admin.bikeBrands.show', ['bikeBrand' => $bikeBrand]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $bikeBrand = BikeBrand::find($id);
        return response()->json([
            'status'     => true,
            'statusCode' => 200,
            'message'    => 'AjaxModal Loaded',
            'data'       => view('admin.bikeBrands.ajaxModal',['bikeBrand' => $bikeBrand,'action' => route('brands.update',['brand' => $id]),'method' => 'PUT'])->render()
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
            'name' => "required|unique:bike_brands,name,".$id
        ]);
        if ($validator->fails()) {
            return response()->json(['status'=> false,'statusCode' => 419,'message' => $validator->errors()->first(),'errors' => $validator->errors()]);
        }
        $bikeBrand = BikeBrand::find($id);
        if(!$bikeBrand){
            return response()->json(['status'=> false,'statusCode' => 419,'message' => 'Brand Not Found']);
        }
        $bikeBrand->update($request->all());
        return response()->json(['status'=> true,'statusCode' => 200,'message'=> 'Created Successfully',],200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $bikeBrand = BikeBrand::find($id);
        if(!$bikeBrand){
            return response()->json(['status'=> false,'statusCode' => 419,'message' => 'Brand Not Found']);
        }
        $bikeBrand->delete();
        return response()->json(['status'=> true,'statusCode' => 200,'message'=> 'Deleted Successfully',],200);

    }

    public function getActions($id)
    {
        return '<div class="action-btn-container">
            <a href="'. route('brands.show',['brand' => $id]) .'" class="btn btn-sm btn-success"><i class="fa fa-eye" aria-hidden="true"></i></a>' .
            '<a href="'. route('brands.edit',['brand' => $id]). '" class="btn btn-sm btn-warning ajaxModalPopup" data-modal_title="Update Brand"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>'.
            '<a href="'. route('brands.destroy',['brand' => $id]) .'" class="btn btn-sm btn-danger deleteRow"  data-id="'.$id.'" data-redirect="'.route('brands.index').'"><i class="fa fa-trash-o" aria-hidden="true"> </i></a>
            </div>';
    }
}
