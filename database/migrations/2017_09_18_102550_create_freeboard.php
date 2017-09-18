<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFreeboard extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 자유게시판
        Schema::create('freeboard', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->enum('category', ['일상', '유머', '치과경영', '의료윤리', '의료사고']);
            $table->string('subject')->comment('제목');
            $table->text('content')->comment('내용');
            $table->unsignedInteger('hits')->default(0)->comment('조회수');
            $table->boolean('pin')->default(false)->comment('상단 고정 여부');
            $table->boolean('open')->default(true)->comment('공개 여부');
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('user_id')
                  ->references('id')->on('users')
                  ->onDelete('cascade')->onUpdate('cascade');

            $table->index('category');
        });

        // 자유게시판 댓글
        Schema::create('freeboard_comments', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('freeboard_id');
            $table->unsignedInteger('parent_id')->nullable();
            $table->text('content')->comment('내용');
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('freeboard_id')
                  ->references('id')->on('freeboard')
                  ->onDelete('cascade')->onUpdate('cascade');

            $table->foreign('user_id')
                  ->references('id')->on('users')
                  ->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::disableForeignKeyConstraints();

        Schema::dropIfExists('freeboard_comments');
        Schema::dropIfExists('freeboard');

        Schema::enableForeignKeyConstraints();
    }
}
