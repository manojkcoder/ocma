<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void{
        Schema::create('reviews',function(Blueprint $table){
            $table->id();
            $table->unsignedBigInteger('physician_id')->nullable();
            $table->enum('type',['same','different'])->nullable();
            $table->string('reviewer_name')->nullable();
            $table->string('primary_specialty')->nullable();
            $table->string('phone')->nullable();
            $table->text('signature')->nullable();
            $table->boolean('is_physician_care')->nullable();
            $table->text('evaluation_ans1')->nullable();
            $table->text('evaluation_ans2')->nullable();
            $table->integer('rating_community')->nullable();
            $table->integer('rating_judgement')->nullable();
            $table->integer('rating_healthcare')->nullable();
            $table->integer('rating_patients')->nullable();
            $table->integer('rating_skills')->nullable();
            $table->text('scan_evalutaion_url')->nullable();
            $table->timestamps();
            $table->foreign('physician_id')->references('id')->on('physicians')->onDelete('cascade')->onUpdate('cascade');
        });
    }
    public function down(): void{
        Schema::dropIfExists('reviews');
    }
};