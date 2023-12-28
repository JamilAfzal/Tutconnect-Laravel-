<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Student; 
use Illuminate\Support\Facades\Validator;
class student extends Model
{
    use HasFactory;
    protected $primaryKey = 'student_id';
    protected $fillable =[
        "email", "fullname", "password", "phonenumber","image"
    ];
    
}
