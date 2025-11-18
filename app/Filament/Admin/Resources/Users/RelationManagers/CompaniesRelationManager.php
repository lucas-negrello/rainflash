<?php

namespace App\Filament\Admin\Resources\Users\RelationManagers;

use App\Models\Company;
use Filament\Actions\AttachAction;
use Filament\Actions\DetachAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\KeyValue;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class CompaniesRelationManager extends RelationManager
{
    protected static string $relationship = 'companies';

    protected static ?string $title = 'Empresas';

    protected static ?string $modelLabel = 'empresa';

    protected static ?string $pluralModelLabel = 'empresas';

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nome')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('pivot.primary_title')
                    ->label('Cargo/Título')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('pivot.currency')
                    ->label('Moeda')
                    ->badge(),
            ])
            ->headerActions([
                AttachAction::make()
                    ->label('Vincular Empresa')
                    ->preloadRecordSelect()
                    ->schema(fn (AttachAction $action): array => [
                        $action->getRecordSelect()
                            ->label('Empresa')
                            ->searchable()
                            ->required()
                            ->options(Company::pluck('name', 'id')),

                        TextInput::make('primary_title')
                            ->label('Cargo/Título')
                            ->maxLength(255),

                        Select::make('currency')
                            ->label('Moeda')
                            ->options([
                                'BRL' => 'Real (BRL)',
                                'USD' => 'Dólar (USD)',
                                'EUR' => 'Euro (EUR)',
                            ])
                            ->default('BRL'),

                        Toggle::make('active')
                            ->label('Ativo')
                            ->default(true),

                        DateTimePicker::make('joined_at')
                            ->label('Data de Entrada')
                            ->default(now())
                            ->displayFormat('d/m/Y'),

                        DateTimePicker::make('left_at')
                            ->label('Data de Saída')
                            ->displayFormat('d/m/Y'),

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
                        Select::make('currency')
                            ->label('Moeda')
                            ->options([
                                'BRL' => 'Real (BRL)',
                                'USD' => 'Dólar (USD)',
                                'EUR' => 'Euro (EUR)',
                            ]),

                        Toggle::make('active')
                            ->label('Ativo'),

                        DateTimePicker::make('joined_at')
                            ->label('Data de Entrada')
                            ->displayFormat('d/m/Y'),

                        DateTimePicker::make('left_at')
                            ->label('Data de Saída')
                            ->displayFormat('d/m/Y'),

                        KeyValue::make('meta')
                            ->label('Metadados (opcional)')
                            ->keyLabel('Chave')
                            ->valueLabel('Valor')
                            ->default([])
                            ->dehydrateStateUsing(fn ($state) => empty($state) ? null : $state),
                    ]),

                DetachAction::make()
                    ->label('Desvincular'),
            ]);
    }
}
