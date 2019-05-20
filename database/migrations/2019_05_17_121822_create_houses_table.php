<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHousesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('houses', function (Blueprint $table) {
          $table->increments('id');
          $table->unsignedInteger('user_id');
          $table->unsignedInteger('category_id');
          $table->string('title');
          $table->unsignedTinyInteger('state')->default(1);
          $table->text('description');
          $table->string('slug')->nullable();
          $table->timestamps();
          $table->softDeletes()->nullable();

          $table->unique(['title', 'deleted_at']);

          $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade');

          $table->foreign('category_id')
                ->references('id')->on('categories')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('houses');
    }
}
