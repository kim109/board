<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSeminars extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 세미나 소식
        Schema::create('seminars', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('category_id');
            $table->string('subject')->comment('제목');
            $table->longText('content')->comment('내용');
            $table->unsignedInteger('thumbnail_id')->comment('대표 이미지');
            $table->unsignedInteger('hits')->default(0)->comment('조회수');
            $table->boolean('pin')->default(false)->comment('상단 고정 여부');
            $table->boolean('open')->default(true)->comment('공개 여부');
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('user_id')
                  ->references('id')->on('users')
                  ->onDelete('cascade')->onUpdate('cascade');

            $table->foreign('category_id')
                  ->references('id')->on('categories')
                  ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('seminars');
    }
}
