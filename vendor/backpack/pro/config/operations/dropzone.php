<?php

return [
    // the disk where temporary files will be stored, eg: temporary uploaded files.
    'temporary_disk' => 'public',

    // the folder inside the disk to store the files.
    'temporary_folder' => 'backpack/temp',

    // temporary files should be cleaned every once in a while to clear stale files
    // you can configure this process to happen automatically via cron jobs or queues.
    // head to the documentation https://backpackforlaravel.com/docs/6.x/crud-fields#dropzone-temporary-folder
    'purge_temporary_files_older_than' => 24*60, //minutes
];