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
        Schema::create('course_levels', function (Blueprint $table) {
            $table->id();

            $table->bigInteger('course_id')->unsigned();
            $table->string('level');
            $table->string('video_link')->nullable();
            $table->string('video_title')->nullable();

            $table->string('pass_mark');
            $table->string('reward_amount');
            $table->timestamps();

            $table->foreign('course_id')->references('id')->on('courses')
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
        Schema::dropIfExists('mm_course_levels');
    }
};
