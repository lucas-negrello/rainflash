<?php

namespace App\Filament\Admin\Resources\Projects\RelationManagers;

use App\Filament\Shared\Tables\AssignmentsTable as SharedAssignmentsTable;
use App\Models\CompanyUser;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;

class AssignmentsRelationManager extends RelationManager
{
    protected static string $relationship = 'assignments';

    protected static ?string $title = 'Atribuições de Usuários';

    protected static ?string $modelLabel = 'atribuição';

    protected static ?string $pluralModelLabel = 'atribuições';

    public function table(Table $table): Table
    {
        return $table
            ->columns(SharedAssignmentsTable::getBase(includeRelationshipFields: true))
            ->headerActions([
                CreateAction::make()
                    ->label('Atribuir Usuário')
                    ->schema([
                        Select::make('company_user_id')
                            ->label('Usuário')
                            ->options(CompanyUser::with('user')->get()->pluck('user.name', 'id'))
                            ->searchable()
                            ->required()
                            ->native(false),

                        DatePicker::make('effective_from')
                            ->label('Data de Início')
                            ->required()
                            ->displayFormat('d/m/Y')
                            ->default(now()),

                        DatePicker::make('effective_to')
                            ->label('Data de Término')
                            ->displayFormat('d/m/Y')
                            ->after('effective_from'),

                        TextInput::make('weekly_capacity_hours')
                            ->label('Capacidade Semanal (horas)')
                            ->numeric()
                            ->minValue(0)
                            ->maxValue(168)
                            ->step(0.5)
                            ->suffix('h'),

                        TextInput::make('hour_rate_override')
                            ->label('Taxa por Hora (override)')
                            ->numeric()
                            ->minValue(0)
                            ->prefix('R$')
                            ->step(0.01),

                        TextInput::make('price_rate_override')
                            ->label('Taxa de Preço (override)')
                            ->numeric()
                            ->minValue(0)
                            ->prefix('R$')
                            ->step(0.01),

                        KeyValue::make('meta')
                            ->label('Metadados (opcional)')
                            ->keyLabel('Chave')
                            ->valueLabel('Valor')
                            ->default([])
                            ->dehydrateStateUsing(fn ($state) => empty($state) ? null : $state)
                            ->columnSpanFull(),
                    ])
                    ->mutateDataUsing(function (array $data): array {
                        $data['project_id'] = $this->getOwnerRecord()->id;
                        return $data;
                    }),
            ])
            ->recordActions([
                EditAction::make()
                    ->label('Editar')
                    ->schema([
                        DatePicker::make('effective_from')
                            ->label('Data de Início')
                            ->required()
                            ->displayFormat('d/m/Y'),

                        DatePicker::make('effective_to')
                            ->label('Data de Término')
                            ->displayFormat('d/m/Y')
                            ->after('effective_from'),

                        TextInput::make('weekly_capacity_hours')
                            ->label('Capacidade Semanal (horas)')
                            ->numeric()
                            ->minValue(0)
                            ->maxValue(168)
                            ->step(0.5)
                            ->suffix('h'),

                        TextInput::make('hour_rate_override')
                            ->label('Taxa por Hora (override)')
                            ->numeric()
                            ->minValue(0)
                            ->prefix('R$')
                            ->step(0.01),

                        TextInput::make('price_rate_override')
                            ->label('Taxa de Preço (override)')
                            ->numeric()
                            ->minValue(0)
                            ->prefix('R$')
                            ->step(0.01),

                        KeyValue::make('meta')
                            ->label('Metadados (opcional)')
                            ->keyLabel('Chave')
                            ->valueLabel('Valor')
                            ->default([])
                            ->dehydrateStateUsing(fn ($state) => empty($state) ? null : $state)
                            ->columnSpanFull(),
                    ]),
                DeleteAction::make()
                    ->label('Remover'),
            ])
            ->toolbarActions([
                DeleteBulkAction::make()
                    ->label('Remover Selecionadas'),
            ])
            ->defaultSort('effective_from', 'desc');
    }
}

