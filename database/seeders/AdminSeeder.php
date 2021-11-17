<?php

namespace Database\Seeders;

use App\Models\ReportDeleted;
use App\Models\Admin;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Person;
use App\Models\Report;
use Illuminate\Support\Arr;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::factory()->create();

        Admin::create([
            'id' => $user->id
        ]);

        $report = Report::create([
            'date' => date("Y-m-d H:i:s"),
            'type_report' => Arr::random(['opinión', 'denuncia']),
            'description' => 'paragraph',
            'assessment' => 1,
            'photo' => 'https://source.unsplash.com/random',
            'id_place' => 1,
        ]);

        ReportDeleted::create([
            'id_person' => mt_rand(1, Person::count()),
            'id_report' => $report->id,
            'id_place' => $report->id_place,
            'id_admin' => $user->id,
        ]);
    }
}
