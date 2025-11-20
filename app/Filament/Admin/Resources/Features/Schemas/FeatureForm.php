<?php

namespace App\Filament\Admin\Resources\Features\Schemas;

use App\Enums\FeatureTypeEnum;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class FeatureForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Nome')
                    ->required()
                    ->maxLength(255)
                    ->live(onBlur: true)
                    ->afterStateUpdated(function (Get $get, Set $set, ?string $state) {
                        if (!$get('key') && $state) {
                            $set('key', Str::slug($state));
                        }
                    }),

                TextInput::make('key')
                    ->label('Chave (Key)')
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true)
                    ->alphaDash()
                    ->helperText('Identificador único da feature (gerado automaticamente)'),

                Select::make('type')
                    ->label('Tipo')
                    ->options(FeatureTypeEnum::labels())
                    ->native(false)
                    ->required()
                    ->helperText('Boleano: sim/não | Limite: valor numérico | Nível: tier/categoria'),

                KeyValue::make('meta')
                    ->label('Metadados (opcional)')
                    ->keyLabel('Chave')
                    ->valueLabel('Valor')
                    ->default([])
                    ->dehydrateStateUsing(fn ($state) => empty($state) ? null : $state),
            ]);
    }
}
