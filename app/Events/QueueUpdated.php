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
    public $categoryName;
    public $queueNumber;
    public $isAdminCall; // Tambahkan properti ini

    public function __construct($categoryId, $remainingQueues, $categoryName, $queueNumber, $isAdminCall = false)
    {
        $this->categoryId = $categoryId;
        $this->remainingQueues = $remainingQueues;
        $this->categoryName = $categoryName;
        $this->queueNumber = $queueNumber;
        $this->isAdminCall = $isAdminCall; // Set properti ini
    }

    public function broadcastOn()
    {
        return new Channel('queue-updates');
    }
}
