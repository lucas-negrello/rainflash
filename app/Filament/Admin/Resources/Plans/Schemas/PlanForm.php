<?php

namespace App\Filament\Admin\Resources\Plans\Schemas;

use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class PlanForm
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
                    ->helperText('Identificador único do plano (gerado automaticamente)'),

                TextInput::make('price_monthly')
                    ->label('Preço Mensal')
                    ->required()
                    ->numeric()
                    ->minValue(0)
                    ->step(0.01)
                    ->prefix('R$')
                    ->helperText('Valor em reais por mês'),

                Select::make('currency')
                    ->label('Moeda')
                    ->options([
                        'BRL' => 'Real (BRL)',
                        'USD' => 'Dólar (USD)',
                        'EUR' => 'Euro (EUR)',
                    ])
                    ->default('BRL')
                    ->required()
                    ->native(false),

                KeyValue::make('meta')
                    ->label('Metadados (opcional)')
                    ->keyLabel('Chave')
                    ->valueLabel('Valor')
                    ->default([])
                    ->dehydrateStateUsing(fn ($state) => empty($state) ? null : $state),
            ]);
    }
}
