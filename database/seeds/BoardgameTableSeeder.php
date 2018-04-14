<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BoardgameTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $games = file_get_contents(__DIR__."/../../resources/data/games_resized.json");
        $games = json_decode($games, true);
        $categories = file_get_contents(__DIR__."/../../resources/data/categories.json");
        $categories = json_decode($categories, true);
        $formattedCategories = [];
        foreach ($categories as $category) {
            $categoryDb = DB::table("categories")
                ->select(DB::raw('*'))
                ->where('name', '=', $category)
                ->get();

            if ($categoryDb->count() === 0) {
                $formattedCategories[] = [
                    "name" => $category
                ];
            }
        }
        if (count($formattedCategories) > 0) {
            DB::table("categories")->insert($formattedCategories);
        }
        $formattedGames = [];
        foreach ($games as $game) {
            $gameDb = DB::table("boardgames")
                ->select(DB::raw('*'))
                ->where('name', '=', $game)
                ->get();
            if ($gameDb->count() === 0) {
                $category = DB::table("categories")
                    ->select(DB::raw('*'))
                    ->where('name', '=', $game["category"])
                    ->get();
                $categoryId = count($category) > 0 ? $category[0]->id : null;
                if(is_null($categoryId)){
                    echo "Error, no categories in database! \n";
                    die();
                }
                $formattedGames[] = [
                    "name" => $game["name"],
                    "description" => html_entity_decode($game["description"]),
                    "min_players" => $game["minPlayers"],
                    "max_players" => $game["maxPlayers"],
                    "avg_time" => $game["playingTime"],
                    "image" => $game["image"],
                    "category_id" => $categoryId
                ];
            }
        }
        DB::table("boardgames")->delete();
        if (count($formattedGames) > 0) {
            DB::table("boardgames")->insert($formattedGames);
        }
    }
}