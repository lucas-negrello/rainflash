<?php

namespace App\Filament\Admin\Resources\Roles\Tables;

use App\Filament\Shared\Tables\RolesTable as SharedRolesTable;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Tables\Table;

class RolesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns(SharedRolesTable::getBase())
            ->filters(SharedRolesTable::getFilters())
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->columnManagerMaxHeight('300px')
            ->defaultPaginationPageOption(5)
            ->paginationPageOptions([5, 10, 25, 50, 100])
            ->defaultSort('created_at', 'desc');
    }
}
