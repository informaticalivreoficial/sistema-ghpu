<?php

namespace Database\Factories;

use App\Models\Ocorrencia;
use App\Models\User;
use App\Models\Company;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Ocorrencia>
 */
class OcorrenciaFactory extends Factory
{
    protected $model = Ocorrencia::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {        
        return [
            'user_id' => User::inRandomOrder()->first()?->id ?? User::factory(),
            'company_id' => Company::inRandomOrder()->first()?->id ?? Company::factory(),

            'title' => $this->faker->sentence(),
            'content' => $this->faker->paragraphs(3, true),

            'status' => $this->faker->numberBetween(0, 1),
            'views' => $this->faker->numberBetween(0, 500),

            'update_user_id' => User::inRandomOrder()->first()?->id ?? null,
        ];        
    }
}
