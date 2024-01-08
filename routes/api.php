<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\teachercontroller;
use App\Http\Controllers\studentcontroller;
use App\Http\Controllers\enrollcontroller;
use App\Http\Controllers\coursecontroller;
use App\Http\Controllers\modulecontroller;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
//Teachers Crud Apis
Route::post("registerteacher",[teachercontroller::class,"teacher_register"]);
Route::get("allteachers",[teachercontroller::class,"allteachers"]);
Route::delete("deleteteacher/{id}",[teachercontroller::class,"deleteteacher"]);
Route::get("showteacher/{id}",[teachercontroller::class,"showteacher"]);
Route::put("updateteacher/{id}",[teachercontroller::class,"updateteacher"]);
//Students CRUD Apis
Route::post("registerstudent",[studentcontroller::class,"student_register"]);
Route::get("allstudents",[studentcontroller::class,"show_students"]);
Route::delete("deletestudent/{id}",[studentcontroller::class,"delete_student"]);
Route::put("updatestudent/{id}",[studentcontroller::class,"update_student"]);
Route::get("student_details/{id}",[studentcontroller::class,"student_details"]);
//Course CRUD Apis
Route::post("courseregister",[coursecontroller::class,"course_register"]);
Route::get("showcourses",[coursecontroller::class,"showCourses"]);
Route::put("updatecourse",[coursecontroller::class,"updatecourse"]);
Route::delete("deletecourse",[coursecontroller::class,"deletecourse"]);
Route::get("showcourse",[coursecontroller::class,"showcourse"]);
//Module CRUD Apis
Route::post('createmodule',[modulecontroller::class,'create_module']);
Route::get('allmodules',[modulecontroller::class,'allmodules']);
Route::get('showmodule/{id}',[modulecontroller::class,'showmodule']);
Route::delete('deletemodule/{id}',[modulecontroller::class,'deletemodule']);
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
