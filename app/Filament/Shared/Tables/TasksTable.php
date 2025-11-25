<?php

namespace App\Filament\Shared\Tables;

use App\Contracts\Filament\SharedFilamentTable;
use App\Enums\TaskStatusEnum;
use App\Enums\TaskTypeEnum;
use Filament\Tables\Columns\ColumnGroup;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;

class TasksTable implements SharedFilamentTable
{
    public static function getBase(array $extraFields = [], bool $includeRelationshipFields = false): array
    {
        $taskColumns = [
            TextColumn::make('title')
                ->label('Título')
                ->searchable()
                ->sortable()
                ->icon(fn ($record) => $record->type?->icon() ?? 'heroicon-o-clipboard-document-list')
                ->iconColor(fn ($record) => $record->type?->color() ?? 'gray')
                ->weight('medium')
                ->limit(50)
                ->tooltip(fn ($record) => $record->type?->label()),

            TextColumn::make('type')
                ->label('Tipo')
                ->badge()
                ->formatStateUsing(fn ($state) => $state ? $state->label() : '—')
                ->color(fn ($state) => $state ? $state->color() : 'gray')
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),

            SelectColumn::make('status')
                ->label('Status')
                ->options(TaskStatusEnum::dropdownOptions())
                ->selectablePlaceholder(false)
                ->sortable(),
        ];

        if (!$includeRelationshipFields) {
            $taskColumns[] = TextColumn::make('project.name')
                ->label('Projeto')
                ->searchable()
                ->sortable()
                ->icon('heroicon-o-folder')
                ->toggleable(isToggledHiddenByDefault: true);

            $taskColumns[] = TextColumn::make('project.company.name')
                ->label('Empresa')
                ->searchable()
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true);
        }

        $taskColumns[] = TextColumn::make('companyUserAssignee.user.name')
            ->label('Responsável')
            ->placeholder('Não atribuído')
            ->sortable()
            ->toggleable();

        $columns = [
            ColumnGroup::make('Tarefa', $taskColumns),
        ];

        if (!$includeRelationshipFields) {
            $columns[] = ColumnGroup::make('Datas', [
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
            ]);

            $columns[] = ColumnGroup::make('Estatísticas', [
                TextColumn::make('time_entries_count')
                    ->label('Apontamentos')
                    ->counts('timeEntries')
                    ->badge()
                    ->color('warning')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ]);

            $columns[] = ColumnGroup::make('Apontamentos', [
                TextColumn::make('estimated_hours')
                    ->label('Hr. Estim.')
                    ->getStateUsing(function ($record) {
                        return $record->estimated_minutes ? number_format($record->estimated_minutes / 60, 1) : '0';
                    })
                    ->suffix(' h')
                    ->badge()
                    ->color('info')
                    ->sortable(query: function ($query, $direction) {
                        $query->orderBy('estimated_minutes', $direction);
                    }),

                TextColumn::make('tracked_hours')
                    ->label('Hr. Apont.')
                    ->getStateUsing(function ($record) {
                        $totalMinutes = $record->timeEntries()
                            ->whereIn('status', [
                                \App\Enums\TimeEntryStatusEnum::APPROVED->value,
                                \App\Enums\TimeEntryStatusEnum::PENDING->value,
                            ])
                            ->sum('duration_minutes');
                        return $totalMinutes ? number_format($totalMinutes / 60, 1) : '0';
                    })
                    ->suffix(' h')
                    ->badge()
                    ->color(function ($record) {
                        $estimated = $record->estimated_minutes;
                        $tracked = $record->timeEntries()
                            ->whereIn('status', [
                                \App\Enums\TimeEntryStatusEnum::APPROVED->value,
                                \App\Enums\TimeEntryStatusEnum::PENDING->value,
                            ])
                            ->sum('duration_minutes');

                        if (!$estimated) return 'gray';

                        $percentage = ($tracked / $estimated) * 100;

                        if ($percentage >= 100) return 'danger';
                        if ($percentage >= 80) return 'warning';
                        return 'success';
                    })
                    ->sortable(query: function ($query, $direction) {
                        $query->withSum([
                            'timeEntries as total_tracked_minutes' => function ($query) {
                                $query->whereIn('status', [
                                    \App\Enums\TimeEntryStatusEnum::APPROVED->value,
                                    \App\Enums\TimeEntryStatusEnum::PENDING->value,
                                ]);
                            }
                        ], 'duration_minutes')
                              ->orderBy('total_tracked_minutes', $direction);
                    }),
            ]);
        }

        return $columns;
    }

    public static function getFilters(array $extraFilters = []): array
    {
        return [
            SelectFilter::make('type')
                ->label('Tipo')
                ->options(TaskTypeEnum::dropdownOptions())
                ->native(false),

            SelectFilter::make('status')
                ->label('Status')
                ->options(TaskStatusEnum::dropdownOptions())
                ->native(false),

            SelectFilter::make('project_id')
                ->label('Projeto')
                ->relationship('project', 'name')
                ->searchable()
                ->preload()
                ->native(false),
        ];
    }
}

