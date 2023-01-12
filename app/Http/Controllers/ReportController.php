<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.reports.index', ['data' => []]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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

    public function loadReportSection(Request $request)
    {
        $postData = $request->all();

        $type = isset($postData['type']) ? $postData['type'] : 'purchase';
        $view = 'purchase';
        $data = array();
        switch ($type) {
            case 'purchase':
                $view = 'purchase';
                break;
            case 'sales':
                $view = 'sales';
                break;
            case 'quotations':
                $view = 'quotations';
                break;
            case 'dues':
                $view = 'dues';
                break;
            case 'rto':
                $view = 'rto';
                break;
            default:
                $view = 'purchase';
                break;
        }

        return response()->json([
            'status'     => true,
            'statusCode' => 200,
            'message'    => ucfirst($view) . " report data loaded.",
            'data'       => view('admin.reports.ajax.' . $view, $data)->render()
        ]);
    }
}
