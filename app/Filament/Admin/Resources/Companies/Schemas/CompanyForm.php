<?php

namespace App\Filament\Admin\Resources\Companies\Schemas;

use App\Enums\CompanyStatusEnum;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\KeyValue;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class CompanyForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informações Básicas')
                    ->schema([
                        TextInput::make('name')
                            ->label('Nome da Empresa')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn ($state, callable $set) => $set('slug', Str::slug($state))),

                        TextInput::make('slug')
                            ->label('Slug')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255)
                            ->alphaDash()
                            ->helperText('Identificador único da empresa (gerado automaticamente a partir do nome)'),

                        Select::make('status')
                            ->label('Status')
                            ->options(CompanyStatusEnum::labels())
                            ->default(CompanyStatusEnum::ACTIVE)
                            ->required()
                            ->native(false),
                    ])
                    ->columns(2),

                Section::make('Metadados')
                    ->schema([
                        KeyValue::make('meta')
                            ->label('Metadados Adicionais')
                            ->keyLabel('Chave')
                            ->valueLabel('Valor')
                            ->reorderable(),
                    ])
                    ->collapsible()
                    ->collapsed(),
            ]);
    }
}
