<?php

return [
    // CRUD table view
    'trashed'                     => 'Trashed',
    'trash'                     => 'Trash',
    'destroy'                   => 'Destroy',
    'restore'                   => 'Restore',

    // Confirmation messages and bubbles
    'trash_confirm'                              => 'Are you sure you want to trash this item?',
    'trash_confirmation_title'                   => 'Item trashed',
    'trash_confirmation_message'                 => 'The item has been trashed successfully.',
    'trash_confirmation_not_title'               => 'NOT trashed',
    'trash_confirmation_not_message'             => "There's been an error. Your item might not have been trashed.",

    // Confirmation messages and bubbles
    'destroy_confirm'                              => 'Are you sure you want to permanently delete this item?',
    'restore_confirm'                              => 'Are you sure you want to restore this item?',
    'restore_success_title'                        => 'Item restored',
    'restore_success_message'                      => 'The item has been restored successfully.',
    'restore_error_title'                          => 'NOT restored',
    'restore_error_message'                        => "There's been an error. Your item haven't been restored.",

    // Bulk Actions
    'bulk_trash_confirm'         => 'Are you sure you want to trash these :number entries?',
    'bulk_trash_success_title'   => 'Entries trashed',
    'bulk_trash_success_message' => ' items have been trashed',
    'bulk_trash_error_title'     => 'Trash failed',
    'bulk_trash_error_message'   => 'One or more items could not be trashed',

    'bulk_destroy_confirm'         => 'Are you sure you want to permanently delete these :number entries?',
    'bulk_destroy_success_title'   => 'Entries permanently deleted',
    'bulk_destroy_success_message' => ' items have been deleted',
    'bulk_destroy_error_title'     => 'Delete failed',
    'bulk_destroy_error_message'   => 'One or more items could not be deleted',

    'bulk_restore_confirm'        => 'Are you sure you want to restore these :number entries?',
    'bulk_restore_sucess_title'   => 'Entries restored',
    'bulk_restore_sucess_message' => ' items have been restored',
    'bulk_restore_error_title'    => 'Restore failed',
    'bulk_restore_error_message'  => 'One or more items could not be restored',
];
