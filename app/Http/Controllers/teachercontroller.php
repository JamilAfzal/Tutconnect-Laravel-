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
                'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                
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
            if ($req->has('image')) {
                // Get the base64-encoded image data from the request
                $imageData = $req->input('image');
            
                
                $imageData = str_replace('data:image/jpeg;base64,', '', $imageData);
            
                // Replace spaces with '+' as some base64 strings may have whitespace
                $imageData = str_replace(' ', '+', $imageData);
            
                // Decode the base64 string into binary data
                $imageBinary = base64_decode($imageData);
            
                // Generate a unique filename for the image
                $imagePath = 'teacher_images/' . uniqid() . '.jpg';
            
                // Store the binary data as a file in the 'public' disk using Laravel Storage
                Storage::disk('public')->put($imagePath, $imageBinary);
            
                // Save the image path to the 'image' attribute of the Teacher model
                $teacher->image = $imagePath;
            }
            
            $teacher->save();
    
            
            if ($req->has('custom_fields') && count($req->input('custom_fields')) > 0) {
                // Retrieve custom fields data from the request
                $customFieldsData = $req->input('custom_fields');

                
                $customFieldsData['teacher_id'] = $teacher->teacher_id;

                
                $customField = $teacher->customFields()->create(['fields' => json_encode($customFieldsData)]);

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
        $customFields = $teacher->customfields->map(function ($customfields) {
            
            $decodedFields = is_array($customfields->fields) ? $customfields->fields : json_decode($customfields->fields, true);
            return $decodedFields;
        })->all();

        
        foreach ($customFields as &$fields) {
            unset($fields['teacher_id']);
        }

        return [
            'teacher_id' => $teacher->teacher_id,
            'email' => $teacher->email,
            'fullname' => $teacher->fullname,
            'phonenumber' => $teacher->phonenumber,
            'qualification' => $teacher->qualification,
            'about' => $teacher->about,
            'created_at' => $teacher->created_at,
            'updated_at' => $teacher->updated_at,
            'customfields' => $customFields,
        ];
    });

    return response()->json(["Status" => "Success", "Details" => $formattedTeachers]);
}
    public function deleteteacher($teacher_id){
        try{$teacher = Teacher::find($teacher_id);
        if(!$teacher){
            return response()->json(["Status"=>"Failed","Message"=>"No Teacher Found"]);
        }
        $teacher->customfields()->delete();
        $teacher->delete();
        return response()->json(["Message"=>"Teacher Has Been Deleted"]);
    } catch (\Exception $e) {
        return response()->json(['Error' => $e->getMessage()]);
}
}   
     public function showteacher($teacher_id)
     {
        try{
            $teacher= Teacher::find($teacher_id);
            if(!$teacher){
                return response()->json(["Status"=>"Failed","Message"=>"No Teacher Found"]);
            }
            
            return response()->json(["Status" => "Success", "Message" => "Showing the Teacher Details","Data"=>$teacher,]);
        } catch (\Exception $e){
            return response()->json(['Error' => $e->getMessage()]);
        }
     }
    }
        