<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePositionsTable extends Migration
{
    public function up()
    {
        Schema::create('positions', function (Blueprint $table) {
            $table->id(); // Primary key (ID of the position)
            $table->string('name')->unique(); // Position name (unique)
            $table->unsignedBigInteger('reports_to')->nullable(); // Reports to (references another position)
            $table->foreign('reports_to')->references('id')->on('positions')->onDelete('cascade'); // Foreign key reference
            $table->softDeletes(); // Soft delete
            $table->timestamps(); // Created and updated timestamps
        });
    }

    public function down()
    {
        Schema::dropIfExists('positions');
    }
}