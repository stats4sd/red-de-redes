<?php

namespace App\Events;

use App\Models\DataMap;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

class NewDataVariableSpotted
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $variable;
    public $datamap;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Array $variable, DataMap $datamap)
    {
        //
        $this->variable = $variable;
        $this->datamap = $datamap;

    }


}
