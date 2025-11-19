<?php

namespace App\Filament\Admin\Widgets;

use App\Enums\ProjectStatusEnum;
use App\Filament\Admin\Resources\Companies\CompanyResource;
use App\Filament\Admin\Resources\Skills\SkillResource;
use App\Filament\Admin\Resources\Users\UserResource;
use App\Models\Skill;
use Filament\Actions\Action;
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
                ->url(CompanyResource::getUrl('index'))
                ->columnSpan([ 'sm' => 'full' , 'xl' => 2 ])
                ->chart([7, 3, 4, 5, 6, 3, 5, 3]),

            Stat::make('Total de Usuários', User::count())
                ->description('Usuários ativos no sistema')
                ->descriptionIcon('heroicon-m-users')
                ->url(UserResource::getUrl('index'))
                ->columnSpan([ 'sm' => 'full' , 'xl' => 2 ])
                ->color('primary'),

            Stat::make('Projetos Ativos', Project::where('status', ProjectStatusEnum::ACTIVE)->count())
                ->description('Projetos em andamento')
                ->descriptionIcon('heroicon-m-briefcase')
//                ->url(CompanyResource::getUrl('index'))
                ->columnSpan([ 'sm' => 'full' , 'xl' => 2 ])
                ->color('warning'),

            Stat::make('Habilidades', Skill::count())
                ->description('Habilidades cadastradas no sistema')
                ->descriptionIcon(Heroicon::AcademicCap)
                ->url(SkillResource::getUrl('index'))
                ->columnSpan([ 'sm' => 'full' , 'xl' => 2 ])
                ->color('danger'),
        ];
    }
}

