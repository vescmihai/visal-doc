<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePageVisitsTable extends Migration
{
    public function up(): void
    {
        Schema::create('page_visits', function (Blueprint $table) {
            $table->id();
            $table->string('page_url')->unique();
            $table->integer('visits')->default(0);
            $table->timestamps();
        });
        
    }

    public function down(): void
    {
        Schema::dropIfExists('page_visits');
    }
}
