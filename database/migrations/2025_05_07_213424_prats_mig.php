<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class PratsMig extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g. VHS, Non-VHS
            $table->timestamps();
        });
        Schema::create('subcategories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->string('name'); // e.g. FAW, XCMG
            $table->timestamps();
        });
        Schema::create('parts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subcategory_id')->constrained()->onDelete('cascade');
            $table->string('nama_sparepart');
            $table->string('type');
            $table->integer('qty_stock')->default(0);
            $table->enum('status', ['open', 'close'])->default('open');
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
         Schema::table('parts', function (Blueprint $table) {
        $table->dropForeign(['subcategory_id']); // Hapus foreign key constraint
        $table->dropColumn('subcategory_id'); // Hapus kolom
    });
    }
}
