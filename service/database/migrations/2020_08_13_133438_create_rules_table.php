<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rest_cache_rules', function (Blueprint $table) {
            $table->id();
			$table->enum("behaviour", ["exclude", "include"])->default("exclude");
			$table->string("pattern", 2048)->default("/");
			$table->tinyInteger("regex")->default(0);
			$table->integer("priority")->default(10);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rest_cache_rules');
    }
}
