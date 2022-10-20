<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class StatesTableSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $path = public_path('sql/states.sql');
        DB::unprepared(file_get_contents($path));
        $this->command->info('States table seeded!');


    }
}
