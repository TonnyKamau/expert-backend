<?php

namespace App\Http\Controllers;

use App\Mail\WriterMail;
use App\Models\Task;
use App\Models\Writer;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Jobs\SendMailJob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class MailController extends Controller
{
     public function sendMail(Request $request){
        $id = $request->id;
        $tips= $request->info;
        $email = $request->to;
        // $id = 'task001';
        // $tips= 'test';
        // $email = 'silomalojoseph@gmail.com';

        $user = Auth::User();
        // return $user->userid;
        $user =$user->userid;
        DB::table('tasks')->where('taskid', $id)->update([
            'assignedby'=>$user,
            'state'=>'assigned',
            'writer'=>$email,
            'writingtips'=>$tips
        ]);

        $tsk = Task::where(['taskid' => $id])->get();
        $name = User::select('name')->where(['email' => $email])->get();
        // dd($name);
        $details=[
        'attachment'=>$tsk[0]->attachment,
        'info'=>$tips,
        'message'=>null,
        'more'=>$tsk[0]->addtionalinfo,
        'date'=>$tsk[0]->duration,
        'pages'=>$tsk[0]->page,
        'mode'=>$tsk[0]->mode,
        'type'=>$tsk[0]->type,
        'level'=>$tsk[0]->level,
        'format'=>$tsk[0]->format,
        'subject'=> $tsk[0]->title,
        'email'=> $email,
        'name'=> $name[0]->name,
        ];
        // $details['email'] = '<EMAIL ADDRESS>';
        dispatch(new SendMailJob($details));
        return response()->json(['message' => 'Mail sent suceesfully']);
    }
    public function fetchWriter()
    {
        $user = Auth::User();
        // $notiNum = User::select('outbox')->where(['userid' => $user->userid])->get();
        return response()->json([
                'status' => 200,
                'number' => $user,
             ]);
    }
    public function sendMulti(Request $request)
    {
        $message = $request->message;
        $email = User::select('email')->where(['role' => $request->target])->get();
        // return $email;
        foreach($email as $key=>$value){
            $details=[
            'attachment'=>null,
            'message'=>$message,
            'subject'=> "Ewrite Notification",
            'email'=> $value->email,
            ];
            dispatch(new SendMailJob($details));
        }
        return response()->json(['status' => 200, 'message' => 'Multi-Mail sent suceesfully']);
    }
}
