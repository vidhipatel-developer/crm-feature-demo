<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contacts', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->string('phone');
            $table->enum('gender', ['Male', 'Female', 'Prefer Not To Say']);
            $table->string('company')->nullable();
            $table->date('birthday')->nullable();
            $table->string('profile_image')->nullable();
            $table->json('custom_fields')->nullable();
            $table->enum('status', ['active', 'merged'])->default('active');
            $table->unsignedBigInteger('merged_into')->nullable();
            $table->timestamps();
            
            $table->foreign('merged_into')->references('id')->on('contacts')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contacts');
    }
};