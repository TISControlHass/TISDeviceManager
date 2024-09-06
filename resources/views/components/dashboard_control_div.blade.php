@php
    $home_structure_url = backpack_url('rooms');
    $device_types_url = backpack_url('device-type');
    $appliance_types_url = backpack_url('appliance-type');
@endphp
<!DOCTYPE html>
<html>

<head>
    <title>Tutorial</title>
    {{-- <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" rel="stylesheet"> --}}
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f0f0f0;
        }

        .container {
            width: 80%;
            margin: 0 auto;
            padding: 20px;
        }

        h1 {
            color: #333;
        }

        .step {
            background-color: #fff;
            border-radius: 5px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0px 0px 10px 0px rgba(0, 0, 0, 0.1);
        }

        .step h2 {
            margin-top: 0;
        }

        .step p {
            margin-bottom: 0;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Device Manager Manual</h1>
        <ul class="nav nav-tabs">
            <li class="nav-item">
                <a class="nav-link active" data-toggle="tab" href="#firstTimeUser">First Time User</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#returningUser">Appliances config Guide</a>
            </li>
        </ul>
        <div class="tab-content">
            <div id="firstTimeUser" class="tab-pane fade show active">
                <!-- First Time User Tutorial -->
                <div class="step">
                    <h2>Step 1: Home Structure</h2>
                    <p>navigate to <a href={{ $home_structure_url }}>home structure tab</a> and add the floors and rooms
                        of your house.</p>
                    <p>it's recommended to add all the floors first and then add the rooms to save time and not to miss
                        any records</p>
                </div>
                <div class="step">
                    <h2>Step 2: Devices</h2>
                    <p>Check the <a href={{ $device_types_url }}>devices types</a> and add any extra records needed</p>
                    <p>devices are TIS control's phsyical devices aka RCU,Dimmer etc...</p>
                </div>
                <div class="step">
                    <h2>Step 3: Appliances</h2>
                    <p>Check the <a href={{ $appliance_types_url }}>appliance types</a> and add any extra records needed
                    </p>
                    <p>appliances are a virtual representation of the clien't electrical appliances aka light bulb, fan
                        etc... and they follow Home Assistant <a
                            href="https://developers.home-assistant.io/docs/core/entity/">representation</a></p>
                </div>
            </div>
            <div id="returningUser" class="tab-pane fade">
                <!-- Returning User Tutorial -->
                <div class="step">
                    <h2>Step 1: Add devices</h2>
                    <ul>
                        <li>in this step we will add the TIS control devices installed at the client's house. depending
                            on
                            your favorite method, there are two ways to achieve this:
                            <ul>
                                <li>offline programing: this method is used when the devices are not yet installed and
                                    you
                                    want to configure the system before the installation:</li>
                                <ul>
                                    <li>
                                        <p>navigate to devices tab and select <a
                                                href={{ backpack_url('virtual-device') }}>virtual
                                                devices</a>
                                        </p>
                                    </li>
                                    <li>
                                        <p>click add virtual devices button to add a new device</p>
                                    </li>
                                    <li>
                                        <p>select a device type from the menu and then enter a name for the device,
                                            please note that
                                            <strong>the name is our unique identifier and cannot be duplicated</strong>
                                        </p>
                                    </li>
                                    <li>save and repeat the steps untill all devices are added</li>
                                </ul>
                                <li>Regular programing: this method requires the TIS control devices to be connected to
                                    the
                                    network and discovered by the TIS software:
                                    <ul>
                                        <li>
                                            <p>navigate to devices tab and select <a
                                                    href={{ backpack_url('device') }}>devices</a>
                                            </p>
                                        </li>
                                        <li>press the scan button which will discover the network for TIS control
                                            devices and add them to the table</li>
                                        <li>for each added device, please set a unique name for each device and note
                                            that <strong>the device will inherit configurations from an offline device
                                                if they have the same name</strong></li>
                                    </ul>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
                <div class="step">
                    <h2>Step 2: Add appliances</h2>
                    <ul>
                        <li>
                            <p>navigate to Appliances tab and select <a
                                    href={{ backpack_url('appliance') }}>appliances</a>
                            </p>
                        </li>
                        <li>
                            <p>click add appliancess button to add a new appliance</p>
                        </li>
                        <li>
                            <p>select an appliance type from the menu and then enter a name for the appliance, please
                                note that
                                <strong>the name is our unique identifier and cannot be duplicated</strong>.
                                finally select the floor and room which the appliance is installed in
                            </p>
                        </li>
                        <li>save and repeat the steps untill all appliances are added</li>
                    </ul>
                </div>
                <div class="step">
                    <h2>Step 3: Map appliances</h2>
                    <ul>
                        <li>
                            <p>navigate to Appliances tab and select <a href={{ backpack_url('appliance-channels') }}>
                                    Appliances
                                    Channels</a></p>
                        </li>
                        <li>
                            <p>for each appliance in the list, you need to select the controlling device and the channel
                                which is connected to.</p>
                        </li>
                        <li>press edit (from the right side column) to configure an appliance</li>
                        <li>first you should select a controlling device and there are two types of devices :</li>
                        <ul>
                            <li>virtual devices: which are the devices created in the offline programing step, these
                                devices don't actually exist yet but rather they are just a representation <strong>they
                                    are denoted by an OFFLINE tag</strong></li>
                            <li>physical devices: these are the actual discovered devices on the network</li>
                        </ul>
                        <li>after selecting the device, please type the channel number which the appliance is connected
                            to.
                        </li>
                        <li>select the correct channel type from the dropdown, <strong>please select virtual channel
                                types in
                                case of using offline programing.</strong></li>
                        <li>repeat the steps for all the appliances.</li>
                    </ul>
                </div>
                <div class="step">
                    <h2>Step 4: Copy configs to actual devices</h2>
                    <ul>
                        <li>in case you used offline programing configuration, please follow the following instructions,
                            if not
                            you can procced to the next step</li>
                        <li>
                            follow part-2 of step 1 to discover the devices and set the names
                        </li>
                        <li>configurations will be applied automatically once names of a physical device and a virtual
                            device match</li>
                    </ul>

                </div>
                <div class="step">
                    <h2>Step 5: Appliance puplishing</h2>
                    <ul>
                        <li>please note in order to be able to send the configurations to Home Assistant, <strong>you
                                must mapp all the appliances as in step-3</strong> or else you will see an error.</li>
                        <li>navigate to appliances tab and select <a href={{ backpack_url('appliance') }}>appliances</a>
                        <li>press on publish to home assistant button to send the configurations</li>
                        <li>in case you wanted to send the configurations to a specific/remote url, navigate to settings
                            and modify the server url record</li>
                    </ul>
                </div>
                <!-- Add steps here -->
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
</body>

</html>
