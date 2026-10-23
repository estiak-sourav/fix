<?php

namespace Database\Factories;

use App\Models\SupportTicket;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<SupportTicket>
 */
class SupportTicketFactory extends Factory
{
    protected $model = SupportTicket::class;

    public function definition(): array
    {
        return [
            'ticket_number' => SupportTicket::generateTicketNumber(),
            'user_id' => null,
            'admin_id' => null,
            'customer_name' => fake()->name(),
            'customer_email' => fake()->safeEmail(),
            'customer_phone' => '017'.fake()->numerify('########'),
            'subject' => fake()->sentence(4),
            'priority' => fake()->randomElement(['low', 'normal', 'high', 'urgent']),
            'status' => 'open',
            'created_by' => 'customer',
            'access_token' => Str::random(40),
            'last_reply_at' => now(),
            'closed_at' => null,
        ];
    }

    public function forUser(User $user): static
    {
        return $this->state(fn () => [
            'user_id' => $user->id,
            'customer_name' => $user->name,
            'customer_email' => $user->email,
            'customer_phone' => $user->phone,
        ]);
    }

    public function closed(): static
    {
        return $this->state(fn () => [
            'status' => 'closed',
            'closed_at' => now(),
        ]);
    }
}
