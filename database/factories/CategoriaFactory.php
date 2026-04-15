<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Categoria>
 */
class CategoriaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'nombre' => $this->faker->words(2, true), // Genera un nombre aleatorio con dos palabras.
            'descripcion' => $this->faker->optional()->sentence(10), // Puede ser nulo o una descripción aleatoria.
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
