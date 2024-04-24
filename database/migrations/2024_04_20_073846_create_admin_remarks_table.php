<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void{
        Schema::create('admin_remarks',function(Blueprint $table){
            $table->id();
            $table->unsignedBigInteger('physician_id')->nullable();
            $table->boolean('flag1')->nullable();
            $table->boolean('flag2')->nullable();
            $table->boolean('flag3')->nullable();
            $table->boolean('flag4')->nullable();
            $table->boolean('flag5')->nullable();
            $table->boolean('flag6')->nullable();
            $table->boolean('flag7')->nullable();
            $table->timestamps();
            $table->foreign('physician_id')->references('id')->on('physicians')->onDelete('cascade')->onUpdate('cascade');
        });
    }
    public function down(): void{
        Schema::dropIfExists('admin_remarks');
    }
};