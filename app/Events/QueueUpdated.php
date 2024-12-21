<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class QueueUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $categoryId;
    public $remainingQueues;

    public function __construct($categoryId, $remainingQueues)
    {
        if (!is_int($categoryId) || !is_int($remainingQueues)) {
            throw new \InvalidArgumentException('Invalid data for QueueUpdated event.');
        }
    
        $this->categoryId = $categoryId;
        $this->remainingQueues = $remainingQueues;
    }

    public function broadcastOn()
    {
        return new Channel('queue-updates');
    }
}
