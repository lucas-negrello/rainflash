<?php

namespace App\Filament\Admin\Resources\Users\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\KeyValue;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informações Básicas')
                    ->schema([
                        TextInput::make('name')
                            ->label('Nome')
                            ->required()
                            ->columnSpanFull()
                            ->maxLength(255),

                        TextInput::make('email')
                            ->label('Email')
                            ->email()
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),

                        TextInput::make('password')
                            ->label('Senha')
                            ->password()
                            ->dehydrated(fn ($state) => filled($state))
                            ->required(fn (string $context): bool => $context === 'create')
                            ->maxLength(255)
                            ->revealable(),
                    ])
                    ->columns(2),

                Section::make('Preferências')
                    ->schema([
                        Select::make('locale')
                            ->label('Idioma')
                            ->options([
                                'pt_BR' => 'Português (Brasil)',
                                'en_US' => 'English (US)',
                            ])
                            ->default('pt_BR'),

                        Select::make('timezone')
                            ->label('Fuso Horário')
                            ->options([
                                'America/Sao_Paulo' => 'America/Sao_Paulo (BRT)',
                                'America/New_York' => 'America/New_York (EST)',
                                'Europe/London' => 'Europe/London (GMT)',
                                'UTC' => 'UTC',
                            ])
                            ->default('America/Sao_Paulo')
                            ->searchable(),
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
