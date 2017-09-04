<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBoardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title')->comment('게시판 제목');
            $table->boolean('open')->default(true)->comment('공개 여부');
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('articles', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('category_id');
            $table->unsignedInteger('user_id');
            $table->string('subject')->comment('제목');
            $table->text('content')->comment('내용');
            $table->unsignedInteger('hits')->default(0)->comment('조회수');
            $table->boolean('pin')->default(false)->comment('상단 고정 여부');
            $table->boolean('open')->default(true)->comment('공개 여부');
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('category_id')
                  ->references('id')->on('categories')
                  ->onDelete('cascade')->onUpdate('cascade');

            $table->foreign('user_id')
                  ->references('id')->on('users')
                  ->onDelete('cascade')->onUpdate('cascade');
        });

        Schema::create('comments', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('article_id');
            $table->unsignedInteger('parent_id')->nullable();
            $table->unsignedInteger('user_id');
            $table->text('content')->comment('내용');
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('article_id')
                  ->references('id')->on('articles')
                  ->onDelete('cascade')->onUpdate('cascade');

            $table->foreign('user_id')
                  ->references('id')->on('users')
                  ->onDelete('cascade')->onUpdate('cascade');
        });

        Schema::create('attachments', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('article_id');
            $table->string('name')->commnet('파일이름');
            $table->string('path')->commnet('경로');
            $table->string('mime', 100)->collation('ascii_bin');
            $table->unsignedInteger('size')->commnet('파일크기');
            $table->timestamps();

            $table->foreign('article_id')
                  ->references('id')->on('articles')
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

        Schema::dropIfExists('categories');
        Schema::dropIfExists('boards');
        Schema::dropIfExists('comments');
        Schema::dropIfExists('attachments');

        Schema::enableForeignKeyConstraints();
    }
}
