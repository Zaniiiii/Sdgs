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
        Schema::create('authors', function (Blueprint $table) {
            $table->id();
            $table->string('nidn',20)->default('-');
            $table->string('front_title',255)->default('-');
            $table->string('name',255);
            $table->string('back_title',255)->default('-');
            $table->char('gender',1)->default('-');
            $table->char('code',3)->default('-');
            $table->string('work_location',255)->default('-');
            $table->string('employment_status',255)->default('-');
            $table->string('position',255)->default('-');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('authors');
    }
};
