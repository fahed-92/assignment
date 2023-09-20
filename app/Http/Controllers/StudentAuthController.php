<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class StudentAuthController extends Controller
{
    private $studentModel;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(User $student)
    {
        $this->studentModel = $student;

    }
    public function login(Request $request)
    {
        $validator=Validator::make($request->all() , [
            'email' =>  ['required' , 'email'],
            'password' => ['required' , 'string'],
            'device_name' => 'string|max:255'
        ]);
        if ($validator->failed()){
            return response()->json(['error' => $validator ->errors()] , 401);
        }
        // authenticated student
        if (!Auth::guard('users')->attempt($request->only(['email' , 'password']))) {
            return response()->json(['error' => 'invalid email or password'] , 401);
        }
        //generate token
        $user = $this->studentModel->where('email' , $request-> email)->first();
        $device_name=$request->post('device_name' , $request->userAgent());
        $user_token = $user->createToken($device_name);
        return response()->json([
            'token' => $user_token -> plainTextToken ,
            'user' => $user] , 401);


    }

    public function register(Request $request)
    {
        $validator=Validator::make($request->all() , [
            'name' => 'required',
            'email' =>  ['required' , 'email'],
            'password' => ['required' , 'string'],
            'device_name' => 'string|max:255'
        ]);
        if ($validator->failed()){
            return response()->json(['error' => $validator ->errors()] , 401);
        }
        //generate token
        $user = $this->studentModel->create([
            'name' => $request->name,
            'email' =>  $request->email,
            'password' => Hash::make($request->password),
            'experience' => $request->experience,
            'device_name' => $request->device_name
        ]);
        $device_name=$request->post('device_name' , $request->userAgent());
        $user_token = $user->createToken($device_name);
        return response()->json([
            'token' => $user_token -> plainTextToken ,
            'user' => $user] , 401);


    }
}
