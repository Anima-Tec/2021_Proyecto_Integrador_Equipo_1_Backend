<?php

namespace Database\Seeders;

use App\Models\Report;
use App\Models\Place;
use App\Models\ReportPerson;
use GuzzleHttp\Promise\Create;
use Illuminate\Database\Seeder;

class ReportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ReportPerson::factory()
            ->has(Place::factory()
                ->has(Report::factory()->count(10))
                ->count(20))
            ->create();
    }
}
