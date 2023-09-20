<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class teacherController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:teachers,api_teachers');

    }

    public function addCourse(Request $request, $teacher_id)
    {
        try {
            if (Auth::user()->id == $teacher_id && Auth::guard('teachers')) {
                $course = Course::create([
                    'name' => $request->name,
                    'description' => $request->description,
                    'image' => $request->image,
                    'teacher_id' => $request->teacher_id,
                ]);
                return response()->json(['data' => $course, 'code' => 201]);
            } else {
                return response()->json(['error' => 'Un Authorize', 'code' => 403]);;
            }

        } catch (\Exception $exception) {
            return response()->json(['error' => $exception->getMessage(), 'code' => 500]);
        } catch (\Error $error) {
            return response()->json(['error' => $error->getMessage(), 'code' => 500]);
        }
    }

}
