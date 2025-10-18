<?php

namespace Database\Factories;


use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Customer>
 */
class CustomerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Customer::class;

    public function definition(): array
    {
        return [
            'name'  => $this->faker->name(),
            'phone' => '+'. $this->faker->numberBetween(1,9) . $this->faker->numerify('###########'), // E.164 вид
            'email' => $this->faker->unique()->safeEmail(),
        ];
    }
}
