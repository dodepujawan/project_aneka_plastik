<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PdfDone implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $userId;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($userId)
    {
        $this->userId = $userId;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        \Log::info('Broadcast ke userId:', ['userId' => $this->userId]);
        return new PrivateChannel('user.' . $this->userId);
    }

    public function broadcastAs()
    {
        return 'pdf.done';
    }

    public function broadcastWith()
    {
        return [
            'message' => 'PDF selesai dibuat!',
            // 'idNotifikasi' => $this->idNotifikasi ?? null,
        ];
    }
}
