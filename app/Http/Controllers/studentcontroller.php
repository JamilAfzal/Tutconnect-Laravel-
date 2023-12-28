<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Student;

class studentcontroller extends Controller
{
    public function student_register(Request $req){
        $validate = Validator::make($req->all(), [
            'email' => 'required|email|unique:students,email',
            'fullname' => 'required|min:5|unique:students',
            'password' => 'required|min:7|max:20',
            'phonenumber' => 'required|string|min:11|max:15|unique:students,phonenumber']);
            if($validate->fails()){
                return response()->json(['Errors' => $validate->errors()]);
            }
            $student = new Student();
            $student->email=$req->input("email");
            $student->fullname=$req->input("fullname");
            $student->password=$req->input("password");
            $student->phonenumber=$req->input("phonenumber");
            $student->save();
            return response()->json(["Message" => "Student Registered Successfully", "Data" => $student]);
    }
    public function show_students(){
        $students = Student::all();
        $Allstudents = $students->map(function ($student) {
            return [
                'student_id' => $student->student_id, 
                'email' => $student->email,
                'fullname' => $student->fullname,
                'phonenumber' => $student->phonenumber
                
            ];
           
        });
        return response()->json(["Status"=>"Success","Message"=>"Showing All The Students", "Data"=>$Allstudents]);
    }
    public function delete_student($student_id) {
        $deletestudent = Student::find($student_id);
    
        if (!$deletestudent) {
            return response()->json(["Status" => "Error", "Message" => "Student Not Found"]);
        }
    
        $deletestudent->delete();
        
        return response()->json(["Status" => "Success", "Message" => "Student Has Been Deleted"]);
    }
    public function update_student(Request $req, $student_id) {
        $student = Student::find($student_id);
        if (!$student) {
            $result = array('Status' => "Failed", "Message" => "Student Not Found");
            return response()->json($result);
        }
    
        $validatedData = $req->validate([
            'email' => 'required|email|unique:students,email,' . $student_id . ',student_id',
            'fullname' => 'required|min:5|unique:students,fullname,' . $student_id . ',student_id',
            'phonenumber' => 'required|string|min:11|max:15|unique:students,phonenumber,' . $student_id . ',student_id',
        ]);
    
        
        $student->fullname = $validatedData['fullname'] ??student->fullname;
        $student->email = $validatedData['email'] ?? $student->email;
        $student->phonenumber = $validatedData['phonenumber'] ?? $student->phonenumber; 
        $student->save();
    
        $result = array('status' => 'true', 'message' => 'User has been Updated', 'data' => $student);
        return response()->json($result);
    }
    public function student_details($student_id){
        $student = Student::find($student_id);
        if (!$student) {
            $result = array('Status' => "Failed", "Message" => "Student Not Found");
            return response()->json($result);
        }
        return response()->json(["Status" => "Success", "Message" => "Showing the Student Details","Data"=>$student]);
    }
}    