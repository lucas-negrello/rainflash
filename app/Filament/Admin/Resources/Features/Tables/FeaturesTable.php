<?php

namespace App\Filament\Admin\Resources\Features\Tables;

use App\Filament\Shared\Tables\FeaturesTable as SharedFeaturesTable;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Table;

class FeaturesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns(SharedFeaturesTable::getBase())
            ->filters(SharedFeaturesTable::getFilters())
            ->recordActions([
                EditAction::make()
                    ->label('Editar'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->label('Excluir Selecionadas'),
                ]),
            ])
            ->columnManagerMaxHeight('300px')
            ->defaultPaginationPageOption(5)
            ->paginationPageOptions([5, 10, 25, 50, 100])
            ->defaultSort('created_at', 'desc');
    }
}
