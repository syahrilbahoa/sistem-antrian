<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PanggilAntrian implements ShouldBroadcast
{
    use Dispatchable, SerializesModels;

    public $nomor;
    public $loket;

    public function __construct($nomor, $loket)
    {
        $this->nomor = $nomor;
        $this->loket = $loket;
    }

    public function broadcastOn()
    {
        return new Channel('antrian');
    }

    public function broadcastAs()
    {
        return 'panggil.antrian';
    }
}
