<?php

namespace App\Filament\User\Widgets;

use Filament\Widgets\Widget;

class WelcomeWidget extends Widget
{
    protected string $view = 'filament.user.widgets.welcome-widget';

    protected int | string | array $columnSpan = 'full';
}
