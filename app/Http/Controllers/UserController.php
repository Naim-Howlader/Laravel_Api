<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Validator;
class UserController extends Controller
{
    public function showUser($id=null){
        if(empty($id)){
            $users = User::get();
            return response()->json(['users'=>$users],200);
        }else{
            $users = User::find($id);
            return response()->json(['users'=>$users],200);
        }
    }
    public function addUser(Request $request){
        if($request->ismethod('post')){
            $data = $request->all();
            //return $data;

            //Customer validation start here
            $rules = [
                'name' => 'required',
                'email' => 'required|email|unique:users',
                'password' => 'required',
            ];
            $errorMsg = [
                'name.required' => "Name must be required",
                'email.required' => "Email must be required",
                'email.email' => "Email must be a valid email",
                'password.required' => "Password must be required",
            ];
            $validation = Validator::make($data,$rules);
            if($validation->fails()){
                return response()->json($validation->errors(),422);
            }

            //Customer validation end here

            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
            ]);
            $message = "User Added Successfully";
            return response()->json(['message'=> $message],201);
        }
    }
}
