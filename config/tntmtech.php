<?php

return [
    'email' => env('TNTMTECH_EMAIL', 'contact@tntmtech.cm'),
    'agencies' => [
        'douala' => [
            'name' => 'Douala',
            'phone' => '+237 676 38 81 35',
            'whatsapp' => env('TNTMTECH_WHATSAPP_DOUALA', '237676388135'),
            'description' => 'Conseil, assistance et interventions informatiques à Douala.',
        ],
        'yaounde' => [
            'name' => 'Yaoundé',
            'phone' => '+237 650 60 09 90',
            'whatsapp' => env('TNTMTECH_WHATSAPP_YAOUNDE', '237650600990'),
            'description' => 'Conseil, assistance et interventions informatiques à Yaoundé.',
        ],
        'france' => [
            'name' => 'Direction France',
            'phone' => '+33 7 56 99 22 82',
            'whatsapp' => env('TNTMTECH_WHATSAPP_FRANCE', '33756992282'),
            'description' => 'Direction, partenaires et demandes internationales.',
        ],
    ],
];
