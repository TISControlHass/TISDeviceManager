<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChannelController;
use App\Http\Controllers\DeviceController;
use App\Http\Controllers\ApplianceController;
use App\Models\VirtualDevice;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/outputchannel/set', [ChannelController::class, 'setOutState'])->name('outputchannel.setstate');
Route::get('/outputchannel/{id}', [ChannelController::class, 'getOutState'])->name('outputchannel.getstate');


// create routes for the DeviceController
Route::get('devices', [DeviceController::class, 'index'])->name('devices.index');
Route::get('devices/create', [DeviceController::class, 'create'])->name('devices.create');
Route::get('devices/scan', [DeviceController::class, 'scan'])->name('devices.scan');
Route::post('devices', [DeviceController::class, 'store'])->name('devices.store');
Route::get('devices/{device}', [DeviceController::class, 'show'])->name('devices.show');
Route::get('devices/{device}/edit', [DeviceController::class, 'edit'])->name('devices.edit');
Route::put('devices/{device}', [DeviceController::class, 'update'])->name('devices.update');
Route::delete('devices/{device}', [DeviceController::class, 'destroy'])->name('devices.destroy');

// routes for the ApplianceController
Route::get('appliances/publish', [ApplianceController::class, 'publish'])->name('appliances.publish');
Route::get('appliances/auto-create', [ApplianceController::class, 'auto_create'])->name('appliances.auto-create');

Route::get('search', function (Request $request) {
    $search = $request->get('term');

    if (strlen($search) >= 1) {
        $results = VirtualDevice::where('device_name', 'LIKE', '%' . $search . '%')->get();

        return $results->map(function ($result) {
            return $result->device_name;
        });
    }
    return [];
});
