<?php

namespace Database\Factories;

use App\Models\Promotion;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Promotion>
 */
class PromotionFactory extends Factory
{
    protected $model = Promotion::class;

    public function definition(): array
    {
        $type = $this->faker->randomElement(['percentage', 'fixed_amount']);
        $value = $type === 'percentage'
            ? $this->faker->numberBetween(5, 50) // percent
            : $this->faker->randomFloat(2, 1, 100);

        $starts = $this->faker->optional()->dateTimeBetween('-1 week', '+1 week');
        $ends = $starts ? $this->faker->optional()->dateTimeBetween($starts, '+1 month') : $this->faker->optional()->dateTimeBetween('now', '+1 month');

        return [
            'name' => $this->faker->unique()->catchPhrase(),
            'type' => $type,
            'discount_value' => $value,
            'starts_at' => $starts,
            'ends_at' => $ends,
            'active' => $this->faker->boolean(90),
        ];
    }
}
