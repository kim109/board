<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAttachment extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::defaultStringLength(150);

        Schema::create('attachments', function (Blueprint $table) {
            $table->increments('id');
            $table->morphs('attach');
            $table->string('name')->commnet('파일이름');
            $table->string('path')->commnet('경로');
            $table->string('mime', 100)->collation('ascii_bin');
            $table->unsignedInteger('size')->commnet('파일크기');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('attachments');
    }
}
