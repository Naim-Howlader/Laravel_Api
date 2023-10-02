<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Auth;
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
    public function deleteUser($id){
        User::findOrFail($id)->delete();
        $message = "User deleted successfully";
        return response()->json(['message'=>$message],200);
    }
    public function deleteUserJson(Request $request){
        if($request->ismethod('delete')){
            $data = $request->all();
            User::where('id',$data['id'])->delete();
            $message = "User deleted successfully with json";
            return response()->json(['message'=>$message],200);
        }
    }
    public function deleteMultipleUser($ids){
        $ids = explode(',',$ids); //explode method for separate multiple ids
        User::whereIn('id',$ids)->delete();
        $message = "Multiple user deleted successfully";
        return response()->json(['message'=>$message],200);
    }
    public function deleteMultipleUserJson(Request $request){
        $header = $request->header('Authorization');
        if($header==''){
            $message = "Authorization is required";
            return response()->json(['message'=>$message],422);
        }else{
            if($header=='eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiIxMjM0NTY3ODkwIiwibmFtZSI6Ik5haW0gSG93bGFkZXIiLCJpYXQiOjE1MTYyMzkwMjJ9.z2VJHUN4XUPrE_KCGQ-SHk5874mfzCUbz8iTXHLXZt8'){
                if($request->ismethod('delete')){
                    $data = $request->all();
                    User::whereIn('id',$data['ids'])->delete();
                    $message = "Multiple user deleted successfully with json";
                return response()->json(['message'=>$message],200);
                }
            }else{
                $message = "Authorization does not match";
                return response()->json(['message'=>$message],422);
            }
        }

    }



    public function userRegisterPassport(Request $request){
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
            if(Auth::attempt(['email'=>$data['email'], 'password'=>$data['password']])){
                $user = User::where('email',$data['email'])->first();
                $access_token = $user->createToken($data['email'])->accessToken;
                // return  response()->json(['message'=> $access_token],422);die;
                User::where('email',$data['email'])->update(['access_token'=>$access_token]);
                $message = "User Register Successfully";
                return response()->json(['message'=> $message,'Access Token'=>$access_token],201);
            }
            else{
                $message = "Opps Error !";
                return response()->json(['message'=> $message],422);
            }

        }
    }
}
