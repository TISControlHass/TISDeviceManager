<?php

namespace Backpack\Pro\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class PurgeTemporaryFolder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'backpack:purge-temporary-folder {--older-than=} {--path=} {--disk=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Deletes files from temporary folder older than X minutes';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $temporaryDisk = $this->option('disk') ?? config('backpack.operations.dropzone.temporary_disk');
        $temporaryFolder = $this->option('path') ?? config('backpack.operations.dropzone.temporary_folder');
        $purgeFilesOlderThan = $this->option('older-than') ?? config('backpack.operations.dropzone.purge_temporary_files_older_than');
        
        collect(Storage::disk($temporaryDisk)->listContents($temporaryFolder, true))
        ->each(function ($file) use ($temporaryDisk, $purgeFilesOlderThan) {
            if ($file['type'] === 'file' && $file['lastModified'] < now()->subMinutes($purgeFilesOlderThan)->getTimestamp()) {
                Storage::disk($temporaryDisk)->delete($file['path']);
            }
        });
    }
}
