<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $name = $this->faker->name();
        return [
            'name' => $name,
            'slug' => Str::slug($name, '-'),
            'image' => $this->faker->imageUrl(640, 480, 'animals', true),
            'parent_id' => 0,
            'level' => 0,
            'is_active' => 1,
        ];
    }
}
