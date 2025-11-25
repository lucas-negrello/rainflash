<?php

namespace App\Filament\Shared\Tables;

use App\Contracts\Filament\SharedFilamentTable;
use Filament\Tables\Columns\TextColumn;

class AssignmentsTable implements SharedFilamentTable
{
    public static function getBase(array $extraFields = [], bool $includeRelationshipFields = false): array
    {
        return [
            TextColumn::make('companyUser.user.name')
                ->label('Usuário')
                ->searchable()
                ->sortable()
                ->icon('heroicon-o-user'),

            TextColumn::make('effective_from')
                ->label('Início')
                ->date('d/m/Y')
                ->sortable(),

            TextColumn::make('effective_to')
                ->label('Término')
                ->date('d/m/Y')
                ->placeholder('Em andamento')
                ->sortable(),

            TextColumn::make('weekly_capacity_hours')
                ->label('Cap. Semanal')
                ->numeric(decimalPlaces: 1)
                ->suffix(' h')
                ->sortable()
                ->toggleable(),

            TextColumn::make('hour_rate_override')
                ->label('Taxa/Hora')
                ->money('BRL')
                ->placeholder('—')
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),

            TextColumn::make('price_rate_override')
                ->label('Taxa/Preço')
                ->money('BRL')
                ->placeholder('—')
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),

            TextColumn::make('created_at')
                ->label('Criado em')
                ->dateTime('d/m/Y H:i')
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),
        ];
    }

    public static function getFilters(array $extraFilters = []): array
    {
        return [];
    }
}

