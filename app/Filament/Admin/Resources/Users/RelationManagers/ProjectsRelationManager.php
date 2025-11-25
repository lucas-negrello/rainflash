<?php

namespace App\Filament\Admin\Resources\Users\RelationManagers;

use App\Models\Assignment;
use App\Models\CompanyUser;
use App\Models\Project;
use Filament\Actions\ActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ProjectsRelationManager extends RelationManager
{
    protected static string $relationship = 'assignments';

    protected static ?string $title = 'Projetos';

    protected static ?string $modelLabel = 'atribuição de projeto';

    protected static ?string $pluralModelLabel = 'atribuições de projetos';

    public function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(function ($query) {
                $query->with(['project', 'companyUser.user']);
            })
            ->columns([
                TextColumn::make('project.code')
                    ->label('Código')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->badge()
                    ->color('gray')
                    ->weight('medium'),

                TextColumn::make('project.name')
                    ->label('Nome do Projeto')
                    ->searchable()
                    ->sortable()
                    ->icon('heroicon-o-folder')
                    ->weight('semibold'),

                TextColumn::make('project.status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn ($state) => $state ? $state->label() : '—')
                    ->color(fn ($state) => $state ? $state->color() : 'gray')
                    ->sortable(),

                TextColumn::make('effective_from')
                    ->label('Início')
                    ->date('d/m/Y')
                    ->sortable(),

                TextColumn::make('effective_to')
                    ->label('Término')
                    ->date('d/m/Y')
                    ->placeholder('Em andamento')
                    ->sortable(),

                TextColumn::make('weekly_capacity_hours')
                    ->label('Cap. Semanal')
                    ->numeric(decimalPlaces: 1)
                    ->suffix(' h')
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('hour_rate_override')
                    ->label('Taxa/Hora')
                    ->money('BRL')
                    ->placeholder('—')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('price_rate_override')
                    ->label('Taxa/Preço')
                    ->money('BRL')
                    ->placeholder('—')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->headerActions([
                CreateAction::make()
                    ->label('Atribuir ao Projeto')
                    ->schema([
                        Select::make('project_id')
                            ->label('Projeto')
                            ->options(function () {
                                $companyIds = $this->getOwnerRecord()->companyUsers()->pluck('company_id');
                                return Project::whereIn('company_id', $companyIds)
                                    ->pluck('name', 'id')
                                    ->toArray();
                            })
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
                        $project = Project::find($data['project_id']);
                        $companyUser = CompanyUser::where('user_id', $this->getOwnerRecord()->id)
                            ->where('company_id', $project->company_id)
                            ->first();

                        if (!$companyUser) {
                            Notification::make()
                                ->title('Erro')
                                ->body('Usuário não está vinculado à empresa deste projeto.')
                                ->danger()
                                ->send();
                            throw new \Exception('Usuário não está vinculado à empresa deste projeto.');
                        }

                        $data['company_user_id'] = $companyUser->id;
                        return $data;
                    }),
            ])
            ->recordActions([
                ActionGroup::make([
                    EditAction::make()
                        ->label('Editar Atribuição')
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
                        ->label('Remover do Projeto'),
                ]),
            ])
            ->toolbarActions([
                DeleteBulkAction::make()
                    ->label('Remover dos Projetos'),
            ])
            ->defaultSort('effective_from', 'desc');
    }
}

