<?php

return [
    'time_out' => 86400,
    'redirect' => [
        # any port
        ['domain' => '127.0.1.1', 'redirect' => '127.0.0.1'],
        # some port
        ['domain' => '127.0.0.1', 'port' => '80', 'redirect' => '10.0.0.1'],
        ['domain' => '127.0.0.1', 'port' => '81', 'redirect' => '10.0.0.2'],
        ['domain' => '127.0.0.1', 'redirect' => '10.0.0.3'],
    ]
];