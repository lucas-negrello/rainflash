<?php

namespace App\Filament\Admin\Resources\Permissions\Schemas;

use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PermissionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Informações da Permissão')
                    ->schema([
                        TextInput::make('name')
                            ->label('Nome')
                            ->required()
                            ->maxLength(255),

                        TextInput::make('key')
                            ->label('Chave (Key)')
                            ->required()
                            ->readOnly()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255)
                            ->alphaDash()
                            ->helperText('Identificador único da permissão'),

                        Textarea::make('description')
                            ->label('Descrição')
                            ->rows(3)
                            ->columnSpanFull()
                            ->helperText('Descrição detalhada sobre o que esta permissão permite fazer'),

                        KeyValue::make('meta')
                            ->columnSpanFull()
                            ->label('Metadados Adicionais')
                            ->keyLabel('Chave')
                            ->valueLabel('Valor')
                            ->reorderable(),
                    ])
                    ->columns(2),
            ])->columns(1);
    }
}
