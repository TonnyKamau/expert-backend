<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ChatController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function fetchstudent()
    {
        $user = Auth::User();
        $notiNum = User::select('inbox')->where(['userid' => $user->userid])->get();
        return response()->json([
                'status' => 200,
                'number' => $notiNum[0]->inbox,
             ]);
    }
    // fetchcurrent
    public function fetchCurrent()
    {
        $user = Auth::User();
        $notiNum = User::select('inbox')->where(['userid' => $user->userid])->get();
        return response()->json([
                'status' => 200,
                'number' => $notiNum[0]->inbox,
             ]);
    }
    public function resetStudent()
    {
        $user = Auth::User();
        DB::table('users')->where('userid', $user->userid)->update([
                    'inbox'=> 0,
                ]);

        $notiNum = User::select('inbox')->where(['userid' => $user->userid])->get();
        return response()->json([
                'status' => 200,
                'number' => $notiNum[0]->inbox,
             ]);
    }
    public function resetCurrent($roomid)
    {
        $retunSize = User::select('outbox')->where(['userid' => $roomid])->get();
        $retunSize = $retunSize[0]->outbox;
        if($retunSize > 0){
        $chats= Chat::Oldest()->where(['taskid' => $roomid])->take($retunSize)->get();
        }else{
        $chats= Chat::Oldest()->where(['taskid' => $roomid])->take(10)->get();
        }

        DB::table('users')->where('userid', $roomid)->update([
                    'outbox'=> 0,
                ]);
        return response()->json([
                'status' => 200,
                'chats' => $chats,
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
    public function store(Request $request, Chat $chat)
    {
        $validator = Validator::make($request->all(), [
            'room' => 'required|string',
            'message'=>'required|string',
            'time' => 'required',
        ]);
        if($validator->fails())
        {
            return response()->json([
                'status' => 400,
                'message' => "Critical selections are empty!",
            ]);
        }else{
            $user = Auth::User();

            $outnum = User::select('outbox')->where(['userid' => $user->userid])->get();
            $innum = User::select('inbox')->where(['userid' => $request->room])->get();
            if($user->role ==3){
                DB::table('users')->where('userid', $user->userid)->update([
                    'outbox'=>$outnum[0]->outbox + 1,
                ]);
            }else if($user->role == 1 || $user->role ==2){
                DB::table('users')->where('userid', $request->room)->update([
                    'inbox'=>$innum[0]->inbox + 1,
                ]);
            }
            else{
                DB::table('users')->where('userid', $request->room)->update([
                    'outbox'=> 0,
                    'inbox'=> 0,
                ]);
            }
            $chat = new Chat();
            $chat->sender =$user->userid;
            $chat->taskid = $request->room;
            $chat->message = $request->message;
            $chat->time = $request->time;
            $chat->save();

            return response()->json([
                'status' => 200,
                'message' => "SMS saved successfully!",
             ]);
            }
        }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Chat  $chat
     * @return \Illuminate\Http\Response
     */
    public function getChat(Chat $chat)
    {
        $user = Auth::User();
        $retunSize = User::select('inbox')->where(['userid' => $user->userid])->get();
        $retunSize = $retunSize[0]->inbox;
        if($retunSize > 0){
        $chats= Chat::Oldest()->where(['taskid' => $user->userid])->take($retunSize)->get();
        }else{
        $chats= Chat::Oldest()->where(['taskid' => $user->userid])->take(10)->get();
        // $chats= Chat::where(['taskid' => $user->userid])->ordertBy('created_at', 'desc')->get();
        }
        return response()->json([
                'status' => 200,
                'chats' => $chats,
             ]);
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Chat  $chat
     * @return \Illuminate\Http\Response
     */
    public function edit(Chat $chat)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Chat  $chat
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Chat $chat)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Chat  $chat
     * @return \Illuminate\Http\Response
     */
    public function destroy(Chat $chat)
    {
        //
    }
}
