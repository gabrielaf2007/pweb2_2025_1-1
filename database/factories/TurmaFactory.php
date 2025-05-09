<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Turma>
 */
class TurmaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nome' => $this->faker->word, " " , $this->faker->year,
            'codigo' => $this->faker->unique()->numerify('TURMA - ####'),
            'curso_id' => \App\Models\Curso::all()->random()->id,
            'data_inicio' => $this->faker->date(),
            'data_fim' => $this->faker->date(),
        ];
    }
}
