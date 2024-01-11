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
                    $material->content = base64_encode(file_get_contents($file));
                } elseif($extension == 'mp4' || $extension == "avi" || $extension =='mov'){
                    $path = $file->store('material_videos');
                    $material->content = base64_encode(file_get_contents($file));

                }
                 else {
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
public function updatematerial(Request $req, $material_id){
    try{
     $material = Material::find($material_id);
     if(!$material){
        return response()->json(["Error"=>"No Error Found"]);

     }
     $validatedata = Validator::make($req->all(),[
        "title"=>"required"
     ]);
     $material->title=$validatedata['title']?? $req->title;
     if ($req->hasFile('content')) {
        $file = $req->file('content');
        $extension = $file->getClientOriginalExtension();

        if (in_array($extension,['pdf','jpeg','jpg','png','txt'])) {
            $path = $file->store('material_files');
            $material->content = base64_encode(file_get_contents($file));
            
        }elseif(in_array($extension,['mp4','avi','mov'])){
            $path = $file->store('material_videos');
            $material->content = base64_encode(file_get_contents($file));
        } 
        
         else {
            return response()->json(["error" => "Invalid file format. Please upload a PDF, PNG, JPEG, or text file."], 400);
        
        }
        
        $material->save();
        return response()->json(["Message" => "Material Updated Successfully"]);
    }
}
    catch(\Exception $e){
        return response()->json(["error" => $e->getMessage()]);
    }
}
}  

