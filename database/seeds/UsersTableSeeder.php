<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Define the userModel with data:
        $users = file_get_contents(__DIR__."/../../resources/data/user_model.json");
        $users = json_decode($users, true);
        // Define the userArray:
        $formattedUsers = [];
        // Looping through the users:
        foreach ($users as $user) {
            // Get the associated user:
            $userDb = DB::table('users')
                ->select(DB::raw('*'))
                ->where('username', '=', $user['username'])
                ->get();
            // Check if there is data:
            if (count($userDb->count()) > 0) {
                // Define the roles:
                $roles = DB::table('roles')->get();
                // Checks if there are roles:
                if (count($roles) == 0) {
                    echo "Error, no roles, please run RolesTableSeeder first! \n";
                    die();
                }
                // Define the role id:
                $roleId = DB::table('roles')
                    ->select(DB::raw('*'))
                    ->where('authority', '=', $user['authority'])
                    ->get()[0]->id;
                // Define the data:
                $formattedUsers[] = [
                    'username' => $user['username'],
                    'email' => $user['email'],
                    'password' => bcrypt('wachtwoord'),
                    'confirmed' => 1,
                    'image' => 'https://twirpz.files.wordpress.com/2015/06/twitter-avi-gender-balanced-figure.png?w=128',
                    'role_id' => $roleId
                ];
            }
        }
        DB::table("users")->delete();
        // Counting the User Object Array:
        if(count($formattedUsers) > 0) {
            // Insert data to the table:
            DB::table('users')->insert($formattedUsers);
        }
    }
}
