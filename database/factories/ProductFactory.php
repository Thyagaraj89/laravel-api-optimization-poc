<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

return new class extends Factory {
    public function definition(): array
    {
        return [
            'sku' => strtoupper($this->faker->bothify('SKU-####??')),
            'name' => $this->faker->words(3, true),
            'price_cents' => $this->faker->numberBetween(500, 25000),
        ];
    }
};
