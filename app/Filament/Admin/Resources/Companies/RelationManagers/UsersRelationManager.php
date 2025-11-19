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
use Filament\Support\Icons\Heroicon;
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
                    ->copyable()
                    ->sortable(),

                TextColumn::make('pivot.primary_title')
                    ->label('Cargo/Título')
                    ->searchable(),

                IconColumn::make('pivot.active')
                    ->label('Ativo')
                    ->boolean()
                    ->trueIcon(Heroicon::CheckCircle)
                    ->falseIcon(Heroicon::XCircle)
                    ->trueColor('success')
                    ->falseColor('danger'),

                TextColumn::make('pivot.currency')
                    ->label('Moeda')
                    ->badge(),

                TextColumn::make('pivot.joined_at')
                    ->label('Data de Entrada')
                    ->date('d/m/Y')
                    ->sortable(),

                TextColumn::make('pivot.left_at')
                    ->label('Data de Saída')
                    ->date('d/m/Y')
                    ->placeholder('-'),
            ])
            ->headerActions([
                AttachAction::make()
                    ->label('Vincular usuário')
                    ->preloadRecordSelect()
                    ->recordSelect(function (AttachAction $action) {
                        return $action->getRecordSelect()
                            ->label('Usuário')
                            ->searchable()
                            ->relationship('users', 'email');
                    })
                    ->schema($this->getPivotFormSchema()),
            ])
            ->recordActions([
                EditAction::make()
                    ->label('Editar vínculo')
                    ->schema($this->getPivotFormSchema()),
            ])
            ->toolbarActions([
                DetachAction::make()
                    ->label('Desvincular Selecionados'),
            ]);
    }

    protected function getPivotFormSchema(): array
    {
        return [
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
                ->label('Data de entrada')
                ->displayFormat('d/m/Y')
                ->default(now()),

            DateTimePicker::make('left_at')
                ->label('Data de saída')
                ->displayFormat('d/m/Y'),

            KeyValue::make('meta')
                ->label('Metadados (opcional)')
                ->keyLabel('Chave')
                ->valueLabel('Valor')
                ->default([])
                ->dehydrateStateUsing(fn ($state) => empty($state) ? null : $state),
        ];
    }
}
