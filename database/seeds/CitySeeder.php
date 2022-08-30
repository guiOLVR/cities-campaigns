<?php

use Illuminate\Database\Seeder;

class CitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $cities =  DB::table('cities');
        $cities->insert([
            'name' => 'Berlin'
        ]);

        $cities->insert([
            'name' => 'Denver'
        ]);

        $cities->insert([
            'name' => 'Tokyo'
        ]);

        $cities->insert([
            'name' => 'Oslo'
        ]);

        $cities->insert([
            'name' => 'Rio'
        ]);

        $cities->insert([
            'name' => 'Nairobi'
        ]);
    }
}