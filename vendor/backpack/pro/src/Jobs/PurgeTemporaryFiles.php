<?php

namespace Backpack\Pro\Jobs;

use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Artisan;

class PurgeTemporaryFiles implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(public string|null $disk = null, public string|null $path = null, public int|null $olderThan = null)
    {
        $this->disk = $disk ?? CrudPanelFacade::getOperationSetting('temporary_disk') ?? config('backpack.operations.dropzone.temporary_disk');
        $this->path = $path ?? CrudPanelFacade::getOperationSetting('temporary_folder') ?? config('backpack.operations.dropzone.temporary_folder');
        $this->olderThan = $olderThan ?? CrudPanelFacade::getOperationSetting('purge_temporary_files_older_than') ?? config('backpack.operations.dropzone.purge_temporary_files_older_than');
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Artisan::call('backpack:purge-temporary-folder --disk='.$this->disk.' --path='.$this->path.' --older-than='.$this->olderThan);
    }
}
