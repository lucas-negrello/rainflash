<?php

namespace App\Filament\Admin\Resources\Features\Tables;

use App\Filament\Shared\Tables\FeaturesTable as SharedFeaturesTable;
use Filament\Actions\EditAction;
use Filament\Support\Icons\Heroicon;
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
                    ->icon(Heroicon::OutlinedEye)
                    ->label('Visualizar'),
            ])
            ->columnManagerMaxHeight('300px')
            ->defaultPaginationPageOption(5)
            ->paginationPageOptions([5, 10, 25, 50, 100])
            ->defaultSort('created_at', 'desc');
    }
}
