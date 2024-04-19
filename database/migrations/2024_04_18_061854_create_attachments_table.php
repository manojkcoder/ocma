<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void{
        Schema::create('attachments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('physician_id')->nullable();
            $table->text('document_name')->nullable();
            $table->text('document_path')->nullable();
            $table->string('document_type')->nullable();
            $table->string('document_size')->nullable();
            $table->text('document_caption')->nullable();
            $table->timestamps();
            $table->foreign('physician_id')->references('id')->on('physicians')->onDelete('cascade')->onUpdate('cascade');
        });
    }
    public function down(): void{
        Schema::dropIfExists('attachments');
    }
};