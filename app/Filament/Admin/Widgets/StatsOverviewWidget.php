<?php

namespace App\Filament\Admin\Widgets;

use App\Enums\ProjectStatusEnum;
use App\Models\Skill;
use Filament\Support\Icons\Heroicon;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Company;
use App\Models\User;
use App\Models\Project;

class StatsOverviewWidget extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total de Empresas', Company::count())
                ->description('Empresas cadastradas no sistema')
                ->descriptionIcon('heroicon-m-building-office')
                ->color('success')
                ->chart([7, 3, 4, 5, 6, 3, 5, 3]),

            Stat::make('Total de Usuários', User::count())
                ->description('Usuários ativos no sistema')
                ->descriptionIcon('heroicon-m-users')
                ->color('primary'),

            Stat::make('Projetos Ativos', Project::where('status', ProjectStatusEnum::ACTIVE)->count())
                ->description('Projetos em andamento')
                ->descriptionIcon('heroicon-m-briefcase')
                ->color('warning'),

            Stat::make('Habilidades', Skill::count())
                ->description('Habilidades cadastradas no sistema')
                ->descriptionIcon(Heroicon::AcademicCap)
                ->color('danger'),
        ];
    }
}

