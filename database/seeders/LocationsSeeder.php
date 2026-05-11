<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\Country;
use App\Models\State;
use App\Support\DivipolaMunicipalities;
use Illuminate\Database\Seeder;

class LocationsSeeder extends Seeder
{
    public function run(): void
    {
        $country = Country::query()->updateOrCreate(
            ['code' => 'CO'],
            ['name' => 'Colombia', 'is_active' => true],
        );

        foreach (DivipolaMunicipalities::fromOds() as $municipality) {
            $state = State::query()
                ->where('countries_Id', $country->getKey())
                ->where(function ($query) use ($municipality) {
                    $query
                        ->where('code', $municipality['department_code'])
                        ->orWhere('name', $municipality['department_name']);
                })
                ->first();

            if (! $state) {
                $state = new State([
                    'countries_Id' => $country->getKey(),
                ]);
            }

            $state->fill([
                'code' => $municipality['department_code'],
                'name' => $municipality['department_name'],
                'is_active' => true,
            ])->save();

            $city = City::query()
                ->where('states_Id', $state->getKey())
                ->where(function ($query) use ($municipality) {
                    $query
                        ->where('code', $municipality['municipality_code'])
                        ->orWhere('name', $municipality['municipality_name']);
                })
                ->first();

            if (! $city) {
                $city = new City([
                    'states_Id' => $state->getKey(),
                ]);
            }

            $city->fill([
                'code' => $municipality['municipality_code'],
                'name' => $municipality['municipality_name'],
                'type' => $municipality['type'],
                'longitude' => $municipality['longitude'],
                'latitude' => $municipality['latitude'],
                'is_capital' => $municipality['is_capital'],
                'is_active' => true,
            ])->save();
        }

        City::query()->whereNull('code')->delete();
        State::query()->whereNull('code')->delete();
    }
}
