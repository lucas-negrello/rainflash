<?php

namespace App\Filament\Admin\Resources\Projects\Tables;

use App\Filament\Shared\Tables\ProjectsTable as SharedProjectsTable;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Table;

class ProjectsTable
{
    public static function configure(Table $table): Table
    {
        $columns = SharedProjectsTable::getBase();
        $filters = SharedProjectsTable::getFilters();

        return $table
            ->columns($columns)
            ->filters($filters)
            ->recordActions([
                \Filament\Actions\Action::make('viewTasks')
                    ->label('Ver Tarefas')
                    ->icon('heroicon-o-clipboard-document-list')
                    ->color('info')
                    ->url(fn ($record) => \App\Filament\Admin\Resources\Tasks\TaskResource::getUrl('index', [
                        'filters' => [
                            'project_id' => ['value' => $record->id],
                        ],
                    ]))
                    ->visible(fn ($record) => $record->tasks()->exists()),
                EditAction::make()->label('Editar'),
                DeleteAction::make()->label('Excluir'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()->label('Excluir Selecionados'),
                ]),
            ])
            ->columnManagerMaxHeight('300px')
            ->defaultPaginationPageOption(5)
            ->paginationPageOptions([5, 10, 25, 50, 100])
            ->defaultSort('created_at', 'desc');
    }
}

