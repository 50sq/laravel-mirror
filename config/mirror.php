<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Mirrors
    |--------------------------------------------------------------------------
    |
    | Define the mirrors and any necessary configuration required to mirror
    | the user attributes.
    |
    */

    'mirrors' => [

        'freshdesk' => [
            'key' => env('FRESHDESK_API_KEY'),
        ],

        'hubspot' => [
            'key' => env('HUBSPOT_API_KEY'),
        ],

    ]

];