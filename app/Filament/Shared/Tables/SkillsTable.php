<?php

namespace App\Filament\Shared\Tables;

use App\Contracts\Filament\SharedFilamentTable;
use App\Enums\UserSkillProficiencyLevelEnum;
use Filament\Tables\Columns\ColumnGroup;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;

class SkillsTable implements SharedFilamentTable
{

    public static function getBase(array $extraFields = [], bool $includeRelationshipFields = false): array
    {
        $skillColumns = [
            TextColumn::make('name')
                ->label('Nome')
                ->searchable()
                ->sortable()
                ->icon('heroicon-o-academic-cap')
                ->weight('medium'),

            TextColumn::make('category')
                ->label('Categoria')
                ->searchable()
                ->sortable()
                ->badge()
                ->color('info'),
        ];

        if ($includeRelationshipFields) {
            $skillColumns[] = TextColumn::make('proficiency_level')
                ->label('Nível de Proficiência')
                ->getStateUsing(fn ($record) => $record->pivot?->proficiency_level)
                ->badge()
                ->formatStateUsing(fn ($state) => $state ? UserSkillProficiencyLevelEnum::from($state)->label() : '—')
                ->color(fn ($state) => $state ? UserSkillProficiencyLevelEnum::from($state)->color() : 'gray')
                ->sortable();

            $skillColumns[] = TextColumn::make('years_of_experience')
                ->label('Anos de Experiência')
                ->getStateUsing(fn ($record) => $record->pivot?->years_of_experience)
                ->numeric(decimalPlaces: 1)
                ->suffix(' anos')
                ->placeholder('—')
                ->sortable();

            $skillColumns[] = TextColumn::make('last_used_at')
                ->label('Última Utilização')
                ->getStateUsing(fn ($record) => $record->pivot?->last_used_at)
                ->date('d/m/Y')
                ->placeholder('—')
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true);

            $skillColumns[] = TextColumn::make('created_at_pivot')
                ->label('Adicionado em')
                ->getStateUsing(fn ($record) => $record->pivot?->created_at)
                ->dateTime('d/m/Y H:i')
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true);
        } else {
            $skillColumns[] = TextColumn::make('key')
                ->label('Chave')
                ->searchable()
                ->copyable()
                ->badge()
                ->color('gray')
                ->toggleable(isToggledHiddenByDefault: true);
        }

        $columns = [
            ColumnGroup::make('Habilidade', $skillColumns),
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
                TextColumn::make('users_count')
                    ->label('Usuários')
                    ->counts('users')
                    ->badge()
                    ->color('success')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ]);
        }

        return $columns;
    }

    public static function getFilters(array $extraFilters = []): array
    {
        return [
            SelectFilter::make('category')
                ->label('Categoria')
                ->options(function () {
                    return \App\Models\Skill::query()
                        ->whereNotNull('category')
                        ->distinct()
                        ->pluck('category', 'category')
                        ->toArray();
                })
                ->multiple()
                ->searchable(),
        ];
    }
}
