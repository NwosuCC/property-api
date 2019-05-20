<?php

use App\Role;
use App\User;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyTablesStructure extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::table('houses', function (Blueprint $table) {
      $table->dropForeign(['user_id']);
      $table->dropColumn(['user_id', 'state']);
    });

    Schema::table('house_user', function (Blueprint $table) {
      $table->dropColumn(['action']);
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::table('house_user', function (Blueprint $table) {
      $table->unsignedTinyInteger('action')->after('user_id');
    });

    Schema::table('houses', function (Blueprint $table) {
      $table->unsignedInteger('user_id')
        ->default( User::model()->hasRole(Role::ADMIN)->first()->id )
        ->after('id');

      $table->unsignedTinyInteger('state')
        ->default(1)
        ->after('title');

      $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade');
    });
  }
}
