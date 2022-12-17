<?php

namespace App\Http\Controllers;

use App\Models\BikeBrand;
use Illuminate\Http\Request;
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
        return view('admin.bikeCategory.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
        //
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
        //
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
        return '<div class="action-btn-container">
            <a href="'. route('brands.show',['brand' => $id]) .'" class="btn btn-sm btn-success"><i class="fa fa-eye" aria-hidden="true"></i></a>' .
            '<a href="'. route('brands.edit',['brand' => $id]) .'" class="btn btn-sm btn-warning"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>' .
            '<a href="'. route('brands.destroy',['brand' => $id]) .'" class="btn btn-sm btn-danger"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
                </div>';
    }
}
