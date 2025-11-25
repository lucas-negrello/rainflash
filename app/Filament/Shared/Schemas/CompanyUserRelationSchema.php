<?php

namespace App\Filament\Shared\Schemas;

use App\Contracts\Filament\SharedFilamentSchema;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;

class CompanyUserRelationSchema implements SharedFilamentSchema
{
    public static function getBase(bool $useRelationshipFields = false): array
    {
        return [
            TextInput::make('primary_title')
                ->label('Cargo/Título')
                ->maxLength(255),

            Select::make('currency')
                ->label('Moeda')
                ->options([
                    'BRL' => 'Real (BRL)',
                    'USD' => 'Dólar (USD)',
                    'EUR' => 'Euro (EUR)',
                ])
                ->default('BRL'),

            Toggle::make('active')
                ->label('Ativo')
                ->default(true),

            DateTimePicker::make('joined_at')
                ->label('Data de entrada')
                ->displayFormat('d/m/Y')
                ->default(now()),

            DateTimePicker::make('left_at')
                ->label('Data de saída')
                ->displayFormat('d/m/Y'),

            KeyValue::make('meta')
                ->label('Metadados (opcional)')
                ->keyLabel('Chave')
                ->valueLabel('Valor')
                ->default([])
                ->dehydrateStateUsing(fn ($state) => empty($state) ? null : $state),
        ];
    }
}
