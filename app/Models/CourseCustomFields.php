<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseCustomFields extends Model
{
    use HasFactory;
    protected $fillable = ["fields"];
    protected $casts = [
        'fields' => 'json',
    ];
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

}
