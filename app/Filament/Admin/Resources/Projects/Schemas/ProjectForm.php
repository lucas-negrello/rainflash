<?php

namespace App\Filament\Admin\Resources\Projects\Schemas;

use App\Filament\Shared\Schemas\ProjectSchema;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ProjectForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informações do Projeto')
                    ->schema(ProjectSchema::getBase())
                    ->columns(2),
            ])->columns(1);
    }
}

