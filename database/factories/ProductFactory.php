<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $paragraphs = $this->faker->paragraphs(rand(2, 6));
        $title = $this->faker->realText(50);
        $details = "<h1>{$title}</h1>";
        foreach ($paragraphs as $para) {
            $details .= "<p>{$para}</p>";
        }

        $price = $this->faker->numberBetween(10, 10000000);
        $minShippingDays = $this->faker->numberBetween(1, 10);
        return [
            'name' => $this->faker->bothify('?###??##'),
            'price' => $price,
            'details' => $details,
            'is_promotion' => $this->faker->numberBetween(0, 1),
            'promotion_price' => $price - $this->faker->numberBetween(1, $price),
            'shipping_type' => $this->faker->numberBetween(FREE_NATIONAL_SHIPPING, SHIPPING_NATIONALLY),
            'min_shipping_days' => $minShippingDays,
            'max_shipping_days' => $this->faker->numberBetween($minShippingDays, $minShippingDays+$this->faker->numberBetween(1, 5)),
            'shipping_cost' => $this->faker->numberBetween(100, 1000000),
            'is_active' => $this->faker->numberBetween(0, 1),
        ];
    }
}
