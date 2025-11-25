<?php

namespace App\Filament\Admin\Resources\Skills\Schemas;

use App\Models\Skill;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class SkillForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informações da Habilidade')
                    ->schema([
                        TextInput::make('name')
                            ->label('Nome')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(function ($state, callable $set, $operation) {
                                if ($operation === 'create' && $state) {
                                    $set('key', Str::slug($state));
                                }
                            }),

                        TextInput::make('key')
                            ->label('Chave (Key)')
                            ->required()
                            ->maxLength(255)
                            ->unique(table: Skill::class, column: 'key', ignoreRecord: true)
                            ->alphaDash()
                            ->helperText('Identificador único da habilidade (gerado automaticamente a partir do nome)'),

                        TextInput::make('category')
                            ->label('Categoria')
                            ->maxLength(255)
                            ->placeholder('Ex: Backend, Frontend, DevOps, etc')
                            ->datalist([
                                'Backend',
                                'Frontend',
                                'DevOps',
                                'Data',
                                'QA',
                                'Mobile',
                                'Design',
                                'Management',
                                'Cloud',
                                'Security',
                            ]),

                        KeyValue::make('meta')
                            ->label('Metadados Adicionais')
                            ->keyLabel('Chave')
                            ->valueLabel('Valor')
                            ->reorderable(),
                    ])
                    ->columns(2),
            ])->columns(1);
    }
}

