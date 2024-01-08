<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use App\Models\Modules;
use App\Models\Material;

use Illuminate\Http\Request;

class modulecontroller extends Controller
{
 public function create_module(Request $req){
    try{
        $validator = Validator::make($req->all(),[
            'module_name'=> 'required',
            'start_date'=>"required",
            "end_date"=>'required'
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }
        $module = new Modules();
        $module->module_name= $req->input('module_name');
        $module->module_desc=$req->input('module_desc');
        $module->start_date= $req->input('start_date');
        $module->end_date= $req->input('end_date');

        $module->course_id = $req->input('course_id');
        $module->save();
        return response()->json(['Message'=>'Module has been Created','Details'=>$module]);
    }catch(\Exception $e){
        return response()->json(["error" => $e->getMessage()],);
    }
 }
 public function allmodules(){
    try {
        $modules = Modules::all();
        $formattedModules = [];

        foreach ($modules as $module) {
            $formattedModules[] = [
                'Module Name' => $module->module_name,
                'Module Desc' => $module->module_desc,
                'Start Date' => $module->start_date,
                'End Date' => $module->end_date
            ];
        }

        return $formattedModules;



    }catch(\Exception $e){
        return response()->json(["error" => $e->getMessage()],);
    }
 }
 public function showmodule($module_id){
    try{
        $module= Modules::find($module_id);
        if(!$module){
            return response()->json(['Message'=>'Module Not Found']);
        }
        return response()->json(["Status" => "Success", "Message" => "Showing the Module Details","Data"=>$module,]);
    }catch(\Exception $e){
        return response()->json(["error" => $e->getMessage()],);
    }
 }
 public function deletemodule($module_id){
    try{
        $module= Modules::find($module_id);
        if(!$module){
            return response()->json(['Message'=>'Module Not Found']);
        }
       
        $module->delete();
        return response()->json(['Message'=>'Module Has Been Deleted']);
       
    }catch(\Exception $e){
        return response()->json(["error" => $e->getMessage()],);
    }
 }
 public function updatemodule(Request $req, $module_id){
    try{$module = Module::find($module_id);
        if(!$module){
            return response()->json(["No Course Found"]);
    
        }
        $validatedata = Validator::make($req->all(),[
            "module_name" => "required|min:4|max:30"
    ]); 
    if($validatedata->fails()){
        return response()->json(["Error"=> $validatedata->errors()]);
    }
    $module->module_name= $validatedata['module_name']?? $module->module_name;
    $module->module_desc = $req->module_desc;
    $module->start_date = $req->start_date;
    $module->end_date = $req->end_date;
    $module->save();


 }catch(\Exception $e){}
 }
}

