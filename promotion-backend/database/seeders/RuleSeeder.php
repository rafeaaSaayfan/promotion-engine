<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Rule;

class RuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Rule::create([
            'name' => 'Buy 5 Get 1 Free on SKU 123',
            'salience' => 10,
            'stackable' => false,
            'condition' => json_encode([
                'operator' => 'AND',
                'rules' => [
                    ['field' => 'line.productId', 'operator' => 'EQUALS', 'value' => 1],
                    ['field' => 'line.quantity', 'operator' => 'GTE', 'value' => 5]
                ]
            ]),
            'action' => json_encode([
                'type' => 'applyFreeUnits',
                'target' => 'line',
                'quantity' => 1
            ])
        ]);

        Rule::create([
            'name' => 'Tiered Discount SKU 456',
            'salience' => 20,
            'stackable' => true,
            'condition' => json_encode([
                'operator' => 'AND',
                'rules' => [
                    ['field' => 'line.productId', 'operator' => 'EQUALS', 'value' => 2],
                    ['field' => 'line.quantity', 'operator' => 'GTE', 'value' => 5]
                ]
            ]),
            'action' => json_encode([
                'type' => 'ifElse',
                'condition' => ['field' => 'line.quantity', 'operator' => 'LTE', 'value' => 9],
                'ifTrue' => ['type' => 'applyPercent', 'target' => 'line', 'percent' => 5],
                'ifFalse' => ['type' => 'applyPercent', 'target' => 'line', 'percent' => 10]
            ])
        ]);

        Rule::create([
            'name' => '20% off Electronics',
            'salience' => 15,
            'stackable' => true,
            'condition' => json_encode([
                'operator' => 'EQUALS',
                'field' => 'line.categoryId',
                'value' => 1
            ]),
            'action' => json_encode([
                'type' => 'applyPercent',
                'target' => 'line',
                'percent' => 20
            ])
        ]);

        Rule::create([
            'name' => '10% off for Restaurants',
            'salience' => 30,
            'stackable' => true,
            'condition' => json_encode([
                'operator' => 'EQUALS',
                'field' => 'customer.type',
                'value' => 'restaurants'
            ]),
            'action' => json_encode([
                'type' => 'applyPercent',
                'target' => 'line',
                'percent' => 10
            ])
        ]);

        Rule::create([
            'name' => '5% off apple.com Corporate',
            'salience' => 25,
            'stackable' => true,
            'condition' => json_encode([
                'operator' => 'ENDS_WITH',
                'field' => 'customer.email',
                'value' => '@apple.com'
            ]),
            'action' => json_encode([
                'type' => 'applyPercent',
                'target' => 'line',
                'percent' => 5
            ])
        ]);

        Rule::create([
            'name' => 'Flash Sale SKU 789',
            'salience' => 5,
            'stackable' => false,
            'condition' => json_encode([
                'operator' => 'AND',
                'rules' => [
                    ['field' => 'line.productId', 'operator' => 'EQUALS', 'value' => 3],
                    ['field' => 'now', 'operator' => 'LT_DATE', 'value' => '2025-07-01T00:00:00Z']
                ]
            ]),
            'action' => json_encode([
                'type' => 'applyPercent',
                'target' => 'line',
                'percent' => 25
            ])
        ]);

        Rule::create([
            'name' => 'Clearance Category Obsolete',
            'salience' => 40,
            'stackable' => true,
            'condition' => json_encode([
                'operator' => 'EQUALS',
                'field' => 'line.categoryId',
                'value' => 3
            ]),
            'action' => json_encode([
                'type' => 'applyPercent',
                'target' => 'line',
                'percent' => 50
            ])
        ]);

        Rule::create([
            'name' => 'Gold Tier Multiplier',
            'salience' => 35,
            'stackable' => true,
            'condition' => json_encode([
                'operator' => 'EQUALS',
                'field' => 'customer.loyaltyTier',
                'value' => 'gold'
            ]),
            'action' => json_encode([
                'type' => 'applyPercent',
                'target' => 'line',
                'percent' => 5
            ])
        ]);

        Rule::create([
            'name' => 'First Purchase SKU 555',
            'salience' => 12,
            'stackable' => true,
            'condition' => json_encode([
                'operator' => 'AND',
                'rules' => [
                    ['field' => 'line.productId', 'operator' => 'EQUALS', 'value' => 4],
                    ['field' => 'customer.ordersCount', 'operator' => 'EQUALS', 'value' => 0]
                ]
            ]),
            'action' => json_encode([
                'type' => 'applyPercent',
                'target' => 'line',
                'percent' => 15
            ])
        ]);

        Rule::create([
            'name' => 'City Promo',
            'salience' => 18,
            'stackable' => true,
            'condition' => json_encode([
                'operator' => 'EQUALS',
                'field' => 'customer.city',
                'value' => 'Jeddah'
            ]),
            'action' => json_encode([
                'type' => 'applyPercent',
                'target' => 'line',
                'percent' => 3
            ])
        ]);
    }
}