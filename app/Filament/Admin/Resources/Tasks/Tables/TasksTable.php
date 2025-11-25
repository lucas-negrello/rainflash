<?php

namespace App\Filament\Admin\Resources\Tasks\Tables;

use App\Filament\Shared\Tables\TasksTable as SharedTasksTable;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Table;

class TasksTable
{
    public static function configure(Table $table): Table
    {
        $columns = SharedTasksTable::getBase();
        $filters = SharedTasksTable::getFilters();

        return $table
            ->columns($columns)
            ->filters($filters)
            ->groups([
                \Filament\Tables\Grouping\Group::make('project.name')
                    ->label('Projeto')
                    ->collapsible()
                    ->titlePrefixedWithLabel(false)
                    ->getTitleFromRecordUsing(function ($record) {
                        $project = $record->project;

                        if (!$project) {
                            return 'Sem Projeto';
                        }

                        $estimatedMinutes = $project->tasks()->sum('estimated_minutes');
                        $estimatedHours = $estimatedMinutes ? number_format($estimatedMinutes / 60, 1) : '0';

                        $trackedMinutes = $project->timeEntries()
                            ->whereIn('status', [
                                \App\Enums\TimeEntryStatusEnum::APPROVED->value,
                                \App\Enums\TimeEntryStatusEnum::PENDING->value,
                            ])
                            ->sum('duration_minutes');
                        $trackedHours = $trackedMinutes ? number_format($trackedMinutes / 60, 1) : '0';

                        return "{$project->name} (Estimadas: {$estimatedHours}h | Apontadas: {$trackedHours}h)";
                    }),
            ])
            ->defaultGroup('project.name')
            ->recordActions([
                EditAction::make()->label('Editar'),
                DeleteAction::make()->label('Excluir'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()->label('Excluir Selecionados'),
                ]),
            ])
            ->columnManagerMaxHeight('300px')
            ->defaultPaginationPageOption(50)
            ->paginationPageOptions([10, 25, 50, 100])
            ->defaultSort('created_at', 'desc');
    }
}

