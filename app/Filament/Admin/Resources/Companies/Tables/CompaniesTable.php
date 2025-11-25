<?php

namespace App\Filament\Admin\Resources\Companies\Tables;

use App\Filament\Shared\Tables\CompaniesTable as SharedCompaniesTable;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Table;

class CompaniesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns(SharedCompaniesTable::getBase())
            ->filters(SharedCompaniesTable::getFilters())
            ->filtersFormColumns(1)
            ->filtersFormSchema(fn (array $filters): array =>
            SharedCompaniesTable::getFiltersFormSchema($filters))
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
