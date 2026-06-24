<?php

namespace Database\Factories;

use App\Models\DocumentType;
use App\Models\Person;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Person>
 */
class PersonFactory extends Factory
{
    protected $model = Person::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $documentType = DocumentType::query()->firstOrCreate(
            ['code' => 'cc'],
            ['name' => 'Cédula de ciudadanía', 'is_active' => true],
        );

        return [
            'document_type_id' => $documentType->getKey(),
            'document_number' => fake()->unique()->numerify('##########'),
            'name' => fake()->firstName(),
            'lastname' => fake()->lastName(),
            'email' => fake()->unique()->safeEmail(),
            'phone' => fake()->numerify('300#######'),
            'address' => fake()->streetAddress(),
        ];
    }
}
