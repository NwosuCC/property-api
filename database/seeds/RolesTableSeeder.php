<?php

use App\Role;
use App\User;
use Illuminate\Support\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      DB::table('roles')->insert(
        [
          [
            'name' => 'Auth',
            'slug' => 'admin',
            'description' => 'In charge',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
          ],
          [
            'name' => 'Tenant',
            'slug' => 'tenant',
            'description' => 'Rented a property',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
          ],
          [
            'name' => 'Applicant',
            'slug' => 'applicant',
            'description' => 'Indicated interest in property',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
          ]
        ]
      );

    }
}
