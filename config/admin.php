<?php

return [
    'features' => [
        'enable_plans_seeder' => env('ENABLE_PLANS_SEEDER', false),
    ],

    'user' => [
        'name' => env('ADMIN_NAME', 'Admin'),
        'email' => env('ADMIN_EMAIL', 'admin@example.com'),
        'password' => env('ADMIN_PASSWORD', 'password'),
        'timezone' => env('ADMIN_TIMEZONE', 'UTC-3'),
        'locale' => env('ADMIN_LOCALE', 'pt_BR'),
    ],

    'company' => [
        'name' => env('ADMIN_COMPANY_NAME', 'RainFlash'),
        'slug' => env('ADMIN_COMPANY_SLUG', 'rainflash'),
        'currency' => env('ADMIN_COMPANY_CURRENCY', 'BRL'),
    ],

    'plans' => [
        [
            'name'          => 'Básico',
            'key'           => 'basic',
            'price_monthly' => 39.99,
            'currency'      => 'BRL',
        ],
        [
            'name'          => 'Profissional',
            'key'           => 'professional',
            'price_monthly' => 99.99,
            'currency'      => 'BRL',
        ],
        [
            'name'          => 'Empresarial',
            'key'           => 'enterprise',
            'price_monthly' => 149.99,
            'currency'      => 'BRL',
        ],
        [
            'name'          => 'Corporativo',
            'key'           => 'corporate',
            'price_monthly' => 289.99,
            'currency'      => 'BRL',
        ],
        [
            'name'          => 'Ilimitado',
            'key'           => 'unlimited',
            'price_monthly' => 899.99,
            'currency'      => 'BRL',
        ]
    ]
];

