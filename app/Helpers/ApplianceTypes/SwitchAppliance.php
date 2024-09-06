<?php

namespace App\Helpers\ApplianceTypes;

use App\Models\Appliance;

class SwitchAppliance implements ApplianceTypeInterface
{
    private $appliance;
    private $room;
    private $id;
    private $name;
    private $command_topic;
    private $config_topic;
    private $availability_topic;


    public function __construct(Appliance $appliance)
    {
        $this->appliance = $appliance;
        $this->room = $appliance->roomId->room_name;
        $this->id = $appliance->id;
        $this->name = $appliance->appliance_name;
        $this->command_topic = "home/{$this->room}/{$this->room}_switch{$this->id}/set";
        $this->config_topic = "homeassistant/switch/{$this->room}_switch{$this->id}/config";
        $this->availability_topic = "home/{$this->room}/{$this->room}_switch{$this->id}/availability";
    }

    public function formMqttPayload($appliance)
    {
        $payload = <<<EOT
        {
            "unique_id": "{$this->room}_switch{$this->id}",
            "name": "{$this->room} {$this->name}",
            "state_topic": "home/{$this->room}/{$this->room}_switch{$this->id}",
            "command_topic": "{$this->command_topic}",
            "availability_topic": "{$this->availability_topic}",
            "payload_on": "ON",
            "payload_off": "OFF",
            "state_on": "ON",
            "state_off": "OFF",
            "optimistic": false,
            "qos": 0,
            "retain": true
        }
        EOT;
        return [
            "config_topic" => $this->config_topic,
            "availability_topic" => $this->availability_topic,
            "command_topic" => $this->command_topic,
            "state_topic" => "home/{$this->room}/{$this->room}_switch{$this->id}",
            "payload" => $payload
        ];
    }
}
