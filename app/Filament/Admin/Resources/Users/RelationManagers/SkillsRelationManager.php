<?php

namespace App\Filament\Admin\Resources\Users\RelationManagers;

use App\Enums\UserSkillProficiencyLevelEnum;
use App\Models\Skill;
use Filament\Actions\AttachAction;
use Filament\Actions\DetachAction;
use Filament\Actions\DetachBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class SkillsRelationManager extends RelationManager
{
    protected static string $relationship = 'skills';

    protected static ?string $title = 'Habilidades';

    protected static ?string $modelLabel = 'habilidade';

    protected static ?string $pluralModelLabel = 'habilidades';

    protected static ?string $recordTitleAttribute = 'name';

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nome')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('category')
                    ->label('Categoria')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color('gray'),

                TextColumn::make('pivot.proficiency_level')
                    ->label('Nível de Proficiência')
                    ->badge()
                    ->formatStateUsing(fn ($state) => $state ? UserSkillProficiencyLevelEnum::from($state)->label() : '—')
                    ->color(fn ($state) => $state ? UserSkillProficiencyLevelEnum::from($state)->color() : 'gray')
                    ->sortable(),

                TextColumn::make('pivot.years_of_experience')
                    ->label('Anos de Experiência')
                    ->numeric(decimalPlaces: 1)
                    ->suffix(' anos')
                    ->placeholder('—')
                    ->sortable(),

                TextColumn::make('pivot.last_used_at')
                    ->label('Última Utilização')
                    ->date('d/m/Y')
                    ->placeholder('—')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('pivot.created_at')
                    ->label('Adicionado em')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->headerActions([
                AttachAction::make()
                    ->label('Adicionar Habilidade')
                    ->preloadRecordSelect()
                    ->schema(fn (AttachAction $action): array => [
                        $action
                            ->getRecordSelect()
                            ->multiple()
                            ->label('Habilidade')
                            ->searchable()
                            ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->name}" . ($record->category ? " ({$record->category})" : ''))
                            ->createOptionForm([
                                TextInput::make('name')
                                    ->label('Nome')
                                    ->required()
                                    ->maxLength(255)
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(function (Get $get, Set $set, ?string $state) {
                                        if (!$get('key') && $state) {
                                            $set('key', Str::slug($state));
                                        }
                                    }),

                                TextInput::make('key')
                                    ->label('Chave (Key)')
                                    ->required()
                                    ->maxLength(255)
                                    ->unique(table: Skill::class, column: 'key')
                                    ->alphaDash()
                                    ->helperText('Identificador único da habilidade (gerado automaticamente)'),

                                TextInput::make('category')
                                    ->label('Categoria')
                                    ->maxLength(255)
                                    ->placeholder('Ex: Backend, Frontend, DevOps, etc')
                                    ->datalist([
                                        'Backend',
                                        'Frontend',
                                        'DevOps',
                                        'Data',
                                        'QA',
                                        'Mobile',
                                        'Design',
                                        'Management',
                                    ]),

                                KeyValue::make('meta')
                                    ->label('Metadados (opcional)')
                                    ->keyLabel('Chave')
                                    ->valueLabel('Valor')
                                    ->default([])
                                    ->dehydrateStateUsing(fn ($state) => empty($state) ? null : $state),
                            ])
                            ->createOptionUsing(function (array $data): int {
                                $skill = Skill::create($data);
                                return $skill->id;
                            })
                            ->createOptionModalHeading('Criar Nova Habilidade'),
                        ...$this->getPivotFormSchema()
                    ]),
            ])
            ->recordActions([
                EditAction::make()
                    ->label('Editar')
                    ->schema($this->getPivotFormSchema()),
                DetachAction::make()
                    ->label('Remover'),
            ])
            ->toolbarActions([
                DetachBulkAction::make()
                    ->label('Remover Selecionadas'),
            ]);
    }

    protected function getPivotFormSchema(): array
    {
        return [
            Select::make('proficiency_level')
                ->label('Nível de Proficiência')
                ->options(UserSkillProficiencyLevelEnum::labels())
                ->native(false)
                ->required(),

            TextInput::make('years_of_experience')
                ->label('Anos de Experiência')
                ->numeric()
                ->minValue(0)
                ->maxValue(99)
                ->step(0.5)
                ->suffix('anos'),

            DatePicker::make('last_used_at')
                ->label('Última Utilização')
                ->displayFormat('d/m/Y')
                ->maxDate(now()),

            KeyValue::make('meta')
                ->label('Metadados (opcional)')
                ->keyLabel('Chave')
                ->valueLabel('Valor')
                ->default([])
                ->dehydrateStateUsing(fn ($state) => empty($state) ? null : $state),
        ];
    }
}

