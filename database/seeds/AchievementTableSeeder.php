<?php

use Illuminate\Database\Seeder;

class AchievementTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $typeName = 'Email verification';
        $type = DB::table("types")
            ->select(DB::raw('*'))
            ->where('name', '=', $typeName)
            ->get();
        $typeId = count($type) > 0 ? $type[0]->id : null;
        if(is_null($typeId)){
            echo "Error, no types in database! \n";
            die();
        }
        DB::table("achievements")->delete();
        DB::table('achievements')->insert([
            'name' => 'Email Bevestigd',
            'amount' => 0,
            'type_id' => $typeId
        ]);
    }
}
