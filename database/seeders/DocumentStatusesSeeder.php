<?php

namespace Database\Seeders;

use App\Models\DocumentStatus;
use Illuminate\Database\Seeder;

class DocumentStatusesSeeder extends Seeder
{
    public function run(): void
    {
        $statuses = [
            ['code' => 'created', 'name' => 'Created', 'description' => 'Document has been created and is pending commercial work.'],
            ['code' => 'draft', 'name' => 'Draft', 'description' => 'Document is being edited.'],
            ['code' => 'sent', 'name' => 'Sent', 'description' => 'Document was sent to the customer.'],
            ['code' => 'approved', 'name' => 'Approved', 'description' => 'Document was approved.'],
            ['code' => 'rejected', 'name' => 'Rejected', 'description' => 'Document was rejected.'],
            ['code' => 'expired', 'name' => 'Expired', 'description' => 'Document validity has expired.'],
            ['code' => 'cancelled', 'name' => 'Cancelled', 'description' => 'Document was cancelled.'],
            ['code' => 'partially_paid', 'name' => 'Partially Paid', 'description' => 'Document has partial payments.'],
            ['code' => 'paid', 'name' => 'Paid', 'description' => 'Document has been fully paid.'],
        ];

        foreach ($statuses as $status) {
            DocumentStatus::query()->updateOrCreate(
                ['code' => $status['code']],
                $status,
            );
        }
    }
}
