<?php

namespace Database\Seeders;

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
        Place::factory()->count(100)->create();
        Report::factory()->count(100)->create();
        ReportPerson::factory()->count(100)->create();
    }
}
