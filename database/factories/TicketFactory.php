<?php

namespace Database\Factories;

use App\Models\Customer;
use App\Models\Ticket;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Ticket>
 */
class TicketFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Ticket::class;

    public function definition(): array
    {
        return [
            'customer_id' => Customer::factory(),
            'subject'     => $this->faker->sentence(4),
            'message'     => $this->faker->paragraph(),
            'status'      => $this->faker->randomElement(['new','in_progress','resolved']),
            'manager_replied_at' => null,
        ];
    }
}
