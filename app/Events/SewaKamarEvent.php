<?php

namespace App\Events;

use App\Models\Kamar;
use App\Models\RiwayatPersewaan;
use App\Models\Sewa;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SewaKamarEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $kamar_id, $penyewa_id, $date, $status;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Sewa $sewa, String $status)
    {
        $this->kamar_id = $sewa->kamar_id;
        $this->penyewa_id = $sewa->penyewa_id;
        $this->date = $sewa->date;
        $this->status = $status;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('sewa-kamar');
    }
}
