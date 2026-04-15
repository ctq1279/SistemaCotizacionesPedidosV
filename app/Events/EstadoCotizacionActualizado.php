<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

use App\Models\Cotizacione;

class EstadoCotizacionActualizado
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $cotizacion;

    public function __construct()
    {
        //
    }

    public function broadcastOn(Cotizacione $cotizacion)
    {
        $this->cotizacion = $cotizacion;
    }
}
