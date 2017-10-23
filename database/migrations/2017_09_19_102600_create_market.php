<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMarket extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 자유게시판
        Schema::create('market', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->enum('status', ['판매중', '판매완료']);
            $table->string('subject')->comment('제목');
            $table->unsignedInteger('price')->comment('가격');
            $table->longText('content')->comment('상세설명');
            $table->unsignedInteger('hits')->default(0)->comment('조회수');
            $table->boolean('pin')->default(false)->comment('상단 고정 여부');
            $table->boolean('open')->default(true)->comment('공개 여부');
            $table->softDeletes();
            $table->timestamps();

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
        Schema::dropIfExists('freeboard');
    }
}
