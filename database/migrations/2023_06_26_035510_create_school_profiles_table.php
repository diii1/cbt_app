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
        Schema::create('school_profiles', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('contact');
            $table->string('email');
            $table->string('address')->nullable();
            $table->string('district')->nullable();
            $table->string('regency')->nullable();
            $table->string('province')->nullable();
            $table->string('acreditation');
            $table->string('logo');
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
        Schema::dropIfExists('school_profiles');
    }
};
