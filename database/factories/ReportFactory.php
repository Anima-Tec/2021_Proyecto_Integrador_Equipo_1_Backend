<?php

namespace Database\Factories;

use App\Models\Report;
use App\Models\Place;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReportFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Report::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'date' => $this->faker->date(),
            'type_report' => $this->faker->randomElement(['opinión', 'denuncia']),
            'description' => $this->faker->paragraph(2),
            'assessment' => $this->faker->numberBetween(1, 5),
            'photo' => 'https://source.unsplash.com/random',
            'id_place' => Place::factory(),
        ];
    }
}
