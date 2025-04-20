<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGooleFormsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('google_forms')) {
            Schema::create('google_forms', function (Blueprint $table) {
                $table->id();
                $table->string('title');
                $table->text('description');
                $table->enum('status', ['active', 'inactive']);
                $table->string('url');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('google_forms');
    }
}
