<?php

namespace App\Helpers\ApplianceTypes;

use App\Models\Appliance;

interface ApplianceTypeInterface
{
    public function formMqttPayload(Appliance $appliance);
}
