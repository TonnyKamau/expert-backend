<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Money;
use App\Models\Front;
use App\Models\Qoute;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use File;


class TaskController extends Controller
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
    public function store(Request $request, Task $task)
    {
        $user = Auth::User();
        $validator = Validator::make($request->all(), [
            'mode' => 'required|string',
            'type' => 'required|string',
            'level'=>'required|string',
            'duration' => 'required|string',
            'pages' => 'required',
            'format' => 'required|string',
        ]);
        if($validator->fails())
        {
            return response()->json([
                'status' => 400,
                'message' => "Critical selections are empty!",
            ]);
        }else{
            $availability = Front::where(['type' => $request->type,'level' => $request->level,'format' => $request->format])->get()->count();
            if($availability <= 0){
                return response()->json([
                    'status' => 400,
                    'message' => "Try another combination!",
            ]);
            }
            //get price
                try{
                $price = Front::where('type', $request->type)
                    ->where('level', $request->level)
                    ->where('format', $request->format)
                    ->first();
                }catch(\Exception $e){
                    return response()->json([
                        'status' => 400,
                        'totalprice' => "Try another combination2!",
                    ]);
                }

                try{
                    $totalprice = $request->pages * $price->unitprice;
                }catch(\Exception $e){
                    return response()->json([
                        'status' => 400,
                        'totalprice' => "Try another combination3!",
                    ]);
                }
                if($request->mode == "write"){
                    $totalprice = $totalprice * ($price->writepercentage/100);
                }
                else if($mode =="reWrite"){
                    $totalprice = $totalprice * ($price->rewritepercentage/100);
                }else{
                    $totalprice = $totalprice * ($price->editpercentage/100);
                }
                //count tasks;
                $ftimer = Task::where(['userid' => $user->userid])->get()->count();
                    if($ftimer <= 0){
                        $totalprice = $totalprice * 0.85;
                    }
            // store after obtaining price

            $task = new Task();
            $task->userid =$user->userid;
            $task->taskid = uniqid('T');
            $task->title = $request->title;
            $task->mode = $request->mode;
            $task->type = $request->type;
            $task->level = $request->level;
            $task->duration = $request->duration;
            $task->page = $request->pages;
            $task->format = $request->format;
            $task->price = $totalprice;

            // if attachament is available-store else skip
            if ($request->hasFile('attachment')){
                if($request->attachment->getSize()<5300000){
                    $extension = $request->attachment->getClientOriginalName();
                    $task->attachment = $request->attachment->storeAs('tasks',$user->userid.'/'.str_replace(' ','_',date("dmYhis").$extension) );
                    //$task->attachment = Storage::url( 'tasks/'. $user->userid. "/".date("dmYhis").$extension);
                }else{
                return response()->json([
                'status' => 400,
                'message' => "Too large file size max=5mb!",
                ]);
                }
            }
            //
            $task->addtionalinfo = $request->more;
            $task->state = "new";
            $task->save();
            return response()->json([
                'status' => 200,
                'message' => "Task submitted successful, check your tasklist!",
             ]);
            }

    }
 public function update($id, Request $request)
    {
        $user = Auth::User();
        $validator = Validator::make($request->all(), [
            'mode' => 'required|string',
            'type' => 'required|string',
            'level'=>'required|string',
            'duration' => 'required|string',
            'page' => 'required',
            'format' => 'required|string',
            'addtionalinfo' => 'required|string',
        ]);
        if($validator->fails())
        {
            return response()->json([
                'status' => 400,
                'message' => "Critical selections are empty!",
            ]);
        }else{
            $availability = Front::where(['type' => $request->type,'level' => $request->level,'format' => $request->format])->get()->count();
            if($availability <= 0){
                return response()->json([
                    'status' => 400,
                    'message' => "Try another combination!",
            ]);
            }
            //get price
                try{
                $price = Front::where('type', $request->type)
                    ->where('level', $request->level)
                    ->where('format', $request->format)
                    ->first();
                }catch(\Exception $e){
                    return response()->json([
                        'status' => 400,
                        'totalprice' => "Try another combination2!",
                    ]);
                }

                try{
                    $totalprice = $price->unitprice * $request->page;
                }catch(\Exception $e){
                    return response()->json([
                        'status' => 400,
                        'totalprice' => "Try another combination3!",
                    ]);
                }
                if($request->mode == "write"){
                    $totalprice = $totalprice * ($price->writepercentage/100);
                }
                else if($mode =="reWrite"){
                    $totalprice = $totalprice * ($price->rewritepercentage/100);
                }else{
                    $totalprice = $totalprice * ($price->editpercentage/100);
                }
                //count tasks;
                $ftimer = Task::where(['userid' => $user->userid])->get()->count();
                    if($ftimer <= 0){
                        $totalprice = $totalprice * 0.85;
                    }
            // store after obtaining price
            if($totalprice <=0){
                return response()->json([
                        'status' => 400,
                        'message' => "An eror occured during the update",
                    ]);
            }
            if ($request->hasFile('attachment')){
                if($request->attachment->getSize()<5300000){
                    if(File::exists(public_path($request->attachment))){
                        File::delete(public_path($request->attachment));
                    }
                    $extension = $request->attachment->getClientOriginalName();
                    // $request->attachment->storeAs('tasks',$user->userid.'/'.str_replace(' ','_',date("dmYhis").$extension));
                    // $url = Storage::url('tasks',$user->userid.'/'.str_replace(' ','_',date("dmYhis").$extension));
                    // DB::table('tasks')->where('taskid', $id)->update(['attachment'=>$url,]);
                    $url = $request->attachment->storeAs('tasks',$user->userid.'/'.str_replace(' ','_',date("dmYhis").$extension) );
                }else{
                return response()->json([
                'status' => 400,
                'message' => "Too large file size max=5mb!",
                ]);
                }
            }else{
                $url =null;
            }
            DB::table('tasks')->where('taskid', $id)->update([
                        'title'=>$request->title,
                        'mode'=>$request->mode,
                        'type'=>$request->type,
                        'level'=>$request->level,
                        'duration'=>$request->duration,
                        'page'=>$request->page,
                        'price'=>$totalprice,
                        'format'=>$request->format,
                        'addtionalinfo'=>$request->addtionalinfo,
                        'attachment'=>$url
            ]);
            return response()->json([
                'status' => 200,
                'message' => "Task Update completed successfully!",
             ]);
            }

    }
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function show(Task $task)
    {
        $user= Auth::User()->userid;
        $tasks = Task::where(['userid'=> $user])->get();
        return response()->json([
            'status' => 200,
            'task'=>$tasks,

        ]);

    }
    public function showfiltered(Task $task)
    {
        $date = today()->format('Y-m-d');
        $user= Auth::User()->userid;
        // $tasks = Task::where(['userid'=> $user, 'duration', '>=', $date])->get();
        $tasks = Task::where('duration', '>=', $date)->where('userid', '=', $user)->get();
        return response()->json([
            'status' => 200,
            'task'=>$tasks,
        ]);

    }

    // admin and support
    public function adminShow(Task $task)
    {
        $tasks = Task::all();
        return response()->json([
            'status' => 200,
            'tasks'=>$tasks,
        ]);
    }
    // sort by filter
    public function adminShowByDate(Task $task)
    {
        $date = today()->format('Y-m-d');
        $tasks = Task::where('duration', '>=', $date)->get();
        return response()->json([
            'status' => 200,
            'tasks'=>$tasks,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function viewTask($id)
    {
        $cost = Task::select('price')->where(['taskid' => $id])->get();
        $isuploaded = Task::select('upload')->where(['taskid' => $id])->get();
        $money = Money::where(['taskid' => $id])->sum('amount');
        if($money>=$cost[0]->price){
            $doc = true;
        }else{
            $doc = false;
        }
        if($isuploaded[0]->upload != null){
            $doc = true;
        }else{
            $doc = false;
        }
        $balance=$cost[0]->price-$money;
        $tasks = Task::where(['taskid'=> $id])->get();
        return response()->json([
            'status' => 200,
            'tasks'=>$tasks,
            'doc'=>$doc,
            'bal'=>$balance,
        ]);
    }
    public function adminviewTask($id)
    {

        $money = Money::where(['taskid' => $id])->sum('amount');
        $isuploaded = Task::select('upload')->where(['taskid' => $id])->get();
        if($isuploaded[0]->upload != null){
            $doc = true;
        }else{
            $doc = false;
        }
        $tasks = Task::where(['taskid'=> $id])->get();
        $prespt = User::select('name')->where(['userid'=> $tasks[0]->assignedby])->get();
        $name = User::select('name')->where(['userid'=> $tasks[0]->userid])->get();
        if($prespt != null){
            $assigningSupport=$prespt;
        }
        return response()->json([
            'status' => 200,
            'tasks'=>$tasks,
            'doc'=>$doc,
            'money'=>$money,
            'spt'=>$assigningSupport,
            'name'=>$name[0]->name,
        ]);
    }
    public function getDoc($id)
    {
        // $id= "task001";
        $quote= "";
        $cost = Task::select('price')->where(['taskid' => $id])->get();
        $money = Money::where(['taskid' => $id])->sum('amount');
        if($money>=$cost[0]->price){
            $doc = Task::select('upload')->where(['taskid' => $id])->get();
        }else{
            $doc = null;
            $quote = Qoute::select('taskquote')->where(['taskid' => $id])->latest()->first();
        }
        return response()->json([
            'status' => 200,
            'docfile'=>$doc,
            'quote'=>$quote,
        ]);
    }
    public function adminDoc($id)
    {
        $doc = Task::select('upload')->where(['taskid' => $id])->get();
        if($doc != null){
            $doc = $doc;
        }else{
            $doc = null;
        }
        return response()->json([
            'status' => 200,
            'docfile'=>$doc,
        ]);
    }
    public function clientCounter()
    {
        // $id= "T6249a016327cf";

        $user = Auth::User();
        $userid =$user->userid;
        $new = Task::where(['userid' => $userid, 'state' => 'new'])->get()->count();
        $pending = Task::where(['userid' => $userid, 'state' => 'assigned'])->get()->count();
        $done = Task::where(['userid' => $userid, 'state' => 'done'])->get()->count();

        $costs = Task::where(['userid' => $userid])->sum('price');
        $paids = Money::where(['user_id' => $userid])->sum('amount');
        $topay= $costs-$paids;
        return response()->json([
            'status' => 200,
            'new'=>$new,
            'pending'=>$pending,
            'done'=>$done,
            'topay'=>$topay,
        ]);
    }
    public function adminCounter()
    {
        $new = Task::where(['state' => 'new'])->get()->count();
        $pending = Task::where(['state' => 'assigned'])->get()->count();
        $done = Task::where(['state' => 'done'])->get()->count();

        $costs = Task::sum('price');
        $paids = Money::sum('amount');
        $topay= $costs-$paids;
        return response()->json([
            'status' => 200,
            'new'=>$new,
            'pending'=>$pending,
            'done'=>$done,
            'topay'=>$topay,
        ]);
    }
    public function supportCounter()
    {
        $user = Auth::User()->userid;
        $new = Task::where(['state' => 'new'])->get()->count();
        $pending = Task::where(['state' => 'assigned', 'assignedby' => $user])->get()->count();
        $done = Task::where(['state' => 'done', 'assignedby' => $user])->get()->count();

        // $costs = Task::where(['assignedby' => $user])->sum('price');
        // $paids = Money::where(['assignedby' => $user])->sum('amount');
        // $topay= $costs-$paids;
        return response()->json([
            'status' => 200,
            'new'=>$new,
            'pending'=>$pending,
            'done'=>$done,
            // 'topay'=>$topay,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function rateWork(Request $request, Task $task)
    {

        $id=$request->id;
        $rt=$request->rate;
        $cmt=$request->comment;
        DB::table('tasks')->where('id', $id)->update([
            'rate'=>$rt,
            'comment'=>$cmt,
        ]);
        return response()->json([
            'status' => 200,
            'message' => "Rate sent succeffuly",
        ]);

    }
    public function upFile(Request $request, Task $task)
    {
        //update table tasks Records
        $user= Auth::User();
        $id=$request->id;
        $tskqt=$request->qoute;

        DB::table('tasks')->where('taskid', $id)->update([
            'state'=>'done',
        ]);
        $qt = new Qoute();
        $qt->taskid =$id;
        $qt->taskquote =$tskqt;
        $qt->save();
        // saving the task file
            if ($request->hasFile('doneFile')){
                if($request->doneFile->getSize()<50000000){
                    $extension = $request->doneFile->getClientOriginalName();
                    $request->doneFile->storeAs('/tasks/donetasks/'. $user->userid, date("dmYhis").$extension);
                    $task = ( "tasks/donetasks/". $user->userid. "/".date("dmYhis").$extension);
                    DB::table('tasks')->where('taskid',$id)->update(['upload'=>$task]);

                    return response()->json([
                        'status' => 200,
                        'message' => "File uploaded successfuly!",
                    ]);
                }else{
                    return response()->json([
                    'status' => 400,
                    'message' => "Too large file size max=5mb!",
                    ]);
                }
            }else{
                return response()->json([
                'status' => 400,
                'message' => "File cannot be empty",
                ]);
            }


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function deleter($id)
    {
        DB::delete('delete from tasks where taskid = ?', [$id]);
        return response()->json([
            'status' => 200,
            'message' => "Task deleted Successfully ",
        ]);

    }
}
