<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rest_cache_records', function (Blueprint $table) {
			$table->id();
			$table->text("uri");
			$table->string("hash", 32)->unique();
			$table->text("filename");
			$table->integer("size");
			$table->dateTime("created", 0);
			$table->dateTime("expires", 0);
			$table->integer("hits");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rest_cache_records');
    }
}
