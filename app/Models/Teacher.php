<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Course;

class Teacher extends Model
{
    use HasFactory;
    protected $primaryKey = 'teacher_id';

    protected $fillable = [
        "email", "fullname", "password", "phonenumber","about","qualification","image"
    ];
    protected $casts = [
        'phonenumber' => 'string'
    ];

    public function customfields()
    {
        return $this->hasMany(customfields::class,"teacher_id");
    }
    public function course()
{
    return $this->hasMany(Course::class, 'teacher_id');
}
}
