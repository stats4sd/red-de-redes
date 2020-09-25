<?php

namespace App\Jobs;

use App\Models\User;
use App\Models\Xlsform;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Http;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use App\Events\KoboDeploymentReturnedError;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SetKoboFormToActive implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $user;
    public $form;

    /**
     * Create a new job instance.
     * @param User $user
     * @param Xlsform $form
     * @return void
     */
    public function __construct(User $user, Xlsform $form)
    {
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

        // Deployement already exists, so get new version_id to update deployment
        if ($this->form->kobo_version_id) {

            $getVersion = Http::withBasicAuth(config('services.kobo.username'), config('services.kobo.password'))
            ->withHeaders(['Accept' => 'application/json'])
            ->get(config('services.kobo.endpoint_v2') . '/assets/'. $this->form->kobo_id.'/')
            ->throw()
                ->json();

            $newVersionId = $getVersion['version_id'];

            // update deployment with new version
            $response = Http::withBasicAuth(config('services.kobo.username'), config('services.kobo.password'))
            ->withHeaders(['Accept' => 'application/json'])
            ->patch(config('services.kobo.endpoint_v2') . '/assets/' . $this->form->kobo_id . '/deployment/', [
                'active' => true,
                'version_id' => $newVersionId,
            ]);
        }

        // Deployment doesn't exist for this form, so POST;
        else {

            $response = Http::withBasicAuth(config('services.kobo.username'),config('services.kobo.password'))
            ->withHeaders(['Accept' => 'application/json'])
            ->post(config('services.kobo.endpoint_v2').'/assets/'.$this->form->kobo_id.'/deployment/', [
                'active' => true,
            ]);
        }

        if($response->failed()) {
            event(new KoboDeploymentReturnedError($this->user, $this->form, 'Deployment Error', json_encode($response->json())));
            throw new \Exception('Error: ' . json_encode($response->json()));
        }

        $response = $response->json();

        $this->form->update([
            'kobo_version_id' => $response['version_id'],
            'enketo_url' => $response['asset']['deployment__links']['url'],
            'is_active' => true,
        ]);


    }
}
