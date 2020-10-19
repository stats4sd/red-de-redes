<?php

namespace App\Jobs;

use App\Models\User;
use App\Models\Xlsform;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Http;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class DeployFormToKobo implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $user;
    public $form;


    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(User $user, Xlsform $form)
    {
        //
        $this->user = $user;
        $this->form = $form;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        //if form is not already on Kobo, create asset...
        if(! $this->form->kobo_id) {
            // Create new Kobo Asset
            $response = Http::withBasicAuth(config('services.kobo.username'), config('services.kobo.password'))
            ->withHeaders(["Accept" => "application/json"])
            ->post(config('services.kobo.endpoint')."/api/v2/assets/", [
                "name" => "TEST VERSION: " . $this->form->title,
                "asset_type" => "survey",
            ])
            ->throw() // throw error and halt if 4** or 5**
            ->json();

            $this->form->update([
                'kobo_id' => $response['uid'],
            ]);
        }



        // Always upload xlsform (in case it is changed)
        UploadXlsFormToKobo::dispatch($this->user, $this->form);


    }

}
