<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Model\teacher;

class customfields extends Model
{
    use HasFactory;
    protected $fillable = ['fields'];

    protected $casts = [
        'fields' => 'array',
    ];
    public function teacher()
    {
        return $this->belongsTo(teacher::class);
    }
}
