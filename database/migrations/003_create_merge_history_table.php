<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('merge_history', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('source_contact_id');
            $table->unsignedBigInteger('target_contact_id');
            $table->json('source_contact_data');
            $table->json('target_contact_data');
            $table->json('conflicts_resolved');
            $table->timestamp('merged_at');
            $table->timestamps();
            
            $table->foreign('target_contact_id')->references('id')->on('contacts');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('merge_history');
    }
};