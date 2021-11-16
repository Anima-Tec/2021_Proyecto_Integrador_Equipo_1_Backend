<?php

namespace Database\Seeders;

use App\Models\Person;
use App\Models\Report;
use App\Models\Place;
use App\Models\ReportPerson;
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
        Place::factory()
            ->has(Report::factory()->count(5))
            ->count(10)
            ->create();

        $reports = Report::all();

        $reports->each(function ($report) {
            ReportPerson::create([
                'id_person' => mt_rand(1, Person::count()),
                'id_report' => $report->id,
                'id_place' => $report->id_place
            ]);
        });
    }
}
