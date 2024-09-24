{{-- This file is used for menu items by any Backpack v6 theme --}}
<li class="nav-item"><a class="nav-link" href="{{ backpack_url('dashboard') }}"><i class="la la-stream nav-icon"></i>
        {{ trans('backpack::base.dashboard') }}</a></li>

<x-backpack::menu-dropdown title="Devices" icon="la la-clipboard-list">
    <x-backpack::menu-dropdown-item title="virtual devices" icon="la la-code" :link="backpack_url('virtual-device')" />
    <x-backpack::menu-dropdown-item title="Devices" icon="la la-server" :link="backpack_url('device')" />
    <!-- <x-backpack::menu-dropdown-item title="Device types" icon="la la-clipboard-list" :link="backpack_url('device-type')" /> -->
    <!-- <x-backpack::menu-dropdown-item title="Default appliances" icon="la la-question" :link="backpack_url('default-appliance')" /> -->

</x-backpack::menu-dropdown>

<x-backpack::menu-dropdown title="Appliances" icon="la la-plug">
    <x-backpack::menu-dropdown-item title="Appliances" icon="la la-plug" :link="backpack_url('appliance')" />
    <x-backpack::menu-dropdown-item title="Appliance channels" icon="la la-link" :link="backpack_url('appliance-channels')" />
    <!-- <x-backpack::menu-dropdown-item title="Appliance types" icon="la la-cogs" :link="backpack_url('appliance-type')" /> -->
    <!-- <x-backpack::menu-dropdown-item title="Default appliance channels" icon="la la-question" :link="backpack_url('default-appliance-channel')" /> -->

</x-backpack::menu-dropdown>

<x-backpack::menu-item title="Handover Sheet" icon="la la-file" :link="backpack_url('handover')" />
<x-backpack::menu-item title="Settings" icon="la la-gear" :link="backpack_url('settings')" />
{{-- <x-backpack::menu-item title="Users" icon="la la-user" :link="backpack_url('user')" /> --}}

{{-- <x-backpack::menu-item title="Devices Channels" icon="la la-toggle-on" :link="backpack_url('channel')" /> --}}
