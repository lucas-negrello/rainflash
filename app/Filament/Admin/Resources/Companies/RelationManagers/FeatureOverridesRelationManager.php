<?php

namespace App\Filament\Admin\Resources\Companies\RelationManagers;

use App\Enums\FeatureTierOptionsEnum;
use App\Enums\FeatureTypeEnum;
use App\Models\Feature;
use App\Models\PlanFeature;
use Filament\Actions\ActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class FeatureOverridesRelationManager extends RelationManager
{
    protected static string $relationship = 'companyFeatureOverrides';

    protected static ?string $title = 'Override de Features';

    protected static ?string $modelLabel = 'override de feature';

    protected static ?string $pluralModelLabel = 'overrides de features';

    protected static ?string $recordTitleAttribute = 'feature.name';

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('feature.name')
                    ->label('Feature')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('feature.key')
                    ->label('Chave')
                    ->searchable()
                    ->sortable()
                    ->copyable(),


                TextColumn::make('feature.type')
                    ->label('Tipo')
                    ->badge()
                    ->formatStateUsing(fn ($state) => $state ? $state->label() : '—')
                    ->color(fn ($state) => $state ? $state->color() : 'gray')
                    ->sortable(),

                TextColumn::make('value')
                    ->label('Valor Override')
                    ->formatStateUsing(function ($state, $record) {
                        try {
                            $tier = FeatureTierOptionsEnum::fromValue((int) $state);
                        } catch (\Exception $e) {
                            $tier = FeatureTierOptionsEnum::BASIC;
                        }

                        return match($record->feature->type) {
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

                        return match($record->feature->type) {
                            FeatureTypeEnum::BOOLEAN => filter_var($state, FILTER_VALIDATE_BOOLEAN) ? 'success' : 'danger',
                            FeatureTypeEnum::LIMIT => FeatureTypeEnum::LIMIT->color(),
                            FeatureTypeEnum::TIER => $tier->color(),
                            default => 'gray',
                        };
                    }),

                TextColumn::make('created_at')
                    ->label('Adicionado em')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->headerActions([
                CreateAction::make()
                    ->label('Adicionar Override')
                    ->mutateDataUsing(function(array $data): array {
                        $feature = Feature::find($data['feature_id']);
                        $data = $this->getFeatureTypeData($feature, $data);
                        unset($data['value_boolean'], $data['value_limit'], $data['value_tier']);
                        return $data;
                    })
                    ->schema([
                        Select::make('feature_id')
                            ->label('Feature')
                            ->options(function () {
                                $existing =
                                    $this->getOwnerRecord()
                                        ->companyFeatureOverrides()
                                        ->pluck('feature_id')
                                        ->toArray();

                                return Feature::whereNotIn('id', $existing)
                                    ->orderBy('name')
                                    ->get()
                                    ->mapWithKeys(fn($f)=>[$f->id=>"{$f->name} ({$f->key})"]);
                            })
                            ->searchable()
                            ->preload()
                            ->required()
                            ->reactive()
                            ->afterStateUpdated(function ($state, callable $set) {
                                $company = $this->getOwnerRecord();
                                $planId = $company->current_plan_id;
                                if ($state && $planId) {
                                    $default = PlanFeature::query()
                                        ->where('plan_id', $planId)
                                        ->where('feature_id', $state)
                                        ->value('value');

                                    if ($default !== null) {
                                        $feature = Feature::find($state);
                                        if ($feature?->type === FeatureTypeEnum::BOOLEAN) {
                                            $set('value_boolean', filter_var($default, FILTER_VALIDATE_BOOLEAN));
                                        } elseif ($feature?->type === FeatureTypeEnum::LIMIT) {
                                            $set('value_limit', is_numeric($default) ? (int) $default : null);
                                        } elseif ($feature?->type === FeatureTypeEnum::TIER) {
                                            $set('value_tier', in_array($default, FeatureTierOptionsEnum::labels())
                                                ? $default
                                                : FeatureTierOptionsEnum::BASIC);
                                        }
                                    }
                                }
                            }),
                        Toggle::make('value_boolean')
                            ->label('Habilitado')
                            ->onIcon('heroicon-o-check')
                            ->offIcon('heroicon-o-x-mark')
                            ->visible(fn (callable $get) => $this->isType($get('feature_id'), FeatureTypeEnum::BOOLEAN))
                            ->default(false)
                            ->inline(false),
                        TextInput::make('value_limit')
                            ->label('Limite')
                            ->numeric()
                            ->minValue(0)
                            ->placeholder('Ex: 100')
                            ->visible(fn (callable $get) => $this->isType($get('feature_id'), FeatureTypeEnum::LIMIT))
                            ->required(fn (callable $get) => $this->isType($get('feature_id'), FeatureTypeEnum::LIMIT))
                            ->reactive(),
                        Select::make('value_tier')
                            ->label('Nível / Tier')
                            ->options(FeatureTierOptionsEnum::dropdownOptions())
                            ->native(false)
                            ->default(FeatureTierOptionsEnum::BASIC)
                            ->visible(fn (callable $get) => $this->isType($get('feature_id'), FeatureTypeEnum::TIER))
                            ->required(fn (callable $get) => $this->isType($get('feature_id'), FeatureTypeEnum::TIER)),
                        KeyValue::make('meta')
                            ->label('Metadados (opcional)')
                            ->keyLabel('Chave')
                            ->valueLabel('Valor')
                            ->default([])
                            ->dehydrateStateUsing(fn ($state) => empty($state) ? null : $state)
                            ->helperText('Informações adicionais para auditoria ou lógica customizada.'),
                    ])
                    ->successNotificationTitle('Override adicionado com sucesso'),
            ])
            ->recordActions([
                ActionGroup::make([
                    EditAction::make()
                        ->label('Editar')
                        ->mutateDataUsing(function(array $data, $record): array {
                            $feature = $record->feature;
                            $data = $this->getFeatureTypeData($feature, $data);
                            unset($data['value_boolean'], $data['value_limit'], $data['value_tier']);
                            return $data;
                        })
                        ->schema(function($record){
                            $feature = $record->feature;
                            $type = $feature->type;
                            $current = $record->value;
                            return [
                                TextInput::make('feature_name')
                                    ->label('Feature')
                                    ->disabled()
                                    ->default($feature->name)
                                    ->dehydrated(false),
                                Toggle::make('value_boolean')
                                    ->label('Habilitado')
                                    ->visible(fn()=> $type === FeatureTypeEnum::BOOLEAN)
                                    ->default(filter_var($current, FILTER_VALIDATE_BOOLEAN)),
                                TextInput::make('value_limit')
                                    ->label('Limite')
                                    ->numeric()
                                    ->minValue(0)
                                    ->visible(fn()=> $type === FeatureTypeEnum::LIMIT)
                                    ->default(is_numeric($current)? $current : 0),
                                Select::make('value_tier')
                                    ->label('Nível / Tier')
                                    ->options(FeatureTierOptionsEnum::dropdownOptions())
                                    ->native(false)
                                    ->visible(fn()=> $type === FeatureTypeEnum::TIER)
                                    ->default(in_array($current, FeatureTierOptionsEnum::labels())
                                        ? $current
                                        : FeatureTierOptionsEnum::BASIC
                                    ),
                                KeyValue::make('meta')
                                    ->label('Metadados (opcional)')
                                    ->keyLabel('Chave')
                                    ->valueLabel('Valor')
                                    ->default($record->meta ?? [])
                                    ->dehydrateStateUsing(fn ($state) => empty($state) ? null : $state)
                                    ->helperText('Informações adicionais.'),
                            ];
                        }),
                    DeleteAction::make()
                        ->label('Remover')
                        ->successNotificationTitle('Override removido'),
                ])
            ])
            ->toolbarActions([
                DeleteBulkAction::make()
                    ->label('Remover Selecionados'),
            ])
            ->emptyStateHeading('Nenhum override de feature')
            ->emptyStateDescription('Esta empresa não possui overrides customizados de features. Adicione overrides para personalizar as funcionalidades.');
    }

    protected function getPivotFormSchema(): array
    {
        return [
            KeyValue::make('value')
                ->label('Valor Override da Feature')
                ->keyLabel('Chave')
                ->valueLabel('Valor')
                ->helperText('Define os valores customizados da feature para esta empresa. Ex: enabled = true, limit = 100, tier = premium')
                ->required()
                ->default([])
                ->addActionLabel('Adicionar propriedade')
                ->reorderable()
                ->dehydrateStateUsing(fn ($state) => empty($state) ? [] : $state),

            KeyValue::make('meta')
                ->label('Metadados (opcional)')
                ->keyLabel('Chave')
                ->valueLabel('Valor')
                ->default([])
                ->addActionLabel('Adicionar metadado')
                ->reorderable()
                ->dehydrateStateUsing(fn ($state) => empty($state) ? null : $state),
        ];
    }

    private function isType(?int $featureId, FeatureTypeEnum $expected): bool
    {
        if (!$featureId) return false;
        $feature = Feature::find($featureId);
        return $feature?->type === $expected;
    }

    private function getFeatureTypeData(mixed $feature, array $data): array {
        $__data = $data;
        if ($feature) {
            $type = $feature->type;
            if ($type === FeatureTypeEnum::BOOLEAN) {
                $__data['value'] = ($__data['value_boolean'] ?? false) ? 'true' : 'false';
            } elseif ($type === FeatureTypeEnum::LIMIT) {
                $__data['value'] = (string) ($__data['value_limit'] ?? 0);
            } elseif ($type === FeatureTypeEnum::TIER) {
                $__data['value'] = $__data['value_tier'] ?? FeatureTierOptionsEnum::BASIC;
            }
        }
        return $__data;
    }
}

