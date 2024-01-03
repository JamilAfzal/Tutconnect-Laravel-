<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Teacher;

class Course extends Model
{
    use HasFactory;
    protected $primaryKey = "course_id";

    protected $fillable=["course_name","course_duration","course_desc","course_fee","course_image","course_obj","start_date","end_date"];
    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }
    public function coursecustomfields()
    {
        return $this->hasMany(CourseCustomFields::class, 'course_id');
    }
    public function modules(){
        return $this->hasMany(Modules::class , "course_id");
    }
}

