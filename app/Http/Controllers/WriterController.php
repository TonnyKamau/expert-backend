<?php

namespace App\Http\Controllers;

use App\Models\Writer;
use App\Models\Task;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class WriterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //get all writers
        $all_writers=Writer::all();
        // $all_writers=Writer::all();
        return response()->json([
            'status' => 200,
            'allWriters' => $all_writers,
        ]);
    }
    public function taskDetails($id)
    {
        //get task where id
        $task=Task::where('id',$id)->first();
        return response()->json([
            'status' => 200,
            'task' => $task,
        ]);


    }

    public function writerData($id)
    {
       $wdata = Writer::where(['id'=> $id])->first();
        return response()->json([
            'status' => 200,
            'wdata' => $wdata,
        ]);
    }

    //saving updates on support changes infor
    public function changeWriter(Request $request)
    {
        $id=$request->id;
        $name=$request->name;
        $email=$request->email;
        $field=$request->field;
        DB::table('writers')->where('id', $id)->update([
            'name'=>$name,
            'email'=>$email,
            'specialization'=>$field,

        ]);
        return response()->json([
            'status' => 200,
            'message' => "Writer data uploaded successfuly",
        ]);
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
     * @param  \App\Models\Writer  $writer
     * @return \Illuminate\Http\Response
     */
    public function show(Writer $writer)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Writer  $writer
     * @return \Illuminate\Http\Response
     */
    public function edit(Writer $writer)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Writer  $writer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Writer $writer)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Writer  $writer
     * @return \Illuminate\Http\Response
     */
    public function destroyWriter($id)
    {
        DB::delete('delete from writers where id = ?',[$id]);
        return response()->json([
            'status' => 200,
            'message' => "Writer deleted Succeffuly ",
        ]);

    }
}
