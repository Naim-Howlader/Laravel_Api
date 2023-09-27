<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
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
    public function addMultipleUsers(Request $request){
        if($request->ismethod('post')){
            $data = $request->all();
        }

        $rules = [
            'users.*.name' => 'required',
            'users.*.email' => 'required|email|unique:users',
            'users.*.password' => 'required',
        ];
        $errorMsg = [
            'users.*.name.required' => 'Name is required',
            'users.*.email.required' => 'Email is required',
            'users.*.email.email' => 'Email must be a valid email',
            'users.*.password.required' => 'Password is required',
        ];
        $validation = Validator::make($data,$rules,$errorMsg);
        if($validation->fails()){
            return response()->json($validation->errors(),422);
        }
        foreach($data['users'] as $addUser){
            User::create([
                'name' => $addUser['name'],
                'email' => $addUser['email'],
                'password' => Hash::make($addUser['password']),
            ]);
            $message = "All users added successfully";
           
        }
        return response()->json(['message'=> $message],201);
    }
    public function updateUser(Request $request,$id){
        if($request->ismethod('put')){
            $data = $request->all();
            //return $data;

            //Customer validation start here
            $rules = [
                'name' => 'required',
                'password' => 'required',
            ];
            $errorMsg = [
                'name.required' => "Name must be required",
                'password.required' => "Password must be required",
            ];
            $validation = Validator::make($data,$rules);
            if($validation->fails()){
                return response()->json($validation->errors(),422);
            }

            //Customer validation end here

            $user = User::findOrFail($id);
            $user->name = $data['name'];
            $user->password = Hash::make($data['password']);
            $user->save();
            $message = "User Updated Successfully";
            return response()->json(['message'=> $message],202);
        }
    }
    public function UpdateSingleRecord(Request $request,$id){
        if($request->ismethod('patch')){
            $data = $request->all();
            //return $data;

            //Customer validation start here
            $rules = [
                'name' => 'required',
            ];
            $errorMsg = [
                'name.required' => "Name must be required",
            ];
            $validation = Validator::make($data,$rules);
            if($validation->fails()){
                return response()->json($validation->errors(),422);
            }

            //Customer validation end here

            $user = User::findOrFail($id);
            $user->name = $data['name'];
            $user->update();
            $message = "User's single record updated successfully";
            return response()->json(['message'=> $message],202);
        }
    }
}
