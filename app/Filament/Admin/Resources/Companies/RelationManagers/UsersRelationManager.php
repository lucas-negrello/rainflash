<?php

namespace App\Filament\Admin\Resources\Companies\RelationManagers;

use App\Models\User;
use Filament\Actions\AttachAction;
use Filament\Actions\DetachAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\KeyValue;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Table;

class UsersRelationManager extends RelationManager
{
    protected static string $relationship = 'users';

    protected static ?string $title = 'Usuários';

    protected static ?string $modelLabel = 'usuário';

    protected static ?string $pluralModelLabel = 'usuários';

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nome')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->copyable(),

                TextColumn::make('pivot.primary_title')
                    ->label('Cargo')
                    ->searchable(),

                IconColumn::make('pivot.active')
                    ->label('Ativo')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),

                TextColumn::make('pivot.currency')
                    ->label('Moeda')
                    ->badge(),

                TextColumn::make('pivot.joined_at')
                    ->label('Entrada')
                    ->dateTime('d/m/Y')
                    ->sortable(),

                TextColumn::make('pivot.left_at')
                    ->label('Saída')
                    ->dateTime('d/m/Y')
                    ->placeholder('—'),
            ])
            ->headerActions([
                AttachAction::make()
                    ->label('Vincular Usuário')
                    ->preloadRecordSelect()
                    ->schema(fn (AttachAction $action): array => [
                        $action->getRecordSelect()
                            ->label('Usuário')
                            ->searchable()
                            ->required()
                            ->options(User::pluck('email', 'id')),


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
                        TextInput::make('primary_title')
                            ->label('Cargo/Título')
                            ->maxLength(255),

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
