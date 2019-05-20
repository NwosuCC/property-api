<?php

namespace App\Events;

use App\House;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class HouseSaved
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $house;

    /**
     * Create a new event instance.
     *
     * @param $house
     * @return void
     */
    public function __construct(House $house)
    {
        $this->house = $house;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
