<?php

return [
    // Habilita o seed automático de admin e RBAC na migration de seed inicial
    'enabled' => env('ADMIN_SEED', true),

    // Se true, garante que a role admin (abaixo) receba todas as permissões disponíveis
    'grant_all_permissions_to_admin_role' => env('ADMIN_GRANT_ALL_PERMISSIONS', true),

    // Chave da role admin (precisa existir nos seeds desta migration)
    'admin_role_key' => env('ADMIN_ROLE_KEY', 'ceo'),

    'user' => [
        'name' => env('ADMIN_NAME', 'Admin'),
        'email' => env('ADMIN_EMAIL', 'admin@example.com'),
        // IMPORTANTE: troque esta senha via env ao subir em produção
        'password' => env('ADMIN_PASSWORD', 'password'),
        'timezone' => env('ADMIN_TIMEZONE', 'UTC-3'),
        'locale' => env('ADMIN_LOCALE', 'pt_BR'),
    ],

    'company' => [
        'name' => env('ADMIN_COMPANY_NAME', 'RainFlash'),
        'slug' => env('ADMIN_COMPANY_SLUG', 'rainflash'),
        'currency' => env('ADMIN_COMPANY_CURRENCY', 'BRL'),
    ],
];

