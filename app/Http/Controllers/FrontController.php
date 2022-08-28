<?php

namespace App\Http\Controllers;

use App\Models\Front;
use App\Models\Type;
use App\Models\Format;
use App\Models\Level;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class FrontController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function priceGenerator(Request $request)
    {
        //validate the request
        $validator = Validator::make($request->all(), [
            'format' => 'required|string',
            'level' => 'required|string',
            'type' => 'required|string',
            'page' => 'required',
        ]);
        if($validator->fails()){
            return response()->json([
                'status' => 401,
                'message' => "Critical selections are empty!",
            ]);
        }else{

            $type= $request->type;
            $level= $request->level;
            $format= $request->format;
            $mode= $request->mode;
            $pages=(int)$request->page;
            $ftimer=$request->ftimer;


            // $type= "essay";
            // $level= "college";
            // $format= "APA";
            // $mode= "write";
            // $pages=2;
            // $ftimer="false";

        try{
            $price = Front::where('type', $type)
                ->where('level', $level)
                ->where('format', $format)
                ->first();
        }catch(\Exception $e){
            return response()->json([
                'status' => 400,
                'totalprice' => "Try another combination!",
            ]);
        }

        try{
            $totalprice = $pages * $price->unitprice;
        }catch(\Exception $e){
            return response()->json([
                'status' => 400,
                'totalprice' => "Try another combination!",
            ]);
        }
        //dd($price);
            //total price paer the number of pages
        //total price per mode
        if($mode == "write"){
            $totalprice = $totalprice * ($price->writepercentage/100);
        }
        else if($mode =="reWrite"){
            $totalprice = $totalprice * ($price->rewritepercentage/100);
        }else{
            $totalprice = $totalprice * ($price->editpercentage/100);
        }
        //dd($totalprice);
        if($ftimer == true){
            $totalprice = $totalprice * 0.85;
        }
        // dd($totalprice);
        return response()->json([
            'status' => 200,
            'totalprice' => $totalprice,
        ]);
    }

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
            $opt= $request->option;
            $title= $request->title;
            $desc= $request->description;
            if($opt == "type"){
                $type= new Type();
                $type->title =$title;
                $type->description =$desc;
                $type->save();
            }else if($opt == "level"){
                $level= new Level();
                $level->title =$title;
                $level->description =$desc;
                $level->save();
            }else if($opt == "format"){
                $format= new Format();
                $format->title =$title;
                $format->description =$desc;
                $format->save();
            }else{
                return response()->json([
                'status' => 201,
                'message' => "Choose the correct option",
            ]);
            }
            return response()->json([
                'status' => 200,
                'message' => "You have created Successfully",
            ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getall()
    {
        $level = Level::all();
        $type = Type::all();
        $format = Format::all();
        return response()->json([
            'status' => 200,
            'level' => $level,
            'type' => $type,
            'format' => $format,
        ]);
    }
    public function getallfronts()
    {
        $front = Front::all();
        return response()->json([
            'status' => 200,
            'front' => $front,
        ]);
    }
    public function saveFront(Request $request)
    {
        $front= new Front();
        $front->type =$request->type;
        $front->level =$request->level;
        $front->format =$request->format;
        $front->unitprice =$request->uprice;
        $front->writepercentage =$request->writep;
        $front->editpercentage =$request->editp;
        $front->save();
        return response()->json([
            'status' => 200,
            'message' => "Combination added successfully",
        ]);
    }
    //get details by id
    public function getDetails($id)
    {

        $details = Front::where(['id' => $id])->get();
        return response()->json([
            'status' => 200,
            'details' => $details,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Front  $front
     * @return \Illuminate\Http\Response
     */
    public function editDetails(Request $request)
    {

        DB::table('fronts')->where('id', $request->id)->update([
            'type'=>$request->type,
            'level'=>$request->level,
            'format'=>$request->format,
            'unitprice'=>$request->unitprice,
            'writepercentage'=>$request->writepercentage,
            'editpercentage'=>$request->editpercentage,
        ]);
        return response()->json([
            'status' => 200,
            'message' => "Updated successfully",
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Front  $front
     * @return \Illuminate\Http\Response
     */
    public function edit(Front $front)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Front  $front
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Front $front)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Front  $front
     * @return \Illuminate\Http\Response
     */
    public function destroyChooser(Request $request)
    {
        if($request->type == "type"){
            DB::delete('delete from types where id = ?', [$request->id]);
            return response()->json([
                'status' => 200,
                'message' => "Type deleted Successffuly ",
            ]);
        }else if($request->type == "level"){
            DB::delete('delete from levels where id = ?', [$request->id]);
            return response()->json([
                'status' => 200,
                'message' => "Level deleted Successffuly ",
            ]);
        }else if($request->type == "format"){
            DB::delete('delete from formats where id = ?', [$request->id]);
            return response()->json([
                'status' => 200,
                'message' => "Format deleted Successffuly ",
            ]);
        }else{
            return response()->json([
                'status' => 201,
                'message' => "Error Occured, retry! ",
            ]);
        }
    }
    public function destroyRecord($id)
    {
        DB::delete('delete from fronts where id = ?', [$id]);
        return response()->json([
            'status' => 200,
            'message' => "Record deleted Successffuly ",
        ]);
    }
    public function logOut(Request $request) {
        auth()->logout();
        return redirect()->route('login');
    }
}
