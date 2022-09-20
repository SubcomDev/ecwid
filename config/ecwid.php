<?php
// config for subcom/Ecwid
return [
    'api_key'=> env('ECWID_API_KEY'),
    'endpoint_base'=> env('ECWID_ENDPOINT_BASE', 'https://my.ecwid.com/resellerapi'),
    'endpoint_version'=> env('ECWID_ENDPOINT_VERSION', 'v1/'),
   
];
