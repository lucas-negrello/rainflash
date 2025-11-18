<?php

namespace App\Filament\User\Pages;

use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected static string $routePath = '/';

    public function getColumns(): int | array
    {
        return [
            'md' => 2,
            'xl' => 3,
        ];
    }
}

