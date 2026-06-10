<?php

namespace Database\Factories;

use App\Models\Quotation;
use App\Models\QuotationItem;
use App\Models\Service;
use App\Models\Tax;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<QuotationItem>
 */
class QuotationItemFactory extends Factory
{
    protected $model = QuotationItem::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $quantity = fake()->randomFloat(2, 1, 5);
        $unitPrice = fake()->numberBetween(100000, 500000);
        $discountRate = fake()->randomElement([0, 5, 10]);
        $taxRate = fake()->randomElement([0, 19]);
        $baseAmount = $quantity * $unitPrice;
        $discountAmount = $baseAmount * ($discountRate / 100);
        $lineTotal = ($baseAmount - $discountAmount) * (1 + ($taxRate / 100));

        return [
            'quotations_Id' => Quotation::factory(),
            'services_Id' => Service::factory(),
            'plans_Id' => null,
            'taxes_Id' => Tax::factory(),
            'description' => fake()->sentence(3),
            'quantity' => $quantity,
            'unit_price' => $unitPrice,
            'discount_rate' => $discountRate,
            'tax_rate' => $taxRate,
            'line_total' => $lineTotal,
            'sort_order' => 1,
        ];
    }
}
