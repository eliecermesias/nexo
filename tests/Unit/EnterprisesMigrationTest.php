<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class EnterprisesMigrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_enterprises_table_has_commercial_sql_columns(): void
    {
        $this->assertTrue(Schema::hasTable('enterprises'));

        $columns = [
            'Id',
            'document_types_Id',
            'document_number',
            'legal_name',
            'trade_name',
            'email',
            'phone',
            'address',
            'city',
            'state',
            'country',
            'tax_regime',
            'created_at',
            'updated_at',
        ];

        foreach ($columns as $column) {
            $this->assertTrue(
                Schema::hasColumn('enterprises', $column),
                "Missing column: $column"
            );
        }

        $this->assertFalse(Schema::hasColumn('enterprises', 'nit'));
        $this->assertFalse(Schema::hasColumn('enterprises', 'name'));
    }
}
