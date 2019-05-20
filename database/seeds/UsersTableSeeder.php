<?php

use App\Role;
use App\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    public static $test_admin = 'claudie9@aol.com';
    public static $test_admin_role = Role::ADMIN;


    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
      $users = [
        [
          'uid' => str_random(\App\User::UID_LENGTH),
          'name' => 'Claudie',
          'email' => static::$test_admin,
          'email_verified_at' => now(),
          'password' => bcrypt('claudie9'),
          'remember_token' => str_random(10),
        ],
        [
          'uid' => str_random(\App\User::UID_LENGTH),
          'name' => 'Mark Bullain',
          'email' => 'mcbullain@yahoo.com',
          'email_verified_at' => now(),
          'password' => bcrypt('mcbullain'),
          'remember_token' => str_random(10),
        ],
        [
          'uid' => str_random(\App\User::UID_LENGTH),
          'name' => 'Jane Doe',
          'email' => 'jane@360world.com',
          'email_verified_at' => now(),
          'password' => bcrypt('jane'),
          'remember_token' => str_random(10),
        ]
      ];

      // Insert
      DB::table('users')->insert( $users );

      // Grant roles to seeded users
      foreach($users as $i => $user){

        /** @var User $existing_user */
        if($existing_user = User::where('email', $user['email'])->first()){

          if($role = Role::find($i + 1)){
            $existing_user->roles()->attach($role);
          }
        }
      }

    }
}
