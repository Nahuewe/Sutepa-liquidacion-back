<?php

namespace App\Events;

use App\Models\Votacion;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NuevaVotacionEvent implements ShouldBroadcast
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    public Votacion $votacion;

    public function __construct(Votacion $votacion)
    {
        $this->votacion = $votacion;
    }

    public function broadcastOn(): Channel
    {
        return new Channel('votaciones');
    }

    public function broadcastAs(): string
    {
        return 'nueva-votacion';
    }
}
