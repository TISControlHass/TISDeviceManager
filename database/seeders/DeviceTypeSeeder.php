<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\DeviceType;

class DeviceTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $known_types =
            [
                [
                    'device_type_name' => 'TIS-DMX-48',
                    'device_description' => 'TIS DMX 48CH Controller',
                    'device_model_number' => '0,32',
                ],
                [
                    'device_type_name' => 'TIS-4DI-IN',
                    'device_description' => '4 Zone Dry Contact DIGITAL INPUT',
                    'device_model_number' => '0,118',
                ],
                [
                    'device_type_name' => 'HVAC6-3A-T',
                    'device_description' => 'HVAC,VAV Air Condition Module',
                    'device_model_number' => '0,119',
                ],
                [
                    'device_type_name' => 'TIS-PIR-CM',
                    'device_description' => 'Ceiling PIR Sensor',
                    'device_model_number' => '0,133',
                ],
                [
                    'device_type_name' => 'TIS-IR-CUR',
                    'device_description' => 'IR Emitter WITH CURRENT SENSOR',
                    'device_model_number' => '1,50',
                ],
                [
                    'device_type_name' => 'ES-10F-CM',
                    'device_description' => '10 Functions Sensor',
                    'device_model_number' => '1,53',
                ],
                [
                    'device_type_name' => 'RLY-4CH-10A',
                    'device_description' => 'Relay 4ch 10A',
                    'device_model_number' => '26,168',
                ],
                [
                    'device_type_name' => 'VLC-6CH-3A',
                    'device_description' => 'Valve Lighting Controler 6CH 3A',
                    'device_model_number' => '26,170',
                ],
                [
                    'device_type_name' => 'RLY-8CH-16A',
                    'device_description' => 'Relay 8ch 16A',
                    'device_model_number' => '26,172',
                ],
                [
                    'device_type_name' => 'VLC-12CH-10A',
                    'device_description' => 'Valve Lighting Controler 12CH 10A',
                    'device_model_number' => '27,184',
                ],
                [
                    'device_type_name' => 'DIM-6CH-2A',
                    'device_description' => 'Dimmer 6ch 2A',
                    'device_model_number' => '2,88',
                ],
                [
                    'device_type_name' => 'DIM-4CH-3A',
                    'device_description' => 'Dimmer 4CH 3A',
                    'device_model_number' => '2,89',
                ],
                [
                    'device_type_name' => 'DIM-2CH-6A',
                    'device_description' => 'Dimmer 2CH 6A',
                    'device_model_number' => '37,90',
                ],
                [
                    'device_type_name' => 'TIS-AUT-TMR',
                    'device_description' => 'AUTOMATION TIMER MODULE',
                    'device_model_number' => '4,84',
                ],
                [
                    'device_type_name' => 'IP-COM-PORT-OLD',
                    'device_description' => 'IP COM PORT GATEWAY',
                    'device_model_number' => '75,177',
                ],
                [
                    'device_type_name' => 'TIS-SEC-SM',
                    'device_description' => 'SECURITY MODULE',
                    'device_model_number' => '190,233',
                ],
                [
                    'device_type_name' => 'TER-4G',
                    'device_description' => 'Terre 4G',
                    'device_model_number' => '23,112',
                ],
                [
                    'device_type_name' => 'TER-ACT',
                    'device_description' => 'Terre AC',
                    'device_model_number' => '23,122',
                ],
                [
                    'device_type_name' => 'TER-AUD',
                    'device_description' => 'Terre MUSIC',
                    'device_model_number' => '23,132',
                ],
                [
                    'device_type_name' => 'MRS-4G',
                    'device_description' => 'MARS 4 Buttons',
                    'device_model_number' => '27,128',
                ],
                [
                    'device_type_name' => 'MRS-8G',
                    'device_description' => 'MARS 8 Buttons',
                    'device_model_number' => '27,138',
                ],
                [
                    'device_type_name' => 'MRS-12G',
                    'device_description' => 'MARS 12 Buttons',
                    'device_model_number' => '27,148',
                ],
                [
                    'device_type_name' => 'MRS-AC10G',
                    'device_description' => 'MARS AC THERMOSTAT 10 BUTTONS',
                    'device_model_number' => '27,158',
                ],
                [
                    'device_type_name' => 'DALI-64',
                    'device_description' => 'TIS DALI 64CH Controller',
                    'device_model_number' => '27,168',
                ],
                [
                    'device_type_name' => 'TIS-DIM-4CH-1A',
                    'device_description' => 'Dimmer 4CH 1A',
                    'device_model_number' => '27,178',
                ],
                [
                    'device_type_name' => 'DIM-TE-2CH-3A',
                    'device_description' => 'TE Dimmer 2CH 3A',
                    'device_model_number' => '27,180',
                ],
                [
                    'device_type_name' => 'DIM-TE-4CH-1.5A',
                    'device_description' => 'TE Dimmer 4CH 1.5A',
                    'device_model_number' => '27,182',
                ],
                [
                    'device_type_name' => 'RCU-8OUT-8IN',
                    'device_description' => 'Room Control Unit 8 In 8 Out',
                    'device_model_number' => '27,186',
                ],
                [
                    'device_type_name' => 'RLY-6CH-0-10V',
                    'device_description' => 'Dimmer 6CH 0-10 Volte',
                    'device_model_number' => '27,187',
                ],
                [
                    'device_type_name' => 'LUNA-TFT-43',
                    'device_description' => 'LUNA TFT TOUCH SCREEN 4.3',
                    'device_model_number' => '35,50',
                ],
                [
                    'device_type_name' => 'LUNA-9GANGS',
                    'device_description' => 'LUNA 9 GANGS TOUCH PANEL',
                    'device_model_number' => '35,150',
                ],
                [
                    'device_type_name' => 'LUNA-BEDSIDE',
                    'device_description' => 'LUNA BED SIDE TOUCH PANEL',
                    'device_model_number' => '35,250',
                ],
                [
                    'device_type_name' => 'LUNA-BELL-3S',
                    'device_description' => 'HOTEL BELL WITH SERVICES',
                    'device_model_number' => '36,94',
                ],
                [
                    'device_type_name' => 'TIS-M3-MOTOR',
                    'device_description' => 'TIS M3 Motor',
                    'device_model_number' => '128,16',
                ],
                [
                    'device_type_name' => 'IO-8G',
                    'device_description' => 'IO 8G Panel',
                    'device_model_number' => '128,19',
                ],
                [
                    'device_type_name' => 'IO-AC-4G',
                    'device_description' => 'IO AC Panel with 4G',
                    'device_model_number' => '128,20',
                ],
                [
                    'device_type_name' => 'VEN-6S-BUS',
                    'device_description' => 'Venera Switch 6CH',
                    'device_model_number' => '128,21',
                ],
                [
                    'device_type_name' => 'TIT-2G-BUS',
                    'device_description' => 'Titan Switch 2CH',
                    'device_model_number' => '128,23',
                ],
                [
                    'device_type_name' => 'TIT-3G-BUS',
                    'device_description' => 'Titan Switch 3CH',
                    'device_model_number' => '128,24',
                ],
                [
                    'device_type_name' => 'TIT-4G-BUS',
                    'device_description' => 'Titan Switch 4CH',
                    'device_model_number' => '128,25',
                ],
                [
                    'device_type_name' => 'TARIQ-8G6R5Z',
                    'device_description' => 'TIS TARIQ 8Gang 6Relay 5Zone',
                    'device_model_number' => '128,26',
                ],
                [
                    'device_type_name' => 'TARIQ-8G3R5Z1F',
                    'device_description' => 'TIS TARIQ 8Gang 3Relay 5Zone 1Fan',
                    'device_model_number' => '128,27',
                ],
                [
                    'device_type_name' => 'TARIQ-8G3R5Z2D',
                    'device_description' => 'TIS TARIQ 8Gang 3Relay 5Zone 2Dim',
                    'device_model_number' => '128,28',
                ],
                [
                    'device_type_name' => 'TIS-HEALTH-CM',
                    'device_description' => 'TIS Health Sensor',
                    'device_model_number' => '128,34',
                ],
                [
                    'device_type_name' => 'VEN-2S-BUS',
                    'device_description' => 'Venera Switch 2CH',
                    'device_model_number' => '128,36',
                ],
                [
                    'device_type_name' => 'VEN-3S-BUS',
                    'device_description' => 'Venera Switch 3CH',
                    'device_model_number' => '128,37',
                ],
                [
                    'device_type_name' => 'VEN-4S-BUS',
                    'device_description' => 'Venera Switch 4CH',
                    'device_model_number' => '128,38',
                ],
                [
                    'device_type_name' => 'VEN-AC-3R-HC-BUS',
                    'device_description' => 'Venera Thermostat With 3 Relay',
                    'device_model_number' => '128,39',
                ],
                [
                    'device_type_name' => 'VEN-AC-4R-HC-BUS',
                    'device_description' => 'Venera Thermostat With 4 Relay',
                    'device_model_number' => '128,40',
                ],
                [
                    'device_type_name' => 'VEN-AC-5R-LC-BUS',
                    'device_description' => 'Venera Thermostat With 5 Relay',
                    'device_model_number' => '128,41',
                ],
                [
                    'device_type_name' => 'RCU-24R20Z',
                    'device_description' => 'Room Control Unit 20 In 24 Out',
                    'device_model_number' => '128,43',
                ],
                [
                    'device_type_name' => 'TIT-TFT-BUS',
                    'device_description' => 'Titan BUS TOUCH SCREEN 2.4',
                    'device_model_number' => '128,44',
                ],
                [
                    'device_type_name' => 'RCU-20R20Z-IP',
                    'device_description' => 'Room Control Unit 20 In 20 Out',
                    'device_model_number' => '128,45',
                ],
                [
                    'device_type_name' => 'ACM-1D-2Z',
                    'device_description' => 'ACM 1 Dimmer With 2 Zone',
                    'device_model_number' => '128,46',
                ],
                [
                    'device_type_name' => 'TIS-4T-IN',
                    'device_description' => '4 Temperature Sensor',
                    'device_model_number' => '128,47',
                ],
                [
                    'device_type_name' => 'DIM-W06CH10A-TE',
                    'device_description' => 'TE Industrial Dimmer 6ch 10A',
                    'device_model_number' => '128,48',
                ],
                [
                    'device_type_name' => 'DIM-W12CH10A-TE',
                    'device_description' => 'TE Industrial Dimmer 12CH 10A',
                    'device_model_number' => '128,49',
                ],
                [
                    'device_type_name' => 'MINI-AIR-AUTO-IRE-T',
                    'device_description' => 'Mini AIR Emitter With Auto Infrared',
                    'device_model_number' => '128,54',
                ],
                [
                    'device_type_name' => 'BUS-PIR-CM',
                    'device_description' => 'BUS PIR Motion Sensor',
                    'device_model_number' => '128,55',
                ],
                [
                    'device_type_name' => 'BUS-ES-IR',
                    'device_description' => 'BUS Energy Servant Sensor With Auto Infrared',
                    'device_model_number' => '128,56',
                ],
                [
                    'device_type_name' => 'ADS-3R-BUS',
                    'device_description' => 'ADS 3R BUS',
                    'device_model_number' => '128,59',
                ],
                [
                    'device_type_name' => 'ACM-3Z-IN',
                    'device_description' => 'AIR Coupler Module With 3 Zone',
                    'device_model_number' => '128,60',
                ],
                [
                    'device_type_name' => 'AIR-AUTO-IRE-T',
                    'device_description' => 'AIR Emitter With Auto Infrared',
                    'device_model_number' => '128,61',
                ],
                [
                    'device_type_name' => 'BUS-AUTO-IRE-T',
                    'device_description' => 'BUS Emitter With Auto Infrared',
                    'device_model_number' => '128,62',
                ],
                [
                    'device_type_name' => 'AIR-ES-IR',
                    'device_description' => 'AIR Energy Servant Sensor Witch Auto Infrared',
                    'device_model_number' => '128,63',
                ],
                [
                    'device_type_name' => 'ADS-1D-1Z',
                    'device_description' => 'ADS 1CH TE Dimmer With 1 Zone Input',
                    'device_model_number' => '128,64',
                ],
                [
                    'device_type_name' => 'ADS-2R-2Z',
                    'device_description' => 'ADS 2 Relay 2 Zone Input',
                    'device_model_number' => '128,65',
                ],
                [
                    'device_type_name' => 'AIR-PIR-CM',
                    'device_description' => 'TIS AIR PIR Motion Sensor',
                    'device_model_number' => '128,66',
                ],
                [
                    'device_type_name' => 'VEN-4S-4R-HC',
                    'device_description' => 'Venera Switch 4CH',
                    'device_model_number' => '128,67',
                ],
                [
                    'device_type_name' => 'VEN-AC-5R-LC',
                    'device_description' => 'Venera Thermostat With 5 Relay',
                    'device_model_number' => '128,68',
                ],
                [
                    'device_type_name' => 'VEN-AC-4R-HC',
                    'device_description' => 'Venera Thermostat With 4 Relay',
                    'device_model_number' => '128,69',
                ],
                [
                    'device_type_name' => 'AIR-1IRE-T',
                    'device_description' => 'Air Infrared  Emitter With Temperature',
                    'device_model_number' => '128,70',
                ],
                [
                    'device_type_name' => 'AIR-2IRE',
                    'device_description' => 'Air Infrared 2 Emitter',
                    'device_model_number' => '128,72',
                ],
                [
                    'device_type_name' => 'ACM-2R-2Z',
                    'device_description' => 'AIR Coupler Module With 2 Relay 2 Zone',
                    'device_model_number' => '128,75',
                ],
                [
                    'device_type_name' => 'VEN-2S-2R-HC',
                    'device_description' => 'Venera Switch 2CH',
                    'device_model_number' => '128,76',
                ],
                [
                    'device_type_name' => 'VEN-AC-3R-HC',
                    'device_description' => 'Venera Thermostat With 3 Relay',
                    'device_model_number' => '128,77',
                ],
                [
                    'device_type_name' => 'ADS-4CH-0-10V',
                    'device_description' => 'ADS RGBW 0-10 Volte Dimmer',
                    'device_model_number' => '128,78',
                ],
                [
                    'device_type_name' => 'ADS-3R-3Z',
                    'device_description' => 'ADS 3 Relay 3 Zone Input',
                    'device_model_number' => '128,79',
                ],
                [
                    'device_type_name' => 'AMP-5S1Z-MTX',
                    'device_description' => 'Audio Matrix Player',
                    'device_model_number' => '128,80',
                ],
                [
                    'device_type_name' => 'AIR-SOCKET-S',
                    'device_description' => 'AIR Socket ON/OFF',
                    'device_model_number' => '128,81',
                ],
                [
                    'device_type_name' => 'VEN-1D-UV',
                    'device_description' => 'Venera Universal Dimmer 1Ch',
                    'device_model_number' => '128,82',
                ],
                [
                    'device_type_name' => 'VEN-3S-3R-HC',
                    'device_description' => 'Venera Switch 3CH',
                    'device_model_number' => '128,83',
                ],
                [
                    'device_type_name' => 'TIS-AIR-BUS',
                    'device_description' => 'AIR BUS Convertor',
                    'device_model_number' => '128,84',
                ],
                [
                    'device_type_name' => 'PRJ-LFT-15K-130',
                    'device_description' => 'TIS Projector Lift',
                    'device_model_number' => '128,85',
                ],
                [
                    'device_type_name' => 'TIS-WS-71',
                    'device_description' => 'Weather Station Adaptor',
                    'device_model_number' => '128,87',
                ],
                [
                    'device_type_name' => 'IP-COM-PORT',
                    'device_description' => 'IP COM Port Gateway',
                    'device_model_number' => '128,88',
                ],
                [
                    'device_type_name' => 'MET-EN-1PH',
                    'device_description' => 'One Phase Energy Meter',
                    'device_model_number' => '128,96',
                ],
                [
                    'device_type_name' => 'TIS-KNX-PORT',
                    'device_description' => 'TIS & KNX Compatible Device',
                    'device_model_number' => '128,97',
                ],
                [
                    'device_type_name' => 'TIS-TRV-16CNV',
                    'device_description' => 'TIS TRV Controler 16CH',
                    'device_model_number' => '128,98',
                ],
                [
                    'device_type_name' => 'LUNA-IN-HOTEL-HRF',
                    'device_description' => 'Luna Hotel Indoor HRF Unit',
                    'device_model_number' => '128,100',
                ],
                [
                    'device_type_name' => 'LUNA-IN-HOTEL-3T3L-HRF',
                    'device_description' => 'Luna Hotel Indoor 3T3L HRF Unit',
                    'device_model_number' => '128,101',
                ],
                [
                    'device_type_name' => 'IO-IN-HOTEL-HRF',
                    'device_description' => 'IO Hotel Indoor 3B3L HRF Unit',
                    'device_model_number' => '128,102',
                ],
                [
                    'device_type_name' => 'LUNA-OUT-HOTEL-HRF',
                    'device_description' => 'Luna Hotel Outdoor 1T3L HRF Unit',
                    'device_model_number' => '128,103',
                ],
                [
                    'device_type_name' => 'LUNA-OUT-HOTEL',
                    'device_description' => 'Luna Hotel Outdoor 1T3L Unit',
                    'device_model_number' => '128,104',
                ],
                [
                    'device_type_name' => 'IO-OUT-HOTEL-HRF',
                    'device_description' => 'IO Hotel Outdoor 1B2L HRF Unit',
                    'device_model_number' => '128,105',
                ],
                [
                    'device_type_name' => 'IO-OUT-HOTEL',
                    'device_description' => 'IO Hotel Outdoor 1B2L Unit',
                    'device_model_number' => '128,106',
                ],
                [
                    'device_type_name' => 'TIS-MER-8G-PB',
                    'device_description' => 'TIS Mercury 8G Panel',
                    'device_model_number' => '128,107',
                ],
                [
                    'device_type_name' => 'TIS-MER-AC4G-PB',
                    'device_description' => 'TIS Mercury AC Panel with 4G',
                    'device_model_number' => '128,108',
                ],
                [
                    'device_type_name' => 'IO-IN-HOTEL-LRF',
                    'device_description' => 'IO Hotel Indoor 3B3L LRF Unit',
                    'device_model_number' => '128,109',
                ],
                [
                    'device_type_name' => 'IO-OUT-HOTEL-LRF',
                    'device_description' => 'IO Hotel Outdoor 1B2L LRF Unit',
                    'device_model_number' => '128,110',
                ],
                [
                    'device_type_name' => 'LUNA-IN-HOTEL-LRF',
                    'device_description' => 'Luna Hotel Indoor LRF Unit',
                    'device_model_number' => '128,111',
                ],
                [
                    'device_type_name' => 'LUNA-OUT-HOTEL-LRF',
                    'device_description' => 'Luna Hotel Outdoor 1T3L LRF Unit',
                    'device_model_number' => '128,112',
                ],
                [
                    'device_type_name' => 'LUNA-IN-HOTEL-3T3L-LRF',
                    'device_description' => 'Luna Hotel Indoor 3T3L LRF Unit',
                    'device_model_number' => '128,113',
                ],
                [
                    'device_type_name' => 'TIS-ZIG-PORT',
                    'device_description' => 'TIS Zigbee Home Automation Converter',
                    'device_model_number' => '128,122',
                ],
                [
                    'device_type_name' => 'TIS-AUD-SRV-4X-160W',
                    'device_description' => 'TIS Audio Server 4 ZONE',
                    'device_model_number' => '128,123',
                ],
                [
                    'device_type_name' => 'TIS-M7-CURTAIN',
                    'device_description' => 'Curtain Motor',
                    'device_model_number' => '128,124',
                ],
                [
                    'device_type_name' => 'VEN-2G-HC-BUS-B',
                    'device_description' => 'Venera Switch 2CH',
                    'device_model_number' => '128,126',
                ],
                [
                    'device_type_name' => 'VEN-3G-HC-BUS-B',
                    'device_description' => 'Venera Switch 3CH',
                    'device_model_number' => '128,127',
                ],
                [
                    'device_type_name' => 'VEN-4G-HC-BUS-B',
                    'device_description' => 'Venera Switch 4CH',
                    'device_model_number' => '128,128',
                ],
                [
                    'device_type_name' => 'VEN-AC-3R-1.5-OLED-BUS',
                    'device_description' => 'Venera Thermostat With 3 Relay 1.5 OLED',
                    'device_model_number' => '128,129',
                ],
                [
                    'device_type_name' => 'VEN-AC-4R-1.5-OLED-BUS',
                    'device_description' => 'Venera Thermostat With 4 Relay 1.5 OLED',
                    'device_model_number' => '128,130',
                ],
                [
                    'device_type_name' => 'VEN-AC-5R-1.5-OLED-BUS',
                    'device_description' => 'Venera Thermostat With 5 Relay 1.5 OLED',
                    'device_model_number' => '128,131',
                ],
                [
                    'device_type_name' => 'VEN-AC-3R-1.5-OLED',
                    'device_description' => 'Venera Thermostat With 3 Relay 1.5 OLED',
                    'device_model_number' => '128,133',
                ],
                [
                    'device_type_name' => 'VEN-AC-4R-1.5-OLED',
                    'device_description' => 'Venera Thermostat With 4 Relay 1.5 OLED',
                    'device_model_number' => '128,134',
                ],
                [
                    'device_type_name' => 'VEN-AC-5R-1.5-OLED',
                    'device_description' => 'Venera Thermostat With 5 Relay 1.5 OLED',
                    'device_model_number' => '128,135',
                ],
                [
                    'device_type_name' => 'TIS-VRV-PRO',
                    'device_description' => 'TIS VRV Gateway Support 32 Indoor Units',
                    'device_model_number' => '128,137',
                ],
                [
                    'device_type_name' => 'VEN-2G-HC-AIR-A',
                    'device_description' => 'Venera Switch 2CH',
                    'device_model_number' => '128,139',
                ],
                [
                    'device_type_name' => 'VEN-3G-HC-AIR-A',
                    'device_description' => 'Venera Switch 3CH',
                    'device_model_number' => '128,140',
                ],
                [
                    'device_type_name' => 'VEN-4G-HC-AIR-A',
                    'device_description' => 'Venera Switch 4CH',
                    'device_model_number' => '128,141',
                ],
                [
                    'device_type_name' => 'TIS-BEDSIDE-12G',
                    'device_description' => 'TIS Bedside 12 Gang With Temperature',
                    'device_model_number' => '128,143',
                ],
                [
                    'device_type_name' => 'TIS-BUS-CONVERTER',
                    'device_description' => 'TIS BUS-RS485-RS232 Converter',
                    'device_model_number' => '128,144',
                ],
                [
                    'device_type_name' => 'TIS-4CH-AIN',
                    'device_description' => 'TIS Analog 4ch Input',
                    'device_model_number' => '128,145',
                ],
                [
                    'device_type_name' => 'TIS-OUTDOOR-BELL',
                    'device_description' => 'TIS Outdoor Bell',
                    'device_model_number' => '128,146',
                ],
                [
                    'device_type_name' => 'TIS-SOL-3G',
                    'device_description' => 'Sol Switch 3CH',
                    'device_model_number' => '128,147',
                ],
                [
                    'device_type_name' => 'MER-IN-HOTEL-LRF',
                    'device_description' => 'Mercury Hotel Indoor LRF Unit',
                    'device_model_number' => '128,148',
                ],
                [
                    'device_type_name' => 'TER-2G',
                    'device_description' => 'Terre 2G',
                    'device_model_number' => '128,149',
                ],
                [
                    'device_type_name' => 'TIS-SOL-TFT',
                    'device_description' => 'TIS SOL TFT Pannel',
                    'device_model_number' => '128,150',
                ],
                [
                    'device_type_name' => 'MER-OUT-HOTEL-LRF',
                    'device_description' => 'Mercury Hotel Outdoor LRF Unit',
                    'device_model_number' => '128,152',
                ],
                [
                    'device_type_name' => 'TIS-GTY-1AC',
                    'device_description' => 'TIS VRV 1 AC',
                    'device_model_number' => '128,153',
                ],
                [
                    'device_type_name' => 'IO-OUT-HOTEL-HRF-809A',
                    'device_description' => 'IO Hotel Outdoor 1B2L HRF Unit',
                    'device_model_number' => '128,154',
                ],
                [
                    'device_type_name' => 'IO-OUT-HOTEL-809B',
                    'device_description' => 'IO Hotel Outdoor 1B2L Unit',
                    'device_model_number' => '128,155',
                ],
                [
                    'device_type_name' => 'LUNA-OUT-HOTEL-HRF-809C',
                    'device_description' => 'Luna Hotel Outdoor 1T3L HRF Unit',
                    'device_model_number' => '128,156',
                ],
                [
                    'device_type_name' => 'LUNA-OUT-HOTEL-809D',
                    'device_description' => 'Luna Hotel Outdoor 1T3L Unit',
                    'device_model_number' => '128,157',
                ],
                [
                    'device_type_name' => 'IO-IN-HOTEL-HRF-809E',
                    'device_description' => 'IO Hotel Indoor 3B3L HRF Unit',
                    'device_model_number' => '128,158',
                ],
                [
                    'device_type_name' => 'CLICK-1G-PANEL-BUS',
                    'device_description' => 'Click-Push Button',
                    'device_model_number' => '128,161',
                ],
                [
                    'device_type_name' => 'CLICK-2G-PANEL-BUS',
                    'device_description' => 'Click-Push Button',
                    'device_model_number' => '128,162',
                ],
                [
                    'device_type_name' => 'CLICK-3G-PANEL-BUS',
                    'device_description' => 'Click-Push Button',
                    'device_model_number' => '128,163',
                ],
                [
                    'device_type_name' => 'CLICK-4G-PANEL-BUS',
                    'device_description' => 'Click-Push Button',
                    'device_model_number' => '128,164',
                ],
                [
                    'device_type_name' => 'CLICK-6G-PANEL-BUS',
                    'device_description' => 'Click-Push Button',
                    'device_model_number' => '128,166',
                ],
                [
                    'device_type_name' => 'TIS-SEC-PRO',
                    'device_description' => 'SMS Security',
                    'device_model_number' => '128,167',
                ],
                [
                    'device_type_name' => 'TIS-CLICK-AC-BUS',
                    'device_description' => 'TIS Click Thermostat Bus',
                    'device_model_number' => '128,168',
                ],
                [
                    'device_type_name' => 'TIS-22DI-DIN',
                    'device_description' => '22 Zone Dry Contact DIGITAL INPUT',
                    'device_model_number' => '128,169',
                ],
                [
                    'device_type_name' => 'MER-IN-HOTEL-HRF-0x80AA',
                    'device_description' => 'Hotel Indoor HRF Unit',
                    'device_model_number' => '128,170',
                ],
                [
                    'device_type_name' => 'TIS-4AI-010V',
                    'device_description' => 'TIS Analog 4ch Input Voltage',
                    'device_model_number' => '128,171',
                ],
                [
                    'device_type_name' => 'TIS-4AI-4-20MA',
                    'device_description' => 'TIS Analog 4ch Input Current',
                    'device_model_number' => '128,172',
                ],
                [
                    'device_type_name' => 'MER-OUT-HOTEL-HRF-0x80AD',
                    'device_description' => 'Hotel Outdoor 1T2L HRF Uint',
                    'device_model_number' => '128,173',
                ],
                [
                    'device_type_name' => 'TIS-HEALTH-CM-RADAR',
                    'device_description' => 'TIS Health Sensor Radar',
                    'device_model_number' => '128,174',
                ],
                [
                    'device_type_name' => 'TIS-ZB-GATEWAY',
                    'device_description' => 'TIS ZB Gateway',
                    'device_model_number' => '128,175',
                ],
                [
                    'device_type_name' => 'TIS-RADAR-SENSOR',
                    'device_description' => 'TIS Sensor Radar',
                    'device_model_number' => '128,176',
                ],
                [
                    'device_type_name' => 'TIS-ERO-1G',
                    'device_description' => 'Europa-Push Button',
                    'device_model_number' => '128,177',
                ],
                [
                    'device_type_name' => 'TIS-ERO-2G',
                    'device_description' => 'Europa-Push Button',
                    'device_model_number' => '128,178',
                ],
                [
                    'device_type_name' => 'TIS-ERO-3G',
                    'device_description' => 'Europa-Push Button',
                    'device_model_number' => '128,179',
                ],
                [
                    'device_type_name' => 'TIS-ERO-4G',
                    'device_description' => 'Europa-Push Button',
                    'device_model_number' => '128,180',
                ],
                [
                    'device_type_name' => 'TIS-ERO-6G',
                    'device_description' => 'Europa-Push Button',
                    'device_model_number' => '128,182',
                ],
                [
                    'device_type_name' => 'TIS-CLICK-AC-FH-BUS',
                    'device_description' => 'TIS Click Thermostat Bus',
                    'device_model_number' => '128,183',
                ],
                [
                    'device_type_name' => 'TIS-C-BUS-CONVERTER',
                    'device_description' => 'TIS C-BUS-RS232 Converter',
                    'device_model_number' => '128,184',
                ],
                [
                    'device_type_name' => 'TIS-FAN-4CH',
                    'device_description' => 'Fan Control 4CH LMH',
                    'device_model_number' => '128,185',
                ],
                [
                    'device_type_name' => 'TIS-SIR-4G',
                    'device_description' => 'Sirius Push Button',
                    'device_model_number' => '128,196',
                ],
                [
                    'device_type_name' => 'TIS-SIR-6G',
                    'device_description' => 'Sirius Push Button',
                    'device_model_number' => '128,198',
                ],
                [
                    'device_type_name' => 'TIS-SIR-8G',
                    'device_description' => 'Sirius Push Button',
                    'device_model_number' => '128,200',
                ],
                [
                    'device_type_name' => 'TIS-TM-120',
                    'device_description' => 'TIS Roller Motor',
                    'device_model_number' => '129,16',
                ],


            ];

        // Truncate the device types table
        DeviceType::truncate();
        // iterate over known types
        foreach ($known_types as $deviceType) {
            // Create a new device type
            DeviceType::create($deviceType);
        }
    }
}
