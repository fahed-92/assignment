<?php

namespace App\Http\Controllers;

use App\Mail\NotifyCourses;
use App\Models\Course;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class CourseController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');

    }
    public function index(){
        try{

                $course = Course::with('Teacher')->paginate(10);
                if ($course){
                    return response()->json(['corses' => $course ,'code' => 200]);
                }
                else{
                    return response()->json(['data' => 'No courses Exist yet' ,'code' => 200]);
                }
        }catch (\Exception $exception){
            return response()->json(['error' => $exception->getMessage() ,'code' => 500]);
        }catch (\Error $error){
            return response()->json(['error' => $error->getMessage() ,'code' => 500]);
        }
    }
    public function subscribe(Request $request){
        try{
//            return User::with('Course')->get();
            $student = Auth::user();
            $courseIds = $request->courseIds;
            $attached = $student->Course()->sync($courseIds);
                if ($attached){
                    Mail::to($student->email)->send(new NotifyCourses($student));
                    return response()->json(['courses' => 'done' ,'code' => 201]);

                }
        }catch (\Exception $exception){
            return response()->json(['error' => $exception->getMessage() ,'code' => 500]);
        }catch (\Error $error){
            return response()->json(['error' => $error->getMessage() ,'code' => 500]);
        }
    }
}
