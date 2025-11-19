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
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
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
                    ->label('Cargo'),

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
                    ->label('Vincular empresa')
                    ->preloadRecordSelect()
                    ->recordSelect(fn ($select) => $select
                        ->label('Empresa')
                        ->searchable(),
                    )
                    ->schema($this->getPivotFormSchema()),
            ])
            ->recordActions([
                EditAction::make()
                    ->label('Editar vínculo')
                    ->schema($this->getPivotFormSchema()),
                DetachAction::make()
                    ->label('Desvincular'),
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
