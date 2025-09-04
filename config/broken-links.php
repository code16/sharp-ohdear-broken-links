<?php
return [
    'api_token' => env('OH_DEAR_API_TOKEN'),
    'monitor_id' => env('OH_DEAR_MONITOR_ID', env('OH_DEAR_SITE_ID')),
];
