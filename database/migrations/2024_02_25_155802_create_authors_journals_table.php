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
        Schema::create('authors_journals', function (Blueprint $table) {
            $table->id();

            $table->unsignedBiginteger('id_authors')->unsigned();
            $table->unsignedBiginteger('id_journals')->unsigned();

            $table->foreign('id_authors')->references('id')
                ->on('authors')->onDelete('cascade');
            $table->foreign('id_journals')->references('id')
                ->on('journals')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('authors_journals');
    }
};
