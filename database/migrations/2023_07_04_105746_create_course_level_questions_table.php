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
        Schema::create('course_level_questions', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('course_level_id')->unsigned();
            $table->string('question');
            $table->json('options');
            $table->json('answer');
            $table->timestamps();

            $table->foreign('course_level_id')->references('id')->on('course_levels')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mm_course_level_questions');
    }
};
