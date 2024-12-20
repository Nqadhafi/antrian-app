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
        $this->categoryId = $categoryId;
        $this->remainingQueues = $remainingQueues;
    }

    public function broadcastOn()
    {
        return new Channel('queue-updates');
    }
}
