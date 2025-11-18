<?php

namespace App\Filament\Admin\Resources\Roles\Tables;

use App\Enums\RoleScopeEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class RolesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
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

                TextColumn::make('permissions_count')
                    ->label('Permissões')
                    ->counts('permissions')
                    ->badge()
                    ->color('success'),


                TextColumn::make('description')
                    ->label('Descrição')
                    ->limit(50)
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->wrap(),

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
            ])
            ->filters([
                SelectFilter::make('scope')
                    ->label('Escopo')
                    ->options(RoleScopeEnum::class)
                    ->native(false),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
