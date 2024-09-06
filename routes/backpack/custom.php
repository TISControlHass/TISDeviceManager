<?php

use Illuminate\Support\Facades\Route;

// --------------------------
// Custom Backpack Routes
// --------------------------
// This route file is loaded automatically by Backpack\Base.
// Routes you generate using Backpack\Generators will be placed here.

Route::group([
    'prefix' => config('backpack.base.route_prefix', 'admin'),
    'middleware' => array_merge(
        (array) config('backpack.base.web_middleware', 'web'),
        (array) config('backpack.base.middleware_key', 'admin')
    ),
    'namespace' => 'App\Http\Controllers\Admin',
], function () { // custom admin routes
    Route::crud('user', 'UserCrudController');
    Route::crud('appliance', 'ApplianceCrudController');
    Route::crud('device', 'DeviceCrudController');
    Route::crud('device-type', 'DeviceTypeCrudController');
    Route::crud('channel', 'ChannelCrudController');
    Route::crud('settings', 'SettingsCrudController');
    Route::crud('rooms', 'RoomsCrudController');
    Route::crud('floors', 'FloorsCrudController');
    Route::crud('appliance-type', 'ApplianceTypeCrudController');
    Route::crud('virtual-device', 'VirtualDeviceCrudController');
    Route::crud('appliance-channels', 'ApplianceChannelsCrudController');
    // resource routes
    Route::resource('handover', 'HandOverController');
    Route::crud('default-appliance', 'DefaultApplianceCrudController');
    Route::crud('default-appliance-channel', 'DefaultApplianceChannelCrudController');
}); // this should be the absolute last line of this file