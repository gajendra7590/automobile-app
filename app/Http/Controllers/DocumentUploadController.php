<?php

namespace App\Http\Controllers;

use App\Models\DocumentSectionTypes;
use App\Models\DocumentUploads;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class DocumentUploadController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!request()->ajax()) {
            return view('admin.documentUploads.index');
        } else {
            $data = DocumentUploads::with(['sectionType'])->select('*');
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('section_type', function ($row) {
                    return isset($row->sectionType->name) ? $row->sectionType->name : '--';
                })
                ->addColumn('created_at', function ($row) {
                    return isset($row->created_at) ? date('Y-m-d', strtotime($row->created_at)) : '--';
                })
                ->addColumn('action', function ($row) {
                    return $this->getActions($row);
                })
                ->rawColumns(['section_type', 'created_at', 'action'])
                ->make(true);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $documentTypes = DocumentSectionTypes::all();
        $data = array(
            'method' => 'POST',
            'action' => route('documentUploads.store'),
            'documentTypes' => $documentTypes
        );
        return view('admin.documentUploads.create', $data);
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

    public function getActions($row)
    {
        $action = '<div class="action-btn-container">';
        $action .= '<a href="' . route('documentUploads.edit', ['documentUpload' => $row->id]) . '" class="btn btn-sm btn-warning ajaxModalPopup" data-modal_title="Update GST Rate"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>';
        // $action .= '<a href="' . route('gst-rates.destroy', ['gst-rate' => $row->id]) . '" data-id="' . $row->id . '" class="btn btn-sm btn-danger ajaxModalDelete" data-modal_title="Delete State"><i class="fa fa-trash-o" aria-hidden="true"></i></a>';
        $action .= '</div>';
        return $action;
    }
}
