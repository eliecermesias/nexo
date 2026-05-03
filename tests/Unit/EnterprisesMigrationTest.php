<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class EnterprisesMigrationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function enterprises_table_has_expected_columns()
    {
        $this->assertTrue(Schema::hasTable('enterprises'));
        $columns = [
            'id',
            'name',
            'nit',
            'phone',
            'email',
            'address',
            'website',
            'created_at',
            'updated_at',
        ];
        foreach ($columns as $column) {
            $this->assertTrue(
                Schema::hasColumn('enterprises', $column),
                "Missing column: $column"
            );
        }
    }
}
