<?php

use Illuminate\Database\Seeder;

class HousesTableSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
      // ToDo: run this in CategorySeeder
      factory(App\House::class, 5)->create();
    }
}
