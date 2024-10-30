<?php
return [
    'server_url' => env('LICENSE_SERVER_URL', 'https://localhost:8000/verify'),
    'api_key' => env('LICENSE_SERVER_API_KEY', ''),
    'check_interval' => env('LICENSE_CHECK_INTERVAL', 60), // Cache duration in minutes
];
