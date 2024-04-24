<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void{
        Schema::create('member_assigns',function(Blueprint $table){
            $table->id();
            $table->unsignedBigInteger('physician_id')->nullable();
            $table->unsignedBigInteger('same_member_id')->nullable();
            $table->unsignedBigInteger('different_member_id')->nullable();
            $table->timestamps();
            $table->foreign('physician_id')->references('id')->on('physicians')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('same_member_id')->references('id')->on('committees')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('different_member_id')->references('id')->on('committees')->onDelete('cascade')->onUpdate('cascade');
        });
    }
    public function down(): void{
        Schema::dropIfExists('member_assigns');
    }
};