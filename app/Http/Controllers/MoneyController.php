<?php

namespace App\Http\Controllers;

use App\Models\Money;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class MoneyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getHistory()
    {
        //get all monies
        // $monies = Money::where('user_id', Auth::user()->id)->get();
        // $monies = Money::all();

        $monies = Money::select('id','payment_id','user_id','taskid','amount','status','created_at')->get();
        return response()->json([
            'status' => 200,
            'message' => $monies,
        ]);
    }
    public function myHistory()
    {
        //get all monies
        $user = Auth::user()->userid;
        $monies = Money::select('id','taskid','amount','status','payment_method as method','created_at as date')->where(['user_id' => $user,])->get();
        return response()->json([
            'status' => 200,
            'message' => $monies,
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
    public function savePayment(Request $request)
    {
        //save payment

        $user = Auth::User();
        $money = new Money();
        $money->payment_id = $request->pid;
        $money->user_id = $user->userid;
        $money->taskid = $request->tid;
        $money->status = $request->sts;
        $money->amount = $request->amt;
        $money->payment_method = "paypal";
        $money->other_payment_details = $request->order;
        $money->save();
        return response()->json([
            'status' => 200,
            'message' => 'Payment Done',
        ]);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Money  $money
     * @return \Illuminate\Http\Response
     */
    public function show(Money $money)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Money  $money
     * @return \Illuminate\Http\Response
     */
    public function edit(Money $money)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Money  $money
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Money $money)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Money  $money
     * @return \Illuminate\Http\Response
     */
    public function destroy(Money $money)
    {
        //
    }
}
