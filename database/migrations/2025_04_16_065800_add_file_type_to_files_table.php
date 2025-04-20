<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFileTypeToFilesTable extends Migration
{
    public function up()
    {
        // Remove the redundant addition of the 'file_type' column.
        // Schema::table('files', function (Blueprint $table) {
        //     $table->string('file_type')->nullable()->after('nama_file');
        // });
    }

    public function down()
    {
        // Optionally, you can leave this empty or remove the column if needed.
        // Schema::table('files', function (Blueprint $table) {
        //     $table->dropColumn('file_type');
        // });
    }
}
