<?php

namespace App\Filament\Admin\Resources\Permissions\Tables;

use App\Filament\Shared\Tables\PermissionsTable as SharedPermissionsTable;
use Filament\Actions\EditAction;
use Filament\Tables\Table;

class PermissionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns(SharedPermissionsTable::getBase())
            ->filters(SharedPermissionsTable::getFilters())
            ->recordActions([
                EditAction::make()->label('Editar'),
            ])
            ->columnManagerMaxHeight('300px')
            ->defaultPaginationPageOption(5)
            ->paginationPageOptions([5, 10, 25, 50, 100])
            ->defaultSort('created_at', 'desc');
    }
}
