<?php

namespace App\Http\Controllers;

use App\Models\Device;
use App\Models\DeviceType;
use App\Models\Settings;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class DeviceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $devices = Device::all();
        return response()->json($devices);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // $device = Device::create($request->all());
        return response()->json("store");
    }

    public function scan(Request $request)
    {
        // Initialize the Guzzle client
        $client = new \GuzzleHttp\Client();

        // Specify the API endpoint
        $server_address = Settings::where("key", "server_address")->first()->value;
        $url = $server_address . '/api/scan_devices';

        try {
            // Make a GET request to the API endpoint
            $response = $client->request('GET', $url);

            // Get the response body
            $devices = json_decode($response->getBody()->getContents(), true);
            // log
            Log::info($devices);
            // store each device in the database that matches the device model
            foreach ($devices as $device) {
                try {
                    // Convert device_type_code array to a comma-separated string
                    $deviceTypeCodeStr = implode(',', $device['device_type_code']);

                    if (in_array($deviceTypeCodeStr, DeviceType::all()->pluck('device_model_number')->toArray())) {
                        Device::updateOrCreate(
                            [
                                'device_address' => implode(',', $device['device_id'])
                            ],
                            [
                                'device_type' => DeviceType::where('device_model_number', $deviceTypeCodeStr)->first()->id,
                                'gateway' => implode('.', $device['gateway']),
                            ]
                        );
                    } else {
                        Log::info("Device type" . $deviceTypeCodeStr . "not found");
                    }
                } catch (\Exception $e) {
                    // Handle the exception (e.g., log the error, return an error message, etc.)
                    dd($e);
                    return response()->json(['error' => 'Unable to update or create device'], 500);
                }
            }

            // Return the devices
            return redirect()->route("device.index");
        } catch (\Exception $e) {
            // Handle the exception (e.g., log the error, return an error message, etc.)
            dd($e);
            return response()->json(['error' => 'Unable to retrieve devices'], 500);
        }
    }
    /**
     * Display the specified resource.
     */
    public function show(Device $device)
    {
        return view('devices.show', compact('device'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Device $device)
    {
        $device->update($request->all());
        return redirect()->route('devices.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Device $device)
    {
        $device->delete();
        return redirect()->route('devices.index');
    }
}
