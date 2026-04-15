<?php

namespace Database\Factories;

use App\Models\Persona;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Persona>
 */
class PersonaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

     
    protected $model = Persona::class;
    public function definition()
    {
        return [
            'razon_social' => $this->faker->company,
            'ciudad' => $this->faker->city,
            'calle' => $this->faker->streetName,
            'nro_vivienda' => $this->faker->buildingNumber,
            'telefono' => $this->faker->phoneNumber,
            'email' => $this->faker->unique()->safeEmail,
            'tipo_persona' => $this->faker->randomElement(['Natural', 'Jurídica']),  // O el valor que corresponda
        ];
    }
}
