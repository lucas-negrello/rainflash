<?php

namespace App\Filament\Admin\Resources\Roles\Schemas;

use App\Enums\RoleScopeEnum;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\KeyValue;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class RoleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informações do Papel')
                    ->schema([
                        TextInput::make('name')
                            ->label('Nome')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(function ($state, callable $set, $operation) {
                                if ($operation === 'create')
                                    $set('key', Str::slug($state));
                            }),

                        TextInput::make('key')
                            ->label('Chave (Key)')
                            ->required()
                            ->readOnly()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255)
                            ->alphaDash()
                            ->helperText('Identificador único do papel (gerado automaticamente a partir do nome)'),

                        Select::make('scope')
                            ->label('Escopo')
                            ->options(RoleScopeEnum::labels())
                            ->required()
                            ->default(RoleScopeEnum::COMPANY)
                            ->native(false)
                            ->helperText('Company: específico da empresa | Global: sistema todo'),

                        Textarea::make('description')
                            ->label('Descrição')
                            ->rows(3)
                            ->columnSpanFull()
                            ->helperText('Descrição detalhada sobre as responsabilidades desta role'),
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
