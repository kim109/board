<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Initalize extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 카테고리
        Schema::create('categories', function (Blueprint $table) {
            $table->increments('id');
            $table->string('table')->collation('ascii_general_ci')->commnet('적용 테이블명');
            $table->string('name')->commnet('카테고리 이름');
            $table->timestamps();

            $table->index('table');
        });

        // 댓글
        Schema::create('comments', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('commentable_id');
            $table->string('commentable_type')->collation('ascii_general_ci');
            $table->text('content')->comment('내용');
            $table->softDeletes();
            $table->timestamps();

            $table->index(['commentable_id', 'commentable_type']);
            $table->foreign('user_id')
                    ->references('id')->on('users')
                    ->onDelete('cascade')->onUpdate('cascade');
        });

        // 첨부파일
        Schema::create('attachments', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('attach_id')->nullable();
            $table->string('attach_type')->nullable()->collation('ascii_general_ci');
            $table->string('name')->commnet('파일이름');
            $table->string('path')->commnet('경로');
            $table->string('mime', 100)->collation('ascii_general_ci');
            $table->unsignedInteger('size')->commnet('파일크기');
            $table->timestamps();

            $table->index(['attach_id', 'attach_type']);
            $table->foreign('user_id')
                  ->references('id')->on('users')
                  ->onDelete('cascade')->onUpdate('cascade');
        });

        // 공지사항
        Schema::create('notices', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('category_id');
            $table->string('subject')->comment('제목');
            $table->longText('content')->comment('내용');
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

        // 자유게시판
        Schema::create('freeboards', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('category_id');
            $table->string('subject')->comment('제목');
            $table->longText('content')->comment('내용');
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

        // 사용 문의 Q&A
        Schema::create('supports', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('category_id');
            $table->string('subject')->comment('제목');
            $table->longText('question')->comment('질문');
            $table->longText('answer')->nullable()->comment('답변');
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

        // 질문
        Schema::create('questions', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('category_id');
            $table->enum('status', ['질문중', '답변완료'])->comment('상태');
            $table->string('subject')->comment('제목');
            $table->longText('content')->comment('내용');
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

        // 답변
        Schema::create('answers', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('question_id');
            $table->longText('content')->comment('내용');
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('user_id')
                  ->references('id')->on('users')
                  ->onDelete('cascade')->onUpdate('cascade');

            $table->foreign('question_id')
                  ->references('id')->on('questions')
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
        //
        Schema::dropIfExists('comments');
        Schema::dropIfExists('attachments');
        Schema::dropIfExists('categories');
        Schema::dropIfExists('notices');
        Schema::dropIfExists('freeboards');
        Schema::dropIfExists('supports');
    }
}
