<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
          $table->increments('id');
          $table->unsignedInteger('user_id');
          $table->string('name');
          $table->string('description');
          $table->string('slug')->nullable();
          $table->timestamps();
          $table->softDeletes()->nullable();

          $table->unique(['name', 'deleted_at']);

          $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('categories');
    }
}
