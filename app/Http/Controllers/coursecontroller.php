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
                "course_image" => "image|mimes:jpeg,png,jpg,gif|max:2048",
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

            if ($request->has('course_image')) {
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
}
