<?php

namespace App\Filament\Shared\Tables;

use App\Contracts\Filament\SharedFilamentTable;
use App\Enums\RoleScopeEnum;
use Filament\Tables\Columns\ColumnGroup;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;

class RolesTable implements SharedFilamentTable
{

    public static function getBase(array $extraFields = [], bool $includeRelationshipFields = false): array
    {
        return [
            ColumnGroup::make('Papel', [
                TextColumn::make('name')
                    ->label('Nome')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('key')
                    ->label('Chave')
                    ->searchable()
                    ->copyable()
                    ->badge()
                    ->color('gray'),

                TextColumn::make('scope')
                    ->label('Escopo')
                    ->formatStateUsing(fn ($state) => $state->label())
                    ->badge()
                    ->sortable(),

                TextColumn::make('description')
                    ->label('Descrição')
                    ->limit(50)
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->wrap(),
            ]),

            ColumnGroup::make('Datas', [
                TextColumn::make('created_at')
                    ->label('Criado em')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')
                    ->label('Atualizado em')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ]),

            ColumnGroup::make('Estatísticas', [
                TextColumn::make('permissions_count')
                    ->label('Permissões')
                    ->counts('permissions')
                    ->badge()
                    ->color('success'),
            ]),
        ];
    }

    public static function getFilters(array $extraFilters = []): array
    {
        return [
            SelectFilter::make('scope')
                ->label('Escopo')
                ->options(RoleScopeEnum::dropdownOptions())
                ->native(false),
        ];
    }
}
