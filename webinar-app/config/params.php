<?php
return [
    'adminEmail' => 'admin@example.com',
    'gotoWebinar' => [
        'client_id'     => '88b37fed-cfa6-4224-bca7-476453a0a8f8',
        'client_secret' => 'WH4yvUqrbBmBXozEKuZVxkGQ',
        'access_token'  => null, // will be loaded from file
        'refresh_token' => null, // will be loaded from file
        'organizer_key' => '8708430510053665565',
        'base_url'      => 'https://api.getgo.com/G2W/rest/v2',
        'token_file'    => '@runtime/gotowebinar_token.json', // file to store tokens
    ],
];
