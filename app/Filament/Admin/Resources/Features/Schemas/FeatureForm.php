<?php

namespace App\Filament\Admin\Resources\Features\Schemas;

use App\Filament\Shared\Schemas\FeatureSchema;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class FeatureForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informações Básicas')
                    ->schema(FeatureSchema::getBase())
                    ->columns(2),
            ])->columns(1);
    }
}
