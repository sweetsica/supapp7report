<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use App\Models\ReportUpload;


class ReportUploadController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        $file = $request->file('files');
        $name_file = $file->getClientOriginalName();
        $date = Carbon::today()->format('d-m-Y');
        $path = Storage::putFileAs("public/report/".$date,$request->file('files'),$name_file);
        $link_file = URL::to('/').Storage::url('report/'.$date.'/'.$name_file);
        ReportUpload::create([
            'fileName' => $name_file,
            'linkFile' => $path,
            'linkDownFile' => $link_file,
            'userId' => $request['userId'],
            'positionId' => $request['positionId'],
            'departmentId' => $request['departmentId']
        ]);
        return response()->json([
            'fileName'=>$name_file,
            'downloadLink'=>$link_file,
            'created_at' => Carbon::now()->format('d-m-Y')
        ]);
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
}
