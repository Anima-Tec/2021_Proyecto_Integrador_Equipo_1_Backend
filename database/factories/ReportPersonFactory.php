<?php

namespace Database\Factories;

use App\Models\Place;
use App\Models\Report;
use App\Models\ReportPerson;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReportPersonFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ReportPerson::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'id_person' => $this->faker->numberBetween(1, User::count()),
            'id_place' => Place::factory(),
            'id_report' => Report::factory(),
        ];
    }
}
