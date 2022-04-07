<?php

namespace App\Mail;

use App\Models\DataMap;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewVariableSpottedEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $variable;
    public $dataMap;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Array $variable, DataMap $dataMap)
    {
        //
        $this->variable = $variable;
        $this->dataMap = $dataMap;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from("no-reply@stats4sd.org")
        ->subject('CCRP Agrometric Platform: New Variable Spotted!!')
            ->markdown('emails.new_variable');
    }
}
