<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Appliance;
use App\Models\Device;
use App\Models\ApplianceChannels;
use App\Models\ApplianceType;
use App\Models\DefaultAppliance;
use App\Models\Settings;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Redirect;
// logging
use Illuminate\Support\Facades\Log;
// use Alert;
use Prologue\Alerts\Facades\Alert as Alert;
use Illuminate\Support\Facades\DB;
use PHPUnit\TextUI\XmlConfiguration\Logging\Logging;

// import Alert facade


// import the helper appliancetype switch


class ApplianceController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index(Request $request)
    {
        //
    }
    public function publish(Request $request)
    {
        // check offlline programing is correct
        $channels =  ApplianceChannels::all();
        foreach ($channels as $channel) {
            if ($channel->appliance_id <= 0 || $channel->channel_number == null) {
                // Set a success message in the session
                Alert::add('error', 'Error publishing appliances, missing device or channel number on ' . $channel->applianceId->appliance_name . $channel->channel_name)->flash();
                Log::info('Error publishing appliances, missing device or channel number on ' . $channel->applianceId->appliance_name . $channel->channel_name);
                return Redirect::to(backpack_url('appliance-channels'));
            }
        }
        $server_address = Settings::where("key", "server_address")->first()->value;
        $appliance_channels = ApplianceChannels::select('appliance_id', 'channel_number', 'channel_name')->get();
        $appliance_channels->transform(function ($channel) {
            $channel->appliance_name = $channel->appliance->appliance_name;
            $channel->is_protected = $channel->applianceId->is_protected;
            $channel->device_id = $channel->device->device_address;
            $channel->gateway = $channel->device->gateway;
            $channel->appliance_type = $channel->applianceId->applianceType->appliance_type_name;
            $channel->appliance_class = $channel->applianceId->appliance_class;
            unset($channel->applianceId);
            unset($channel->appliance);
            unset($channel->device);
            return $channel;
        });

        $grouped_channels = $appliance_channels->groupBy('appliance_name');

        $devices = Device::select('device_type', 'device_name', 'device_address')->get();

        $devices->transform(function ($device) {
            $device->device_type = $device->deviceType->device_type_name;
            unset($device->deviceType);
            return $device;
        });

        // get the configs
        $configs = [
            'lock_module_password' => Settings::where('key', 'lock_module_password')->first()->value,
        ];


        $payload = [
            'devices' => $devices,
            'appliances' => $grouped_channels,
            'configs' => $configs,
        ];
        // log the payload
        Log::info('Payload: ' . json_encode($payload));
        // make post request

        // TODO: 
        try {
            $response = Http::post($server_address . '/api/tis', $payload);
            Alert::Add('success', 'Successfully published the appliances')->flash();
            return back()->with('success', 'Request successful.');
        } catch (\Exception $e) {
            Alert::add('error', 'Error publishing appliances,' . str($e))->flash();
            return back();
        }
    }

    public function auto_create(Request $request)
    {
        DB::beginTransaction();

        try {
            // get all devices
            $devices = Device::all();
            // make sure all devices has no appliancechannels
            Appliance::query()->delete();
            ApplianceChannels::query()->delete();

            foreach ($devices as $device) {
                //for each device, get the device type and create the default appliances

                $defaultAppliances = $device->deviceType->defaultAppliances;
                foreach ($defaultAppliances as $appliance) {
                    // create a new appliance
                    $appliance = Appliance::create(
                        [
                            'device_id' => $device->id,
                            'appliance_name' => $device->device_name . '_' . $appliance->appliance_identifier,
                            'appliance_type' => $appliance->appliance_type,
                            'appliance_class' => null,
                            'is_protected' => $appliance->appliancetype->is_protected,
                        ]
                    );
                }
            }

            DB::commit();

            Alert::add('success', 'Successfully auto created appliances')->flash();
            return back()->with('success', 'Successfully auto created appliances');
        } catch (\Throwable $th) {
            DB::rollback();

            Alert::add('error', 'Error creating appliances ' . str($th))->flash();
            return back()->with('error', 'Error creating appliances');
        }
    }

    // private function createApplianceAndChannels($device, $channels, $applianceType, $channel_type)
    // {
    //     foreach ($channels as $channel) {
    //         $appliance = Appliance::updateOrCreate(
    //             [
    //                 'device_id' => $device->id,
    //             ],
    //             [
    //                 'appliance_name' => $device->device_name . '_' . $channel_type . '_' . $channel->channel_number,
    //                 'appliance_type' => $applianceType->id,
    //                 'appliance_class' => null,
    //             ]
    //         );

    //         // update appliance channels with the device id and channel number
    //         ApplianceChannels::updateOrCreate(
    //             [
    //                 'appliance_id' => $appliance->id,
    //                 'channel_type' => $channel_type,
    //             ],
    //             [
    //                 'device_id' => $device->id,
    //                 'channel_number' => $channel->channel_number,
    //             ]
    //         );
    //     }
    // }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Appliance $appliance)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Appliance $appliance)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Appliance $appliance)
    {
        //
    }
}
