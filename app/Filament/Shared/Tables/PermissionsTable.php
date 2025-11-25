<?php

namespace App\Filament\Shared\Tables;

use App\Contracts\Filament\SharedFilamentTable;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\ColumnGroup;
use Filament\Tables\Columns\TextColumn;

class PermissionsTable implements SharedFilamentTable
{

    public static function getBase(array $extraFields = [], bool $includeRelationshipFields = false): array
    {
        return [
            ColumnGroup::make('Permissões', [
                TextColumn::make('name')
                    ->label('Nome')
                    ->searchable()
                    ->sortable()
                    ->icon(Heroicon::OutlinedKey),
                TextColumn::make('key')
                    ->label('Chave')
                    ->searchable()
                    ->copyable()
                    ->badge()
                    ->color('gray'),
                TextColumn::make('description')
                    ->label('Descrição')
                    ->limit(50)
                    ->wrap()
                    ->placeholder('—')
                    ->toggleable(isToggledHiddenByDefault: true),
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
                TextColumn::make('roles_count')
                    ->label('Papéis')
                    ->counts('roles')
                    ->badge()
                    ->color('success'),
            ]),
        ];
    }

    public static function getFilters(array $extraFilters = []): array
    {
        return [];
    }
}
