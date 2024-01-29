<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use App\Models\Modules;
use App\Models\Material;
use Illuminate\Support\Facades\File;



class materialcontroller extends Controller
{
   

    public function createMaterial(Request $req)
    {
        try {
            $validator = Validator::make($req->all(), [
                "title" => "required",
                "content" => "required|file|max:2048",
                "module_id" => "required|exists:modules,module_id", // Ensure module_id exists in the modules table
            ]);
    
            if ($validator->fails()) {
                return response()->json(["error" => $validator->errors()], 422);
            }
    
            $material = new Material();
            $material->title = $req->input('title');
            $material->module_id = $req->input('module_id'); // Assign module_id from the request
    
            if ($req->hasFile('content')) {
                $file = $req->file('content');
                $extension = $file->getClientOriginalExtension();
                $allowedExtensions = ['pdf', 'txt', 'png', 'jpeg', 'jpg', 'mp4', 'avi', 'mov'];
    
                if (in_array($extension, $allowedExtensions)) {
                    $path = $file->store('materials', 'public');

                    $material->content = $path;
                } else {
                    return response()->json(["error" => "Invalid file format. Please upload a PDF, PNG, JPEG, TXT, MP4, AVI, or MOV file."], 400);
                }
            } else {
                return response()->json(["error" => "File not provided."], 400);
            }
    
            $material->save();
    
            return response()->json(["Message" => "Material Created"]);
        } catch (\Exception $e) {
            return response()->json(["error" => $e->getMessage()], 500);
        }
    }
    

    
  public function deletematerial($material_id){
    try{$material = Material::find($material_id);
        if(!$material){
            return response()->json(["Message"=>"No Material Available"]);
        }
        $material->delete();
        return response()->json(["Message"=>"Material Has Been Deleted"]);
    }catch(\Exception $e){
        return response()->json(["error" => $e->getMessage()],);
    }
  }
  public function showallmaterial()
  {
    try{$materials = Material::all();
        $Allmaterial = $materials->map(function ($material) {
            return [
                'Material ID' => $material->material_id,
                'Title' => $material->title,
                'Content' => $material->content
            ];
        });
    
        return response()->json(["Message"=>"Showing All Material Details","Data"=>$Allmaterial]);
    }
    catch(\Exception $e){
        return response()->json(["error" => $e->getMessage()],);
        }
}
public function showmaterial($material_id){
    try{
        $material = Material::find($material_id);
      if(!$material){
        return response()->json(["Message"=>"No Material Found"]);
      }
      return response()->json(["Showing the Material Details","Data"=>$material]);
    }
    catch(\Exception $e){
        return response()->json(["error" => $e->getMessage()],);
    }
}
public function updatematerial(Request $req, $material_id)
{
    try {
        $material = Material::find($material_id);
        if (!$material) {
            return response()->json(["Error" => "Material not found"]);
        }

        $validator = Validator::make($req->all(), [
            "title" => "required",
            "content" => "required|file|max:2048" // Require file upload
        ]);

        if ($validator->fails()) {
            return response()->json(["error" => $validator->errors()], 400);
        }

        $material->title = $req->input('title');

        // Handle content update if file is provided
        if ($req->hasFile('content')) {
            $file = $req->file('content');
            $extension = $file->getClientOriginalExtension();

            $allowedExtensions = ['pdf', 'jpeg', 'jpg', 'png', 'txt', 'mp4', 'avi', 'mov'];

            if (!in_array($extension, $allowedExtensions)) {
                return response()->json(["error" => "Invalid file format. Please upload a PDF, PNG, JPEG, TXT, MP4, AVI, or MOV file."], 400);
            }

            // Store the new file in the 'materials' directory and update content
            $path = $file->store('materials', 'public');
            $material->content = $path;
        }

        // Update title
        $material->title = $validator->validated()['title']??$req->input('title');
        $material->content=$validator->validated()['content']??$req->input('content');
        $material->save();

        return response()->json(["Message" => "Material Updated Successfully"]);
    } catch (\Exception $e) {
        return response()->json(["error" => $e->getMessage()]);
    }
}
}



  

