<?php

namespace App\Events;

use App\Models\GigOrder;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewGigOrder implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $gigOrder;

    /**
     * Create a new event instance.
     */
    public function __construct(GigOrder $gigOrder)
    {
        $this->gigOrder = $gigOrder;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('provider.' . $this->gigOrder->provider_id),
        ];
    }

    public function broadcastAs()
    {
        return 'new-gig-order';
    }
}
