<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void{
        Schema::create('final_remarks',function(Blueprint $table){
            $table->id();
            $table->unsignedBigInteger('physician_id')->nullable();
            $table->unsignedBigInteger('member_id')->nullable();
            $table->boolean('flag1')->nullable();
            $table->boolean('flag2')->nullable();
            $table->boolean('flag3')->nullable();
            $table->boolean('flag4')->nullable();
            $table->boolean('flag5')->nullable();
            $table->boolean('flag6')->nullable();
            $table->text('description1')->nullable();
            $table->text('description2')->nullable();
            $table->text('description3')->nullable();
            $table->text('description4')->nullable();
            $table->text('description5')->nullable();
            $table->text('description6')->nullable();
            $table->text('remark')->nullable();
            $table->timestamps();
            $table->foreign('physician_id')->references('id')->on('physicians')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('member_id')->references('id')->on('committees')->onDelete('cascade')->onUpdate('cascade');
        });
    }
    public function down(): void{
        Schema::dropIfExists('final_remarks');
    }
};