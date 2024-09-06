@php
$output = null;
$channel = $entry;
if ($channel->channel_type == 'input' || $channel->channel_type == 'output') {
    $output = $channel->deviceName;
}
else{
    $output = $channel->VirtualDeviceName;
}


@endphp

<span>{{$output->device_type_name}}</span>