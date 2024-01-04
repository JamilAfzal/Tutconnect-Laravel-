<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Modules;


class Material extends Model
{
    use HasFactory;
    protected $fillable = ["title","content"];
    public function modules()
    {
        return $this->belongsTo(Modules::class);
    }
}
