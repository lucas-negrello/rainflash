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
        $columns = [
            TextColumn::make('started_at')
                ->label('Início')
                ->dateTime('d/m/Y H:i')
                ->sortable(),

            TextColumn::make('ended_at')
                ->label('Fim')
                ->dateTime('d/m/Y H:i')
                ->sortable(),

            TextColumn::make('duration_minutes')
                ->label('Duração')
                ->formatStateUsing(fn ($state) => $state ? number_format($state / 60, 1) : '0')
                ->suffix(' h')
                ->sortable()
                ->badge()
                ->color('info'),

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

            IconColumn::make('locked')
                ->label('Travado')
                ->boolean()
                ->trueIcon('heroicon-o-lock-closed')
                ->falseIcon('heroicon-o-lock-open')
                ->trueColor('danger')
                ->falseColor('success')
                ->sortable()
                ->toggleable(),
        ];

        // Adiciona campos específicos se não for RelationManager
        if (!$includeRelationshipFields) {
            array_splice($columns, 0, 0, [
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
            ]);
        }

        return $columns;
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

