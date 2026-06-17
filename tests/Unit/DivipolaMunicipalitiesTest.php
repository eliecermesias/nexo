<?php

namespace Tests\Unit;

use App\Support\DivipolaMunicipalities;
use Tests\TestCase;

class DivipolaMunicipalitiesTest extends TestCase
{
    public function test_it_loads_municipalities_from_the_default_ods_file(): void
    {
        $municipalities = DivipolaMunicipalities::fromOds();

        $this->assertNotEmpty($municipalities);
        $this->assertContains([
            'department_code' => '91',
            'department_name' => 'Amazonas',
            'municipality_code' => '91001',
            'municipality_name' => 'Leticia',
            'type' => 'Municipio',
            'longitude' => '-69.941721',
            'latitude' => '-4.198950',
            'is_capital' => true,
        ], $municipalities);
    }
}
