<?php

namespace Database\Factories;

use App\Models\Company;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Company>
 */
class CompanyFactory extends Factory
{
    protected $model = Company::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'social_name' => $this->faker->company(),
            'alias_name' => $this->faker->companySuffix(),

            'document_company' => $this->faker->cnpj(false),
            'document_company_secondary' => $this->faker->cnpj(false),

            'information' => $this->faker->sentence(),

            'status' => $this->faker->numberBetween(0, 1),

            // Address
            'zipcode' => $this->faker->postcode(),
            'street' => $this->faker->streetName(),
            'number' => $this->faker->buildingNumber(),
            'complement' => $this->faker->secondaryAddress(),
            'neighborhood' => $this->faker->citySuffix(),
            'state' => $this->faker->stateAbbr(),
            'city' => $this->faker->city(),

            // Contact
            'phone' => $this->faker->phoneNumber(),
            'cell_phone' => $this->faker->phoneNumber(),
            'whatsapp' => $this->faker->phoneNumber(),
            'telegram' => $this->faker->phoneNumber(),

            'additional_email' => $this->faker->safeEmail(),
            'email' => $this->faker->unique()->companyEmail(),
        ];
    }
}
