<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Faker\Factory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

        Model::unguard();

        $this->cleanup();
        $faker = Factory::create();


        //TODO create users for testing

        // Call seed roles and permissions command
        Artisan::call('blog:seed-roles-and-permissions');
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }

    private function cleanup()
    {
        $this->turnOffFKCheck();

        $tables = DB::select('SHOW TABLES');
        $keyName = 'Tables_in_' . (app()->environment() === 'testing' ? env('DB_DATABASE') . '_test' : env('DB_DATABASE'));

        foreach ($tables as $table) {
            DB::table($table->$keyName)->truncate();
        }

        $this->turnOnFKCheck();
    }

    private function turnOnFKCheck()
    {
        switch (DB::getDriverName()) {
            case 'mysql':
                DB::statement('SET FOREIGN_KEY_CHECKS=1');
                break;
            case 'sqlite':
                DB::statement('PRAGMA foreign_keys = ON');
                break;
        }
    }

    private function turnOffFKCheck()
    {
        switch (DB::getDriverName()) {
            case 'mysql':
                DB::statement('SET FOREIGN_KEY_CHECKS=0');
                break;
            case 'sqlite':
                DB::statement('PRAGMA foreign_keys = OFF');
                break;
        }
    }
}
