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
        Schema::create('journals_sdgs', function (Blueprint $table) {
            $table->id();
            
            $table->unsignedBiginteger('id_journals')->unsigned();
            $table->unsignedBiginteger('id_sdgs')->unsigned();

            $table->foreign('id_journals')->references('id')
                ->on('journals')->onDelete('cascade');
            $table->foreign('id_sdgs')->references('id')
                ->on('sdgs')->onDelete('cascade');
                
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('journals_sdgs');
    }
};
