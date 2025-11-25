<?php

namespace App\Filament\Shared\Tables;

use App\Contracts\Filament\SharedFilamentTable;
use App\Enums\TimeEntryOriginEnum;
use App\Enums\TimeEntryStatusEnum;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;

class TimeEntriesTable implements SharedFilamentTable
{
    public static function getBase(array $extraFields = [], bool $includeRelationshipFields = false): array
    {
        $appointmentColumns = [];

        // Adiciona campos específicos se não for RelationManager
        if (!$includeRelationshipFields) {
            $appointmentColumns = [
                TextColumn::make('project.name')
                    ->label('Projeto')
                    ->searchable()
                    ->sortable()
                    ->icon('heroicon-o-folder'),

                TextColumn::make('task.title')
                    ->label('Tarefa')
                    ->searchable()
                    ->sortable()
                    ->limit(30)
                    ->placeholder('—')
                    ->toggleable(),

                TextColumn::make('companyUserCreator.user.name')
                    ->label('Criado por')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
            ];
        }

        $appointmentColumns = array_merge($appointmentColumns, [
            TextColumn::make('duration_minutes')
                ->label('Duração')
                ->formatStateUsing(fn ($state) => $state ? number_format($state / 60, 1) : '0')
                ->suffix(' h')
                ->sortable()
                ->badge()
                ->color('info')
                ->summarize([
                    \Filament\Tables\Columns\Summarizers\Sum::make()
                        ->label('Total')
                        ->formatStateUsing(fn ($state) => $state ? number_format($state / 60, 1) . ' h' : '0 h'),
                ]),

            TextColumn::make('origin')
                ->label('Origem')
                ->badge()
                ->formatStateUsing(fn ($state) => $state ? $state->label() : '—')
                ->color(fn ($state) => $state ? $state->color() : 'gray')
                ->sortable()
                ->toggleable(),

            TextColumn::make('status')
                ->label('Status')
                ->badge()
                ->formatStateUsing(fn ($state) => $state ? $state->label() : '—')
                ->color(fn ($state) => match($state) {
                    TimeEntryStatusEnum::APPROVED => 'success',
                    TimeEntryStatusEnum::REPROVED => 'danger',
                    TimeEntryStatusEnum::PENDING => 'warning',
                    default => 'gray',
                })
                ->sortable(),

            TextColumn::make('locked')
                ->label('Travado')
                ->badge()
                ->formatStateUsing(fn ($state) => $state ? 'Sim' : 'Não')
                ->color(fn ($state) => $state ? 'danger' : 'success')
                ->sortable()
                ->toggleable(),
        ]);

        $dataColumns = [
            TextColumn::make('date')
                ->label('Data')
                ->date('d/m/Y')
                ->sortable(),

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
        ];

        $statisticsColumns = [
            TextColumn::make('approved_at')
                ->label('Aprovado em')
                ->dateTime('d/m/Y H:i')
                ->placeholder('—')
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),

            TextColumn::make('companyUserReviewer.user.name')
                ->label('Revisado por')
                ->searchable()
                ->placeholder('—')
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),
        ];

        return [
            \Filament\Tables\Columns\ColumnGroup::make('Apontamentos', $appointmentColumns),
            \Filament\Tables\Columns\ColumnGroup::make('Datas', $dataColumns),
            \Filament\Tables\Columns\ColumnGroup::make('Estatísticas', $statisticsColumns),
        ];
    }

    public static function getFilters(array $extraFilters = []): array
    {
        return [
            SelectFilter::make('status')
                ->label('Status')
                ->options(TimeEntryStatusEnum::dropdownOptions())
                ->native(false),

            SelectFilter::make('origin')
                ->label('Origem')
                ->options(TimeEntryOriginEnum::dropdownOptions())
                ->native(false),

            TernaryFilter::make('locked')
                ->label('Travado')
                ->trueLabel('Sim')
                ->falseLabel('Não')
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

