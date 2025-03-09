<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFoldersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('folders', function (Blueprint $table) {
            $table->id('id_folder'); // Menambahkan kolom id dengan tipe data yang sesuai
            $table->string('nama');
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->foreign('parent_id')->references('id_folder')->on('folders')->onDelete('cascade');
            $table->string('icon_path')->nullable(); // Menghapus after('parent_id')
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
        Schema::dropIfExists('folders');
    }
}
