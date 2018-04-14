<?php

use Illuminate\Database\Seeder;

class TypeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table("types")->delete();

        DB::table('types')->insert([
            'name' => 'Email verification',
            'identifier' => 0
        ]);
    }
}
