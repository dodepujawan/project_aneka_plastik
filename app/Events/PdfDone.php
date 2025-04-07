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
    public $userBroad;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($userBroad)
    {
        $this->userBroad = $userBroad;
        \Log::info('PdfDone konstruktor', ['userBroad' => $this->userBroad]);
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        // \Log::info('Broadcast channel:', ['channel' => $channel]);
        \Log::info('Event user broadcastr:' . $this->userBroad);

        // return [
        //     // new Channel('public-pdf-notifications'),
        //     new PrivateChannel('user.'. $this->userBroad)
        // ];
        return new PrivateChannel('user.'. $this->userBroad);

        // $channel = 'user.' . $this->userBroad;
        // return new PrivateChannel($channel);
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
