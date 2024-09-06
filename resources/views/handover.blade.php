@extends(backpack_view('blank'))

{{-- TODO : save image offline --}}
@section('header')
    <section class="header-operation container-fluid animated fadeIn d-flex mb-2 align-items-baseline d-print-none"
        bp-section="page-header">
        <div class="row d-flex justify-content-between" style="width: 100%">
            <div class="col-md-6">
                <h1 class="text-capitalize mb-0" bp-section="page-heading">TIS Handover Sheet</h1>
            </div>
            <div class="col-lg-5"></div>
            <div class="col-md-1 text-right">
                <img src="{{ asset('assets/img/tis_logo.jpg') }}" alt="TIS Logo">
            </div>
        </div>
        {{-- <p class="ms-2 ml-2 mb-0" id="datatable_info_stack" bp-section="page-subheading">sub header</p> --}}
    </section>
@endsection
@php
    $devices = \App\Models\Device::all();
@endphp

@section('content')
    <style>
        .handover-content {
            font-family: Arial, sans-serif;
        }

        .device-table,
        .appliance-table {
            width: 100%;
            border-collapse: collapse;
        }

        .device-header th,
        .channels td,
        .channels th,
        .channel-header th,
        .channel-row td {
            padding: 10px;
            border-top: 2px solid #000;
            border-bottom: 2px solid #000;
            border-left: none;
            border-right: none;
        }

        .appliance-row td,
        .appliance-header th,
        .appliance-row {

            border-left: none;
            border-right: none;
            border-top: none;
            border-bottom: none;
        }

        .device-header {
            background-color: red;
            color: white;
            border-top: none;
            border-bottom: none;
        }

        .device-header th {
            padding: 10px;
        }

        .channel-type-header th,
        .channel-type-header td {
            color: #333;
            text-align: center;
        }

        .channel-header th {
            background-color: #333;
            color: white;
        }

        .channels {
            background-color: black;
            color: white;
        }

        .channels td,
        .channels th {
            padding: 10px;
        }

        .channel-row td {
            color: black;
        }

        .appliance-table {
            width: 100%;
            margin-top: 10px;
            border-collapse: collapse;
        }

        .appliance-header {
            background-color: #333;
            color: white;
        }

        .appliance-header th {
            padding: 10px;
        }

        .appliance-row td {
            padding: 10px;
            background-color: #f2f2f2;
            color: black;
        }
    </style>
    {{-- Default box --}}
    <div class="handover-content">
        <table id="sheet_table" class="device-table">
            @foreach ($devices as $device)
                <thead class="device-header">
                    <tr>
                        <th class="device-name">Name: {{ $device->device_name }}</th>
                        <th class="device-type">Type: {{ $device->deviceType->device_type_name }}</th>
                        <th class="device-mac">Adress: {{ $device->mac }}</th>
                    </tr>
                </thead>
                <tbody class="channels">
                    <tr class="channel-header">
                        <th>Channel Description</th>
                        <th>Channel Number</th>
                        <th>Appliance Channels</th>
                    </tr>
                    <tr class="channel-type-header">
                        <th colspan="3">Output Channels</th>
                    </tr>
                    @foreach ($device->outputChannels as $channel)
                        <tr class="channel-row">
                            <td class="channel-description">{{ $channel->channel_description }}</td>
                            <td class="channel-number">{{ $channel->channel_number }}</td>
                            @php
                                $appliance_channels = $channel->appliances;
                            @endphp
                            @if ($appliance_channels->count() > 0)
                                <td class="appliance-channels">
                                    <table class="appliance-table">
                                        <tr class="appliance-header">
                                            <th class="appliance-name">Appliance Name</th>
                                            <th class="appliance-type">Appliance Type</th>
                                            <th class="channel-name">Channel Name</th>
                                            <th>Floor</th>
                                            <th>Room</th>
                                        </tr>
                                        @foreach ($appliance_channels as $appliance_channel)
                                            <tr class="appliance-row">
                                                <td>{{ $appliance_channel->applianceId->appliance_name }}</td>
                                                <td>{{ $appliance_channel->applianceId->applianceType->appliance_type_name }}
                                                </td>
                                                <td>{{ $appliance_channel->channel_name }}</td>
                                                <td>{{ $appliance_channel->applianceId->floorId->floor_name }}</td>
                                                <td>{{ $appliance_channel->applianceId->roomId->room_name }}</td>
                                            </tr>
                                        @endforeach
                                    </table>
                                </td>
                            @endif
                        </tr>
                    @endforeach
                    <tr class="channel-type-header">
                        <th colspan="3">Input Channels</th>
                    </tr>
                    @foreach ($device->inputChannels as $channel)
                        <tr class="channel-row">
                            <td class="channel-description">{{ $channel->channel_description }}</td>
                            <td class="channel-number">{{ $channel->channel_number }}</td>
                            @php
                                $appliance_channels = $channel->appliances;
                            @endphp
                            @if ($appliance_channels->count() > 0)
                                <td class="appliance-channels">
                                    <table class="appliance-table">
                                        <tr class="appliance-header">
                                            <th class="appliance-name">Appliance Name</th>
                                            <th class="appliance-type">Appliance Type</th>
                                            <th class="channel-name">Channel Name</th>
                                            <th>Floor</th>
                                            <th>Room</th>
                                        </tr>
                                        @foreach ($appliance_channels as $appliance_channel)
                                            <tr class="appliance-row">
                                                <td>{{ $appliance_channel->applianceId->appliance_name }}</td>
                                                <td>{{ $appliance_channel->applianceId->applianceType->appliance_type_name }}
                                                </td>
                                                <td>{{ $appliance_channel->channel_name }}</td>
                                                <td>{{ $appliance_channel->applianceId->floorId->floor_name }}</td>
                                                <td>{{ $appliance_channel->applianceId->roomId->room_name }}</td>
                                            </tr>
                                        @endforeach
                                    </table>
                                </td>
                            @endif
                        </tr>
                    @endforeach
                </tbody>
            @endforeach
        </table>
    </div>
@endsection

@section('after_styles')
    {{-- DATA TABLES --}}
    @basset('https://cdn.datatables.net/1.13.1/css/dataTables.bootstrap5.min.css')
    @basset('https://cdn.datatables.net/fixedheader/3.3.1/css/fixedHeader.dataTables.min.css')
    @basset('https://cdn.datatables.net/responsive/2.4.0/css/responsive.dataTables.min.css')

    {{-- CRUD LIST CONTENT - crud_list_styles stack --}}
    @stack('crud_list_styles')
@endsection

@section('after_scripts')
    {{-- @include('crud::inc.datatables_logic') --}}

    {{-- CRUD LIST CONTENT - crud_list_scripts stack --}}
    @stack('crud_list_scripts')
@endsection
