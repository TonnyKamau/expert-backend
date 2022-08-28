<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Writer;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;


class UserController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function handleGoogleCallback(Request $request)
    {

        try {
            $provider_id = $request->googleId;
            $email = $request->email;
            $name = $request->name;

            $user = User::where('email', $email)->first();

            if ($user) {
                Auth::login($user);

                return response()->json([
                    'status' => 200,
                    'user' => Auth::User()
                ]);
            } else {
                $user = User::create([
                    'userid' => uniqid('U'),
                    'name' => $name,
                    'role' => 3,
                    'provider' => 'google',
                    'email' => $email,
                    'provider_id' => $provider_id,
                    'password' => ''
                ]);
                Auth::login($user);

                return response()->json([
                    'status' => 200,
                    'user' => Auth::User()
                ]);
            }
        } catch (\Exception $e) {
        }
    }
    public function handleTwitterCallback(Request $request)
    {

        try {
            $provider_id = $request->provider_id;
            $email = $request->email;
            $name= $request->name;

            $user = User::where('email', $email)->first();

            if ($user) {
                Auth::login($user);

                return response()->json([
                    'status' => 200,
                    'user' => Auth::User()
                ]);
            } else {
                $user = User::create([
                    'userid' => uniqid('U'),
                    'name'=> $name,
                    'provider' => 'twitter',
                    'email' => $email,
                    'provider_id' => $provider_id,
                    'password' => ''
                ]);
                Auth::login($user);

                return response()->json([
                    'status' => 200,
                    'user' => Auth::User()
                ]);
            }
        } catch (\Exception $e) {
        }
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'message' => "Critical selections are empty!",
            ]);
        } else {
            $user= new User();
            $user->userid =uniqid('U');
            $user->role =3;
            $user->name= $request->name;
            $user->email= $request->email;
            $password=$request->password;
            $user->password= Hash::make($password);
            $user->save();
            return response()->json([
                'status' => 200,
                'message' => "You are registered Succefully",
            ]);
        }
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
    public function getUinfo()
    {
        $user = Auth::User();
        return response()->json([
            'status' => 200,
            'user' => $user,
        ]);
    }
    // store updated profile
    public function saveUProfile(Request $request)
    {
        $user = Auth::User();
        if($user->email == $request->email){
            if($request->isPchanged == "true" && $request->password != "" ){
            $validator = Validator::make($request->all(), [
                    'password' => 'required|string|min:10||max:25',
                ]);
                if ($validator->fails()) {
                    return response()->json([
                        'status' => 400,
                        'message' => "Password must be atleast 10 and max 25 characters",
                    ]);
                }
            }
        }else{
            $validator = Validator::make($request->all(), [
                    'email' => 'required|string|email|max:255|unique:users',
                ]);
                if ($validator->fails()) {
                    return response()->json([
                        'status' => 400,
                        'message' => "Must be a Unique email!",
                    ]);
                }
        }

        if($request->isPchanged == "true" && $request->password != "" ){
            $validator = Validator::make($request->all(), [
                    'password' => 'required|string|min:10||max:255',
                ]);
                if ($validator->fails()) {
                    return response()->json([
                        'status' => 400,
                        'message' => "Password must be atleast 10 max 25 characters",
                    ]);
                }
         DB::table('users')->where('userid', $user->userid)->update([
                        'name'=>$request->name,
                        'email'=>$request->email,
                        'password'=>Hash::make($request->password)
            ]);
        }else{
            DB::table('users')->where('userid', $user->userid)->update([
                        'name'=>$request->name,
                        'email'=>$request->email
            ]);
        }
        return response()->json([
            'status' => 200,
            'message' => "Profile updated",
        ]);
    }
    //get users list where role =3
    public function getusers()
    {
        $num = User::where('outbox', '>', 0 )->get()->count();
        $list = User::where(['role' => 3])->get();
        return response()->json([
            'status' => 200,
            'list' => $list,
            'num' => $num,
        ]);
    }
    public function getusersFiltered($searchID)
    {
        if($searchID != "null"){
            $list = User::where(['role' => 3, 'userid' => $searchID])->get();
        }else{
            $list = User::where(['role' => 3])->get();
        }
        $num = User::where('outbox', '>', 0 )->get()->count();
        return response()->json([
            'status' => 200,
            'list' => $list,
            'num' => $num,
        ]);
    }

    //update users
    public function upUserDetails(Request $request)
    {

        $userid = $request->userid;
        $name = $request->name;
        $email = $request->email;
        $specialization = $request->specialization;

        if($request->password != "" ){
        $validator = Validator::make($request->all(), [
                'password' => 'required|string|min:10||max:25',
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'status' => 400,
                    'message' => "Password must be atleast 10 and max 25 characters",
                ]);
            }
        DB::table('users')->where('userid', $userid)->update([
            'name'=>$name,
            'email'=>$email,
            'specialization'=>$specialization,
            'password'=>Hash::make($request->password)
        ]);
        }else{
            DB::table('users')->where('userid', $userid)->update([
                'name'=>$name,
                'email'=>$email,
                'specialization'=>$specialization
            ]);
        }
        return response()->json([
            'status' => 200,
            'message' => "User details updated successfully",
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function ifAuth()
    {
        //check if the user is authenticated by santuming the token
        if (Auth::check()) {
            $user = Auth::User();
            // dd($user);
            if ($user->role == 1) {
                return response()->json([
                    'status' => 200,
                    'message' => $user,
                    // 'data' => $user
                ]);
            } elseif ($user->role == 2) {
                return response()->json([
                    'status' => 202,
                    'message' => $user,
                    // 'data' => $user
                ]);
            } else {
                return response()->json([
                    'status' => 204,
                    'message' => $user,
                ]);
            }
        } else {
            return response()->json([
                'status' => 400,
                'message' => "false",
            ]);
        }
    }
    //role only
    public function ifAuthrole()
    {
        //check if the user is authenticated by santuming the token
        if (Auth::check()) {
            $user = Auth::User()->role;
            // dd($user);
            if ($user == 1) {
                return response()->json([
                    'status' => 200,
                    'message' => $user,
                    // 'data' => $user
                ]);
            } elseif ($user == 2) {
                return response()->json([
                    'status' => 202,
                    'message' => $user,
                    // 'data' => $user
                ]);
            } else {
                return response()->json([
                    'status' => 204,
                    'message' => $user,
                ]);
            }
        } else {
            return response()->json([
                'status' => 400,
                'message' => "false",
            ]);
        }
    }
    //role only
    public function addstaff(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255|unique:users',
            // 'name' => 'required|string|email|max:255',
            // 'role' => 'required|string|email|max:255',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'message' => "Unique Email is required!",
            ]);
        } else {
            $user= new User();
            $user->userid =uniqid('U');
            $user->name =$request->name;
            $user->role =$request->role;
            $user->email= $request->email;
            if($request->role=="4"){
            $user->specialization= $request->field;
            $user->password= "notneeded";
            }else{
            $password=$request->password;
            $user->password= Hash::make($password);
            }
            $user->save();
            return response()->json([
                'status' => 200,
                'message' => "User registered Succefully",
            ]);
        }
    }
    public function getstaffs()
    {
       //get staff where role =2 and 4
    //    $staff = User::all();
       $staff = User::where(['role'=>2 ])->orWhere(['role'=> 3])->orWhere(['role'=> 4])->get();
       //get all writters
        return response()->json([
            'status' => 200,
            'staff' => $staff,
        ]);

    }
    public function info($userid)
    {
       //get staff where role =2 and 4
    //    $staff = User::all();
       $staff = User::where(['userid'=> $userid])->get();
        return response()->json([
            'status' => 200,
            'staff' => $staff,
        ]);
    }
    public function getClients()
    {
        $clients = User::where(['role' => 3])->get();
        return response()->json([
            'status' => 200,
            'clients' => $clients,
        ]);
    }
    public function supportData($id)
    {
        $sdata = User::where(['id' => $id])->first();
        return response()->json([
            'status' => 200,
            'sdata' => $sdata,
        ]);
    }

    //saving updates on support changes infor
    public function changeSupport(Request $request)
    {
        $id = $request->id;
        $name = $request->name;
        $email = $request->email;
        DB::table('users')->where('id', $id)->update([
            'name' => $name,
            'email' => $email,
        ]);
        return response()->json([
            'status' => 200,
            'message' => "Support data uploaded successfuly",
        ]);
    }

    public function writers()
    {

        $all_writers = User::select('name','email')->where(['role' => 4])->get();
        return response()->json([
            'status' => 200,
            'allWriters' => $all_writers,
        ]);
    }

    //clients updation
    public function clientData($id)
    {
        $cdata = User::where(['id' => $id])->first();
        return response()->json([
            'status' => 200,
            'cdata' => $cdata,
        ]);
    }

    //saving updates
    public function changeClient(Request $request)
    {
        $id = $request->id;
        $email = $request->email;
        // $pwd=$request->password;
        DB::table('users')->where('id', $id)->update([
            'email' => $email,
            // 'password'=>$pwd,
        ]);
        return response()->json([
            'status' => 200,
            'message' => "Support data uploaded successfuly",
        ]);
    }
    public function destroyUser($id)
    {
        DB::delete('delete from users where userid = ?', [$id]);
        return response()->json([
            'status' => 200,
            'message' => "User deleted Succeffuly ",
        ]);
    }
}
