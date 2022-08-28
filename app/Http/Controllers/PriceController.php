<?php

namespace App\Http\Controllers;

use App\Models\Type;
use App\Models\Level;
use App\Models\Format;
use App\Models\User;
use App\Models\Money;
use App\Models\Task;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class PriceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function types()
    {
        // $all_types=Type::all()->pluck('title');
        $all_types=Type::all();
        //dd($all_types);
        return response()->json([
            'status' => 200,
            'allTypes' => $all_types,
        ]);

    }
    public function levels()
    {
        $all_levels=Level::all();
        //dd($all_types);
        return response()->json([
            'status' => 200,
            'allLevels' => $all_levels,
        ]);
    }
    public function formats()
    {
        $all_formats=Format::all();
        return response()->json([
            'status' => 200,
            'allFormats' => $all_formats,
        ]);
        //dd($all_types);
    }
    // the combined
    public function combined()
    {
        $all_formats=Format::all();
        $all_levels=Level::all();
        $all_types=Type::all();
        return response()->json([
            'status' => 200,
            'allFormats' => $all_formats,
            'allLevels' => $all_levels,
            'allTypes' => $all_types,
        ]);
    }



    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getbalance()
    {
        //get all from payment controller

        $user =  Auth::User()->userid;
        $deposits = Money::where('user_id',$user)->sum('amount');
        $deductions = Task::where('userid',$user)->sum('price');
        $balance = $deposits - $deductions;
        return response()->json([
            'status' => 200,
            'balance' => $balance,
        ]);
    }
     public function getexpence()
    {
        //get all from payment controller
        $user =  Auth::User()->userid;
        // $paid = Task::select('*')->where('userid',$user)->orWhere('state',$complete)->sum('price');

        $paid = Task::where(['userid'=> $user, 'state' => 'done'])->sum('price');
        $topay = Task::where(['userid'=> $user, 'state' => 'assigned'])->sum('price');
        // $topay = Task::where('userid',$user)->where('state',$new)->where('state',$assigned)->sum('price');
        $deposits = Money::where('user_id',$user)->sum('amount');
        $deductions = Task::where('userid',$user)->sum('price');
        $balance = $deposits - $deductions;
        return response()->json([
            'status' => 200,
            'paid' => $paid,
            'topay' => $topay,
            'balance' => $balance,
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
}
