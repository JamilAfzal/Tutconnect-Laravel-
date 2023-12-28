<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('teachers', function (Blueprint $table) {
            $table->id("teacher_id");
            
            $table->string("email",50);
            $table->string("fullname",50);
            $table->string("password");
            $table->bigInteger("phonenumber")->unsigned();
            $table->string("image")->nullable();
            $table->text("about")->nullable();
            $table->string("qualification")->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teachers');
    }
};
