<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class TeacherAuthController extends Controller
{
    private $teacherModel;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Teacher $teacher)
    {
        $this->teacherModel = $teacher;

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
        // authenticated teacher
        if (!Auth::guard('teachers')->attempt($request->only(['email' , 'password']))) {
            return response()->json(['error' => 'invalid email or password'] , 401);
        }
        //generate token
        $teacher = $this->teacherModel->where('email' , $request-> email)->first();
        $device_name=$request->post('device_name' , $request->userAgent());
        $teacher_token = $teacher->createToken($device_name);
        return response()->json([
            'token' => $teacher_token -> plainTextToken ,
            'teacher' => $teacher] , 401);


    }

    public function register(Request $request)
    {
        $validator=Validator::make($request->all() , [
            'name' => 'required',
            'email' =>  ['required' , 'email'],
            'password' => ['required' , 'string'],
            'experience' => ['required'],
            'device_name' => 'string|max:255'
        ]);
        if ($validator->failed()){
            return response()->json(['error' => $validator ->errors()] , 401);
        }
        //generate token
        $teacher = $this->teacherModel->create([
            'name' => $request->name,
            'email' =>  $request->email,
            'password' => Hash::make($request->password),
            'experience' => $request->experience,
            'device_name' => $request->device_name
        ]);
        $device_name=$request->post('device_name' , $request->userAgent());
        $teacher_token = $teacher->createToken($device_name);
        return response()->json([
            'token' => $teacher_token -> plainTextToken ,
            'teacher' => $teacher] , 401);


    }

}
