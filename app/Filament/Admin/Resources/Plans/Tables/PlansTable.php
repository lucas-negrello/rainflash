<?php

namespace App\Filament\Admin\Resources\Plans\Tables;

use App\Filament\Shared\Tables\PlansTable as SharedPlansTable;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Table;

class PlansTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns(SharedPlansTable::getBase())
            ->filters(SharedPlansTable::getFilters())
            ->recordActions([
                EditAction::make()
                    ->label('Editar'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->label('Excluir Selecionados'),
                ]),
            ])
            ->columnManagerMaxHeight('300px')
            ->defaultPaginationPageOption(5)
            ->paginationPageOptions([5, 10, 25, 50, 100])
            ->defaultSort('price_monthly', 'desc');
    }
}
