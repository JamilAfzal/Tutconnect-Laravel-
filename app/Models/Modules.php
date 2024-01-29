<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Course;
use App\Models\Material;
use App\Http\Controllers\modulecontroller;

class Modules extends Model
{
    use HasFactory;
    protected $primaryKey = "module_id";
    protected $fillable=["module_name","module_desc","start_date","end_date"];
    public function course()
    {
        return $this->belongsTo(Course::class);
    }
    public function material()
    {
        return $this->hasMany(Material::class);
    }
}
