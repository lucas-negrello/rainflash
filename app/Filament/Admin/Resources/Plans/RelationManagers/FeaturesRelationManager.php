<?php

namespace App\Filament\Admin\Resources\Plans\RelationManagers;

use App\Enums\FeatureTierOptionsEnum;
use App\Enums\FeatureTypeEnum;
use App\Filament\Shared\Schemas\FeatureSchema;
use App\Filament\Shared\Tables\FeaturesTable as SharedFeaturesTable;
use App\Models\Feature;
use Filament\Actions\AttachAction;
use Filament\Actions\DetachAction;
use Filament\Actions\DetachBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;

class FeaturesRelationManager extends RelationManager
{
    protected static string $relationship = 'features';

    protected static bool $isRelationAsync = false;

    protected static ?string $title = 'Features';

    protected static ?string $modelLabel = 'feature';

    protected static ?string $pluralModelLabel = 'features';

    protected static ?string $recordTitleAttribute = 'name';

    public function table(Table $table): Table
    {
        return $table
            ->columns(SharedFeaturesTable::getBase(includeRelationshipFields: true))
            ->headerActions([
                AttachAction::make()
                    ->label('Adicionar Feature')
                    ->preloadRecordSelect()
                    ->recordSelectOptionsQuery(fn ($query) => $query
                        ->orderBy('name'))
                    ->recordSelectSearchColumns(['name', 'key'])
                    ->recordTitleAttribute('name')
                    ->schema(function (AttachAction $action): array {
                        return [
                            $action->getRecordSelect()
                                ->label('Feature')
                                ->searchable()
                                ->reactive()
                                ->live()
                                ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->name} ({$record->key})"),

                            Toggle::make('value_boolean')
                                ->label('Habilitado')
                                ->visible(fn (callable $get) => $this->isType($get('recordId'), FeatureTypeEnum::BOOLEAN))
                                ->default(false)
                                ->live(),

                            TextInput::make('value_limit')
                                ->label('Limite')
                                ->numeric()
                                ->minValue(0)
                                ->placeholder('Ex: 50')
                                ->visible(fn (callable $get) => $this->isType($get('recordId'), FeatureTypeEnum::LIMIT))
                                ->required(fn (callable $get) => $this->isType($get('recordId'), FeatureTypeEnum::LIMIT))
                                ->live(),

                            Select::make('value_tier')
                                ->label('Nível / Tier')
                                ->options(FeatureTierOptionsEnum::dropdownOptions())
                                ->native(false)
                                ->default(FeatureTierOptionsEnum::BASIC)
                                ->visible(fn (callable $get) => $this->isType($get('recordId'), FeatureTypeEnum::TIER))
                                ->required(fn (callable $get) => $this->isType($get('recordId'), FeatureTypeEnum::TIER))
                                ->live(),

                            KeyValue::make('meta')
                                ->label('Metadados (opcional)')
                                ->keyLabel('Chave')
                                ->valueLabel('Valor')
                                ->default([])
                                ->dehydrateStateUsing(fn ($state) => empty($state) ? null : $state),
                        ];
                    })
                    ->using(function (AttachAction $action, $record, array $data): void {
                        $plan = $this->getOwnerRecord();
                        $feature = Feature::find($data['recordId'] ?? null);

                        $value = 'true';
                        if ($feature) {
                            $type = $feature->type;
                            if ($type === FeatureTypeEnum::BOOLEAN) {
                                $value = ($data['value_boolean'] ?? false) ? 'true' : 'false';
                            } elseif ($type === FeatureTypeEnum::LIMIT) {
                                $value = (string) ($data['value_limit'] ?? 0);
                            } elseif ($type === FeatureTypeEnum::TIER) {
                                $value = $data['value_tier'] ?? FeatureTierOptionsEnum::BASIC;
                            }
                        }

                        $plan->features()->attach($data['recordId'], [
                            'value' => $value,
                            'meta' => $data['meta'] ?? null,
                        ]);
                    }),
            ])
            ->recordActions([
                EditAction::make()
                    ->label('Editar')
                    ->fillForm(function ($record): array {
                        $feature = $record;
                        $pivot = $record->pivot;
                        $current = $pivot->value;
                        $type = $feature->type;

                        $form = [
                            'meta' => $pivot->meta ?? [],
                        ];

                        if ($type === FeatureTypeEnum::BOOLEAN) {
                            $form['value_boolean'] = filter_var($current, FILTER_VALIDATE_BOOLEAN);
                        } elseif ($type === FeatureTypeEnum::LIMIT) {
                            $form['value_limit'] = is_numeric($current) ? (int)$current : 0;
                        } elseif ($type === FeatureTypeEnum::TIER) {
                            $form['value_tier'] = in_array($current, FeatureTierOptionsEnum::labels())
                                ? $current
                                : FeatureTierOptionsEnum::BASIC;
                        }

                        return $form;
                    })
                    ->form(function($record) {
                        $feature = $record;
                        $type = $feature->type;

                        return [
                            TextInput::make('feature_name')
                                ->label('Feature')
                                ->disabled()
                                ->default($feature->name)
                                ->dehydrated(false),

                            Toggle::make('value_boolean')
                                ->label('Habilitado')
                                ->visible(fn()=> $type === FeatureTypeEnum::BOOLEAN)
                                ->live(),

                            TextInput::make('value_limit')
                                ->label('Limite')
                                ->numeric()
                                ->minValue(0)
                                ->visible(fn()=> $type === FeatureTypeEnum::LIMIT)
                                ->live(),

                            Select::make('value_tier')
                                ->label('Nível / Tier')
                                ->options(FeatureTierOptionsEnum::dropdownOptions())
                                ->native(false)
                                ->visible(fn()=> $type === FeatureTypeEnum::TIER)
                                ->live(),

                            KeyValue::make('meta')
                                ->label('Metadados (opcional)')
                                ->keyLabel('Chave')
                                ->valueLabel('Valor')
                                ->dehydrateStateUsing(fn ($state) => empty($state) ? null : $state),
                        ];
                    })
                    ->using(function ($record, array $data): void {
                        $feature = $record;
                        $type = $feature->type;

                        if ($type === FeatureTypeEnum::BOOLEAN) {
                            $value = ($data['value_boolean'] ?? false) ? 'true' : 'false';
                        } elseif ($type === FeatureTypeEnum::LIMIT) {
                            $value = (string) ($data['value_limit'] ?? 0);
                        } elseif ($type === FeatureTypeEnum::TIER) {
                            $value = $data['value_tier'] ?? FeatureTierOptionsEnum::BASIC;
                        } else {
                            $value = 'true';
                        }

                        $record->pivot->value = $value;
                        $record->pivot->meta = $data['meta'] ?? null;
                        $record->pivot->save();
                    }),
                DetachAction::make()->label('Remover'),
            ])
            ->toolbarActions([
                DetachBulkAction::make()
                    ->label('Remover Selecionadas'),
            ]);
    }

    private function isType(?int $featureId, FeatureTypeEnum $expected): bool
    {
        if (!$featureId) return false;
        $feature = Feature::find($featureId);
        return $feature?->type === $expected;
    }
}
