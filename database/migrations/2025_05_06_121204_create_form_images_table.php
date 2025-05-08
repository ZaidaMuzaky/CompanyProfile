<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFormImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('form_images', function (Blueprint $table) {
             $table->id();
        $table->string('form_id')->nullable(); // bisa ID dari spreadsheet
        $table->string('section'); // contoh: 'jadwal', 'inventaris', dst.
        $table->string('image_path'); // lokasi gambar di server
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
        Schema::dropIfExists('form_images');
    }
}
