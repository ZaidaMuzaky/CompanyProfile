<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class FileExcel extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
{
    Schema::create('cn_unit_file', function (Blueprint $table) {
        $table->id();
        $table->foreignId('cn_unit_id')->constrained()->onDelete('cascade');
        $table->string('file_path')->nullable();
        $table->string('file_name')->nullable();
        $table->string('file_type')->nullable();
        $table->timestamps();
    });
}

public function down()
{
    Schema::dropIfExists('cn_unit_file');
}

}
