<?php

namespace App\Filament\Shared\Tables;

use App\Contracts\Filament\SharedFilamentTable;
use App\Enums\ProjectBillingModelEnum;
use App\Enums\ProjectStatusEnum;
use App\Enums\ProjectTypeEnum;
use Filament\Tables\Columns\ColumnGroup;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;

class ProjectsTable implements SharedFilamentTable
{
    public static function getBase(array $extraFields = [], bool $includeRelationshipFields = false): array
    {
        $projectColumns = [
            TextColumn::make('code')
                ->label('Código')
                ->searchable()
                ->sortable()
                ->copyable()
                ->badge()
                ->color('gray')
                ->weight('medium')
                ->toggleable(isToggledHiddenByDefault: true),

            TextColumn::make('name')
                ->label('Nome')
                ->searchable()
                ->sortable()
                ->icon('heroicon-o-folder')
                ->weight('semibold'),

            TextColumn::make('type')
                ->label('Tipo')
                ->badge()
                ->formatStateUsing(fn ($state) => $state ? $state->label() : '—')
                ->color(fn ($state) => $state ? $state->color() : 'gray')
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),

            TextColumn::make('billing_model')
                ->label('Cobrança')
                ->badge()
                ->formatStateUsing(fn ($state) => $state ? $state->label() : '—')
                ->color(fn ($state) => $state ? $state->color() : 'gray')
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),

            TextColumn::make('status')
                ->label('Status')
                ->badge()
                ->formatStateUsing(fn ($state) => $state ? $state->label() : '—')
                ->color(fn ($state) => $state ? $state->color() : 'gray')
                ->sortable(),
        ];

        // Campos específicos para contexto normal (não RelationManager)
        if (!$includeRelationshipFields) {
            $projectColumns[] = TextColumn::make('company.name')
                ->label('Empresa')
                ->searchable()
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true);
        }

        $columns = [
            ColumnGroup::make('Projeto', $projectColumns),
        ];

        // Adiciona grupos adicionais apenas quando NÃO for RelationManager
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
                TextColumn::make('tasks_count')
                    ->label('Tarefas')
                    ->counts('tasks')
                    ->badge()
                    ->color('info')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('assignments_count')
                    ->label('Atribuições')
                    ->counts('assignments')
                    ->badge()
                    ->color('success')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

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
                    ->label('Horas Estimadas')
                    ->getStateUsing(function ($record) {
                        // Soma de estimated_minutes de todas as tasks do projeto
                        $totalMinutes = $record->tasks()->sum('estimated_minutes');
                        return $totalMinutes ? number_format($totalMinutes / 60, 1) : '0';
                    })
                    ->suffix(' h')
                    ->badge()
                    ->color('info')
                    ->sortable(query: function ($query, $direction) {
                        $query->withSum('tasks as total_estimated_minutes', 'estimated_minutes')
                              ->orderBy('total_estimated_minutes', $direction);
                    }),

                TextColumn::make('tracked_hours')
                    ->label('Horas Apontadas')
                    ->getStateUsing(function ($record) {
                        // Soma de duration_minutes dos time_entries aprovados ou pendentes (exclui reprovados)
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
                        $estimated = $record->tasks()->sum('estimated_minutes');
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
                ->options(ProjectTypeEnum::dropdownOptions())
                ->native(false),

            SelectFilter::make('billing_model')
                ->label('Modelo de Cobrança')
                ->options(ProjectBillingModelEnum::dropdownOptions())
                ->native(false),

            SelectFilter::make('status')
                ->label('Status')
                ->options(ProjectStatusEnum::dropdownOptions())
                ->native(false),

            SelectFilter::make('company_id')
                ->label('Empresa')
                ->relationship('company', 'name')
                ->searchable()
                ->preload()
                ->native(false),
        ];
    }
}

