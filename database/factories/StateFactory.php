<?php

namespace Database\Factories;

use App\Models\Country;
use App\Models\State;
use App\Support\DivipolaMunicipalities;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<State>
 */
class StateFactory extends Factory
{
    public function definition(): array
    {
        $municipality = fake()->unique()->randomElement($this->departments());

        return [
            'countries_Id' => $this->colombiaId(),
            'code' => $municipality['department_code'],
            'name' => $municipality['department_name'],
            'is_active' => true,
        ];
    }

    public function divipolaDepartment(array $municipality): static
    {
        return $this->state(fn (array $attributes) => [
            'countries_Id' => $this->colombiaId(),
            'code' => $municipality['department_code'],
            'name' => $municipality['department_name'],
            'is_active' => true,
        ]);
    }

    private function colombiaId(): int
    {
        return Country::query()->firstOrCreate(
            ['code' => 'CO'],
            ['name' => 'Colombia', 'is_active' => true],
        )->getKey();
    }

    private function departments(): array
    {
        $departments = [];

        foreach (DivipolaMunicipalities::fromOds() as $municipality) {
            $departments[$municipality['department_code']] = $municipality;
        }

        return array_values($departments);
    }
}
