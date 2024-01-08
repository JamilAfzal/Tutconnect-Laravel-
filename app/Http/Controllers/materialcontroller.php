<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use App\Models\Modules;
use App\Models\Material;



class materialcontroller extends Controller
{
    use Illuminate\Support\Facades\File;

    public function createMaterial(Request $req)
    {
        try {
            $validator = Validator::make($req->all(), [
                "title" => "required",
                "content" => "required|file|mimes:jpeg,png,jpg,pdf,txt|max:2048",
            ]);
    
            if ($validator->fails()) {
                return response()->json(["error" => $validator->errors()], 400);
            }
    
            $material = new Material();
            $material->title = $req->input('title');
    
            if ($req->hasFile('content')) {
                $file = $req->file('content');
                $extension = $file->getClientOriginalExtension();
    
                if ($extension == 'pdf' || $extension == 'txt' || $extension == 'png' || $extension == 'jpeg' || $extension == 'jpg') {
                    $path = $file->store('material_files');
                    $material->content = base64_encode(File::get($file));
                } else {
                    return response()->json(["error" => "Invalid file format. Please upload a PDF, PNG, JPEG, or text file."], 400);
                }
            } else {
                return response()->json(["error" => "File not provided."], 400);
            }
    
            $material->save();
    
            return response()->json(["Message" => "Material Created"]);
        } catch (\Exception $e) {
            return response()->json(["error" => $e->getMessage()],);
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
}
