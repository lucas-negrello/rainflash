<?php

namespace App\Filament\Shared\Tables;

use App\Contracts\Filament\SharedFilamentTable;
use App\Enums\FeatureTierOptionsEnum;
use App\Enums\FeatureTypeEnum;
use Filament\Tables\Columns\ColumnGroup;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;

class FeaturesTable implements SharedFilamentTable
{

    public static function getBase(array $extraFields = [], bool $includeRelationshipFields = false): array
    {
        $featureColumns = [
            TextColumn::make('name')
                ->label('Nome')
                ->searchable()
                ->sortable(),

            TextColumn::make('key')
                ->label('Chave')
                ->searchable()
                ->sortable()
                ->copyable()
                ->toggleable(),

            TextColumn::make('type')
                ->label('Tipo')
                ->badge()
                ->sortable()
                ->formatStateUsing(fn ($state) => $state ? $state->label() : '—')
                ->color(fn ($state) => $state ? $state->color() : 'gray'),
        ];

        if ($includeRelationshipFields) {
            $featureColumns[] = TextColumn::make('value')
                ->label('Valor')
                ->getStateUsing(fn ($record) => $record->pivot?->value)
                ->formatStateUsing(function ($state, $record) {
                    try {
                        $tier = FeatureTierOptionsEnum::fromValue((int) $state);
                    } catch (\Exception $e) {
                        $tier = FeatureTierOptionsEnum::BASIC;
                    }

                    return match($record->type) {
                        FeatureTypeEnum::BOOLEAN => filter_var($state, FILTER_VALIDATE_BOOLEAN) ? '✓ Sim' : '✗ Não',
                        FeatureTypeEnum::LIMIT => "Limite: {$state}",
                        FeatureTypeEnum::TIER => "Nível: {$tier->label()}",
                        default => $state ?? '—',
                    };
                })
                ->badge()
                ->color(function ($state, $record) {
                    try {
                        $tier = FeatureTierOptionsEnum::fromValue((int) $state);
                    } catch (\Exception $e) {
                        $tier = FeatureTierOptionsEnum::BASIC;
                    }

                    return match($record->type) {
                        FeatureTypeEnum::BOOLEAN => filter_var($state, FILTER_VALIDATE_BOOLEAN) ? 'success' : 'danger',
                        FeatureTypeEnum::LIMIT => FeatureTypeEnum::LIMIT->color(),
                        FeatureTypeEnum::TIER => $tier->color(),
                        default => 'gray',
                    };
                });
        }

        $columns = [
            ColumnGroup::make('Feature', $featureColumns),
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
                TextColumn::make('plan_features_count')
                    ->label('Planos')
                    ->counts('planFeatures')
                    ->badge()
                    ->color('success')
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('company_feature_overrides_count')
                    ->label('Overrides')
                    ->counts('companyFeatureOverrides')
                    ->badge()
                    ->color('warning')
                    ->sortable()
                    ->toggleable(),
            ]);
        } else {
            $columns[] = TextColumn::make('created_at')
                ->label('Adicionado em')
                ->getStateUsing(fn ($record) => $record->pivot?->created_at)
                ->dateTime('d/m/Y H:i')
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true);
        }

        return $columns;
    }

    public static function getFilters(array $extraFilters = []): array
    {
        return [
            SelectFilter::make('type')
                ->label('Tipo')
                ->options(FeatureTypeEnum::dropdownOptions())
                ->native(false),
        ];
    }
}
