<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ProductMediaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->imageUrl(640, 480, 'animals', true),
            'media_type' => 'image',
            'media_text' => null,
        ];
    }
}
