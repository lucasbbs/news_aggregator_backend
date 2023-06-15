<?php

namespace Database\Seeders;

use App\Models\Sources;
use Illuminate\Database\Seeder;

class SourceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Sources::create(['name' => config('global.guardian')]);
        Sources::create(['name' => config('global.new_york_times')]);
        Sources::create(['name' => config('global.news_api')]);
    }
}
