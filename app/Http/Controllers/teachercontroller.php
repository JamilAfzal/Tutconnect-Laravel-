<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\customfields;
use App\Models\Teacher; // Fix the namespace
use Illuminate\Support\Facades\Validator;

class teachercontroller extends Controller
{
    public function teacher_register(Request $req)
    {
        try {
            $validate = Validator::make($req->all(), [
                'email' => 'required|email|unique:teachers,email',
                'fullname' => 'required|min:5|unique:teachers',
                'password' => 'required|min:7|max:20',
                'phonenumber' => 'required|string|min:11|max:15|unique:teachers,phonenumber',
                
            ]);
    
            if ($validate->fails()) {
                return response()->json(['Errors' => $validate->errors()]);
            }
    
            $teacher = new Teacher();
            $teacher->email = $req->input('email');
            $teacher->fullname = $req->input('fullname');
            $teacher->password = $req->input('password');
            $teacher->phonenumber = $req->input('phonenumber');
            $teacher->qualification = $req->input('qualification');
            $teacher->about = $req->input('about');
            $teacher->image = $req->input('image');
            $teacher->save();
    
            
            if ($req->has('custom_fields') && count($req->input('custom_fields')) > 0) {
                // Retrieve custom fields data from the request
                $customFieldsData = $req->input('custom_fields');

                
                $customFieldsData['teacher_id'] = $teacher->id;

                
                $customField = $teacher->customFields()->create(['fields' => $customFieldsData]);

                if (!$customField) {
                    throw new \Exception('Failed to create custom field.');
                }
            
}

    
            return response()->json(["Message" => "Teacher Registered Successfully", "Data" => $teacher, "Custom Fields"=>$customField]);
        } catch (\Exception $e) {
            return response()->json(['Error' => $e->getMessage()]);
        }
    }
    public function allteachers()
    {
        $teachers = Teacher::with('customfields')->get();
    
        $formattedTeachers = $teachers->map(function ($teacher) {
            return [
                'teacher_id' => $teacher->teacher_id, 
                'email' => $teacher->email,
                'fullname' => $teacher->fullname,
                'phonenumber' => $teacher->phonenumber,
                'qualification' => $teacher->qualification,
                'about' => $teacher->about,
                'created_at' => $teacher->created_at,
                'updated_at' => $teacher->updated_at,
                'customfields' => $teacher->customfields->map(function ($customfields) {
                    return json_decode($customfields->fields, true);
                })->all()
            ];
        });
    
        return response()->json(["Status" => "Success", "Details" => $formattedTeachers]);
    }
    
}    