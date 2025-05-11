<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ParetoProblem extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('main_menus', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->timestamps();
        });
        Schema::create('menu_sections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('main_menu_id')->constrained('main_menus')->onDelete('cascade');
            $table->string('nama');
            $table->timestamps();
        });
        Schema::create('menu_brands', function (Blueprint $table) {
            $table->id();
            $table->foreignId('menu_section_id')->constrained('menu_sections')->onDelete('cascade');
            $table->string('nama');
            $table->timestamps();
        });
        Schema::create('menu_files', function (Blueprint $table) {
            $table->id();
            $table->foreignId('menu_brand_id')->constrained('menu_brands')->onDelete('cascade');
            $table->string('judul');
            $table->text('deskripsi')->nullable();
            $table->string('path'); // simpan path ke file/gambar
            $table->enum('tipe', ['file', 'image']);
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
        //
    }
}
