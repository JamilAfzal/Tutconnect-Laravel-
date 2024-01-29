<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use App\Model\CourseCustomFields;

class CourseController extends Controller
{
    public function course_register(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                "name" => "required|min:4|max:30",
                "duration" => "required",
                "fee" => "required",
                "start_date" => "required",
                "end_date" => "required",
                
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 400);
            }

            $course = new Course();
            $course->name = $request->input('name');
            $course->duration = $request->input('duration');
            $course->desc = $request->input("course_desc");
            $course->fee = $request->input("fee");
            $course->obj = $request->input("obj");
            $course->start_date = $request->input("start_date");
            $course->end_date = $request->input("end_date");
            // Have to remove this later
            $course->teacher_id = $request->input('teacher_id');

            if ($request->has('course_image') && !empty($request->input('course_image'))) {
                $imageData = $request->input('course_image');
                $imageData = str_replace('data:image/jpeg;base64,', '', $imageData);
                $imageData = str_replace(' ', '+', $imageData);
                $imageBinary = base64_decode($imageData);
                $imagePath = 'course_images/' . uniqid() . '.jpg';
                Storage::disk('public')->put($imagePath, $imageBinary);
                $course->course_image = $imagePath;
            }

            $course->save();

            if ($request->has('custom_fields') && count($request->input('custom_fields')) > 0) {
                $customFieldsData = $request->input('custom_fields');

                foreach ($customFieldsData as $customField) {
                    $validatorCustomField = Validator::make($customField, [
                        'name' => 'required|string|max:50|min:3',
                        'value' => 'required|string|max:255',
                    ]);

                    if ($validatorCustomField->fails()) {
                        return response()->json(['error' => 'Invalid custom field . Each custom field must have a valid name and value.'], 400);
                    }
                }

                $customFieldsData['course_id'] = $course->course_id;
                $customField = $course->courseCustomFields()->create(['fields' => json_encode($customFieldsData)]);

                if (!$customField) {
                    throw new \Exception('Failed to create custom field.');
                }

                $decodedFields = json_decode($customField->fields, true);
                return response()->json(["message" => "Course registered successfully", "data" => $course, "custom_fields" => $decodedFields]);
            }

            return response()->json(["message" => "Course registered successfully", "data" => $course]);
        } catch (\Exception $e) {
            return response()->json(["error" => $e->getMessage()], 500);
        }
    }
    public function showCourses()
{
    $courses = Course::with('CourseCustomFields')->get();

    $formattedCourses = $courses->map(function ($course) {
        $customFields = $course->CourseCustomFields->map(function ($coursecustomfields) {
            $decodedFields = is_array($coursecustomfields->fields) ? $coursecustomfields->fields : json_decode($coursecustomfields->fields, true);

            
            unset($decodedFields['course_id']);

            return $decodedFields;
        })->all();

        return [
            
            'Name' => $course->name,
            'Duration' => $course->duration,
            'Description' => $course->desc,
            'Fee' => $course->fee,
            'Image' => $course->course_image,
            'Obj' => $course->obj,
            'Starting date'=> $course->start_date,
            'End date'=> $course->end_date,
            'created_at' => $course->created_at,
            'updated_at' => $course->updated_at,
            'Custom fields' => $customFields
        ];
    });

    return response()->json(['courses' => $formattedCourses]);
}
public function showcourse($course_id)
{
    try {
        $course = Course::find($course_id);

        if (!$course) {
            return response()->json(["Message" => "No Course Found"]);
        }

        $customFields = [];

        if ($course->customfields) {
            $customFields = $course->customfields->map(function ($customfields) {
                $decodedFields = is_array($customfields->fields) ? $customfields->fields : json_decode($customfields->fields, true);
                return $decodedFields;
            })->all();

            foreach ($customFields as &$fields) {
                unset($fields['course_id']);
            }
        }

        $formattedCourse = [
            'Course ID' => $course->course_id,
            'Name' => $course->name,
            'Duration' => $course->duration,
            'Start Date' => $course->start_date,
            'End Date' => $course->end_date,
            'Fee' => $course->fee,
            'Objective' => $course->obj,
            'Description' => $course->desc,
            'Updated At' => $course->updated_at,
            'Custom Fields' => $customFields,
        ];

        return response()->json(["Status" => "Success", "Details" => $formattedCourse], 200, [], JSON_PRETTY_PRINT);
    } catch (\Exception $e) {
        return response()->json(['Error' => $e->getMessage()]);
    }
}


public function deletecourse($course_id){
    try{$course = Course::find($course_id);
    if(!$course){
        return response()->json(["Message"=>"No Course Found"]);
    }
    $course->modules()->each(function ($module) {
        $module->material()->delete();
        $module->delete();
    });

    
    $course->coursecustomfields()->delete();
    $course->delete();
    return response()->json(["Message"=>"Course Has Been Deleted"]);
}
    catch(\Exception $e){
        return response()->json(['Error' => $e->getMessage()]);
    }

}
public function updatecourse(Request $req, $course_id){
     try{
        $course = Course::find($course_id);
        if(!$course){
        return response()->json(["Message"=>"No Course Found"]);
    }
    $validatedata = Validator::make($req->all(),[
        "name" => "required|min:4|max:30"
]);
if($validatedata->fails()){
    return response()->json(["Error"=> $validatedata->errors()]);
}
    $course->name= $validatedata->validated()['name'] ?? $req->name;
    $course->duration = $req->duration;
    $course->desc = $req->desc;
    $course->fee = $req->fee;
    $course->obj= $req->obj;
    $course->start_date = $req->start_date;
    $course->end_date = $req->end_date;
    if ($req->has('course_image')) {
        
        $imageData = $req->input('course_image');

        // Remove the existing image file
        if (Storage::disk('public')->exists($course->course_image)) {
            Storage::disk('public')->delete($course->course_image);
        }

        $newImagePath = 'course_images/' . uniqid() . '.jpg';
        $imageBinary = base64_decode($imageData);

        Storage::disk('public')->put($newImagePath, $imageBinary);

        
        $course->course_image = $newImagePath;
    }

    
    $course->save();
    if ($req->has('custom_fields')  && count($req->input('custom_fields')) > 0) {
        $customFieldsData = $req->input('custom_fields');
        $customFieldsData['course_id'] = $course->course_id;
        $customField = $course->CourseCustomFields()->updateOrCreate([], ['fields' => json_encode($customFieldsData)]);
        return response()->json(["Message"=>"Updated","Data"=> $course,"CustomFields"=>customFieldsData]);
    }
    return response()->json(["Message"=>"Course has been updated", "Data"=>$course]);
    

     }catch(\Exception $e){
        return response()->json(['Error' => $e->getMessage()]);
     }
}

}
