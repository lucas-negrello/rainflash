<?php

namespace App\Filament\Admin\Resources\Users\RelationManagers;

use App\Enums\UserSkillProficiencyLevelEnum;
use App\Filament\Shared\Schemas\SkillSchema;
use App\Filament\Shared\Tables\SkillsTable as SharedSkillsTable;
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
use Filament\Tables\Table;

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
            ->columns(SharedSkillsTable::getBase(includeRelationshipFields: true))
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
                            ->createOptionForm(SkillSchema::getBase(useRelationshipFields: true))
                            ->createOptionUsing(function (array $data): int {
                                $skill = Skill::create($data);
                                return $skill->id;
                            })
                            ->createOptionModalHeading('Criar Nova Habilidade'),

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
                    ]),
            ])
            ->recordActions([
                EditAction::make()
                    ->label('Editar')
                    ->schema([
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
                    ]),
                DetachAction::make()
                    ->label('Remover'),
            ])
            ->toolbarActions([
                DetachBulkAction::make()
                    ->label('Remover Selecionadas'),
            ]);
    }
}

