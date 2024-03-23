<?php

namespace App\Events;

use App\Models\Person;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class DeletingPersonEvent
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @param bool $isSuperAdmin indica si el usuario que está eliminando la persona es un super administrador
     */
    public function __construct(
        public Person $personModel,
        public bool $isSuperAdmin,
    ) {
    }
}
