<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubmenuImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('submenu_images', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('submenu_id');
            $table->string('image_path');
            $table->string('description')->nullable(); // jika ingin menambah deskripsi
            $table->timestamps();
        
            // Ubah ke id_submenu karena primary key-nya itu
            $table->foreign('submenu_id')->references('id_submenu')->on('submenus')->onDelete('cascade');
        });
        
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('submenu_images');
    }
}
