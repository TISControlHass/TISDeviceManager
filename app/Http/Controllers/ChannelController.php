<?php

namespace App\Http\Controllers;

use App\Models\Channel;
use Illuminate\Http\Request;

class ChannelController extends Controller
{
    public function setOutState(Request $request)
    {
        try {

            // Get the output channel
            $data  = $request->all();
            $outputChannel = Channel::where('id', $data['id'])
                ->where('channel_type', 'output')
                ->first();

            if (!$outputChannel) {
                return response()->json(['error' => 'Output channel ' . $data['id'] . ' not found'], 404);
            }

            // Set the output channel value
            $outputChannel->channel_value = $request->value;

            // Save the output channel
            $outputChannel->save();
            // Broadcast the output channel value
            // Return the output channel
            return $outputChannel;
        } catch (\Exception $e) {
            return response()->json(['error' => 'Internal server error'], 500);
        }
    }

    public function getOutState(Request $request, $id)
    {
        // Get the output channel
        $outputChannel = Channel::where('id', $id)
            ->where('channel_type', 'output')
            ->first();
        // Return the output channel
        return $outputChannel;
    }
}
