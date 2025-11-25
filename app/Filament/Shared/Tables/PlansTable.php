<?php

namespace App\Filament\Shared\Tables;

use App\Contracts\Filament\SharedFilamentTable;
use Filament\Tables\Columns\ColumnGroup;
use Filament\Tables\Columns\TextColumn;

class PlansTable implements SharedFilamentTable
{

    public static function getBase(array $extraFields = [], bool $includeRelationshipFields = false): array
    {
        return [
            ColumnGroup::make('Plano', [
                TextColumn::make('name')
                    ->label('Nome')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('key')
                    ->label('Chave')
                    ->badge()
                    ->color('gray')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->toggleable(),

                TextColumn::make('price_monthly')
                    ->label('Preço Mensal')
                    ->money('BRL')
                    ->sortable(),

                TextColumn::make('currency')
                    ->label('Moeda')
                    ->badge()
                    ->searchable()
                    ->sortable(),
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
                TextColumn::make('features_count')
                    ->label('Features')
                    ->badge()
                    ->color('success')
                    ->counts('features')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('companies_count')
                    ->label('Empresas')
                    ->badge()
                    ->color('success')
                    ->counts('companies')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ]),
        ];
    }

    public static function getFilters(array $extraFilters = []): array
    {
        return [];
    }
}
