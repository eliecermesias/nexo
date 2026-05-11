<?php

namespace Database\Factories;

use App\Models\City;
use App\Models\Country;
use App\Models\State;
use App\Support\DivipolaMunicipalities;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<City>
 */
class CityFactory extends Factory
{
    public function definition(): array
    {
        $municipality = fake()->randomElement(DivipolaMunicipalities::fromOds());

        return [
            'states_Id' => $this->stateId($municipality),
            'code' => $municipality['municipality_code'],
            'name' => $municipality['municipality_name'],
            'type' => $municipality['type'],
            'longitude' => $municipality['longitude'],
            'latitude' => $municipality['latitude'],
            'is_capital' => $municipality['is_capital'],
            'is_active' => true,
        ];
    }

    public function capital(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_capital' => true,
        ]);
    }

    private function stateId(array $municipality): int
    {
        return State::query()->firstOrCreate(
            ['code' => $municipality['department_code']],
            [
                'countries_Id' => Country::query()->firstOrCreate(
                    ['code' => 'CO'],
                    ['name' => 'Colombia', 'is_active' => true],
                )->getKey(),
                'name' => $municipality['department_name'],
                'is_active' => true,
            ],
        )->getKey();
    }
}
