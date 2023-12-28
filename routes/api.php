<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\teachercontroller;
use App\Http\Controllers\studentcontroller;
use App\Http\Controllers\enrollcontroller;
use App\Http\Controllers\coursecontroller;

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
//Students CRUD Apis
Route::post("registerstudent",[studentcontroller::class,"student_register"]);
Route::get("allstudents",[studentcontroller::class,"show_students"]);
Route::delete("deletestudent/{id}",[studentcontroller::class,"delete_student"]);
Route::put("updatestudent/{id}",[studentcontroller::class,"update_student"]);
Route::get("student_details/{id}",[studentcontroller::class,"student_details"]);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});