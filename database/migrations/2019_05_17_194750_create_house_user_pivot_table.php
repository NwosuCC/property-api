<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHouseUserPivotTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('house_user', function (Blueprint $table) {
      $table->increments('id');
      $table->integer('house_id');
      $table->integer('user_id');
      $table->unsignedTinyInteger('action');
      $table->timestamp('expires_at')->nullable();
      $table->timestamps();
      $table->softDeletes();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('house_user');
  }
}
