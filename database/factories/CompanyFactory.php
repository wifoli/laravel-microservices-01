<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Company>
 */
class CompanyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'category_id'   => Category::factory()->create(),
            'name'          => $this->faker->unique()->name(),
            'email'         => $this->faker->unique()->email(),
            'whatsapp'      => $this->faker->unique()->numberBetween(1000000000, 999999999),
            'image'         => $this->faker->imageUrl()
        ];
    }
}
