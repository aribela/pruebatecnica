<?php

namespace App\Observers;

use App\Models\Evidence;
use App\Models\Notifications;

class EvidenceObserver
{
    //en este caso la primera vez se crea uno de creating y update por la imagen que se sube despues
    public function creating(Evidence $evidence){

        $notification = Notifications::create([
            'user_id' => $evidence->user_id,
            'accion' => 'crear',
        ]);
    }

    public function updating(Evidence $evidence){

        $notification = Notifications::create([
            'user_id' => $evidence->user_id,
            'accion' => 'actualizar',
        ]);
    }
}
