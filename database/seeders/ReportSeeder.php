<?php

namespace Database\Seeders;

use App\Models\Report;
use App\Models\Place;
use App\Models\ReportPerson;
use App\Models\Person;
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
            ->count(100)
            ->has(
                Report::factory()
                    ->has(Place::factory()->count(10))
                    ->count(100)
            )
            ->create();
    }
}
