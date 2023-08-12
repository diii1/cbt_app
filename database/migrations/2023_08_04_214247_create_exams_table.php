<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exams', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('session_id');
            $table->foreign('session_id')->references('id')->on('sessions')->onUpdate('cascade')->onDelete('cascade');
            $table->unsignedBigInteger('subject_id');
            $table->foreign('subject_id')->references('id')->on('subjects')->onUpdate('cascade')->onDelete('cascade');
            $table->unsignedBigInteger('teacher_id');
            $table->foreign('teacher_id')->references('user_id')->on('teachers')->onUpdate('cascade')->onDelete('cascade');
            $table->unsignedBigInteger('class_id');
            $table->foreign('class_id')->references('id')->on('classes')->onUpdate('cascade')->onDelete('cascade');
            $table->string('subject_name');
            $table->string('teacher_name');
            $table->string('class_name');
            $table->string('title');
            $table->string('code');
            $table->integer('total_question');
            $table->date('date');
            $table->integer('token');
            $table->datetime('expired_token');
            $table->enum('type', ['pts', 'pas']);
            $table->boolean('is_active')->default(true);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('exams');
    }
};
