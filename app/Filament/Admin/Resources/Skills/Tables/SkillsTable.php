<?php

namespace App\Filament\Admin\Resources\Skills\Tables;

use App\Filament\Shared\Tables\SkillsTable as SharedSkillsTable;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Tables\Table;

class SkillsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns(SharedSkillsTable::getBase())
            ->filters(SharedSkillsTable::getFilters())
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                DeleteBulkAction::make(),
            ])
            ->columnManagerMaxHeight('300px')
            ->defaultPaginationPageOption(5)
            ->paginationPageOptions([5, 10, 25, 50, 100])
            ->defaultSort('name', 'asc');
    }
}

