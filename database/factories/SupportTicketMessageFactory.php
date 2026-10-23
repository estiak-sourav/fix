<?php

namespace Database\Factories;

use App\Models\SupportTicket;
use App\Models\SupportTicketMessage;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<SupportTicketMessage>
 */
class SupportTicketMessageFactory extends Factory
{
    protected $model = SupportTicketMessage::class;

    public function definition(): array
    {
        return [
            'support_ticket_id' => SupportTicket::factory(),
            'sender_type' => 'customer',
            'sender_id' => null,
            'sender_name' => fake()->name(),
            'message' => fake()->paragraph(),
        ];
    }

    public function fromAdmin(string $adminName = 'Support Admin'): static
    {
        return $this->state(fn () => [
            'sender_type' => 'admin',
            'sender_name' => $adminName,
        ]);
    }
}
