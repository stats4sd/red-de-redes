<?php

namespace App\Jobs;

use App\Events\MetDataImportStarted;
use App\Imports\DavisFileImport;
use App\Models\Met\File;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class StartMetDataImport implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(public File $fileRecord, public string $fileWithMergedHeaders, public Collection $neededConversions, public User $user)
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // tell the user that the import process has started
        MetDataImportStarted::dispatch($this->fileRecord, $this->user);

        Excel::queueImport(new DavisFileImport($this->fileRecord, $this->neededConversions, $this->user), $this->fileWithMergedHeaders, 'public', \Maatwebsite\Excel\Excel::TSV)->chain([
            new MetDataImportCompletedJob($this->fileRecord, $this->user)
        ]);
}
}