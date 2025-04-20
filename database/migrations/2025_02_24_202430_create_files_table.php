<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('files', function (Blueprint $table) {
            $table->id('id_file');
            $table->unsignedBigInteger('id_folder');
            $table->string('nama_file');
            $table->string('file_type')->nullable();
            $table->string('path');
            $table->unsignedBigInteger('id_user_upload')->nullable();
            $table->timestamps();

            $table->foreign('id_folder')->references('id_folder')->on('folders')->onDelete('cascade');
            $table->foreign('id_user_upload')->references('id_user')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('files');
    }
}
