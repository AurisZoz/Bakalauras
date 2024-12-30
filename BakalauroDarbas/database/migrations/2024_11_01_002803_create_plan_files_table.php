<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('plan_files', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rehabilitation_plan_id')->constrained('rehabilitation_plans')->onDelete('cascade');
            $table->string('file_path');
            $table->string('original_file_name')->nullable();
            $table->boolean('is_deleted')->default(false); 
            $table->timestamps();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('plan_files');
    }
    
};
