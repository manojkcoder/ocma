<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void{
        Schema::create('physicians',function(Blueprint $table){
            $table->id();
            $table->boolean('firstans')->nullable();
            $table->boolean('secondans')->nullable();
            $table->boolean('thirdans')->nullable();
            $table->boolean('fourthans')->nullable();
            $table->enum('designation',["do","md"])->nullable();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('full_name')->nullable();
            $table->string('email')->nullable();
            $table->string('medical_license')->nullable();
            $table->string('date_issue')->nullable();
            $table->string('primary_specialty')->nullable();
            $table->string('organization')->nullable();
            $table->string('address1')->nullable();
            $table->string('address2')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('zip')->nullable();
            $table->string('phone')->nullable();
            $table->string('fax')->nullable();
            $table->string('primary_hospital_affiliation')->nullable();
            $table->string('special_interest')->nullable();
            $table->string('signature')->nullable();
            $table->longText('leadership_positions')->nullable();
            $table->longText('elected_members')->nullable();
            $table->longText('teaching_activities')->nullable();
            $table->longText('medical_educations')->nullable();
            $table->longText('medical_advances')->nullable();
            $table->longText('participation_activities')->nullable();
            $table->longText('other_activities')->nullable();
            $table->enum('form_status',['saved','submitted'])->default("saved");
            $table->boolean('mark_complete')->default(0);
            $table->text('scan_application_url')->nullable();
            $table->longText('comments')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
    public function down(): void{
        Schema::dropIfExists('physicians');
    }
};