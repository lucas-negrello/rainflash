<?php

namespace App\Filament\Shared\Schemas;

use App\Contracts\Filament\SharedFilamentSchema;
use App\Models\Skill;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Illuminate\Support\Str;

class SkillSchema implements SharedFilamentSchema
{
    public static function getBase(bool $useRelationshipFields = false): array
    {
        return [
            TextInput::make('name')
                ->label('Nome')
                ->required()
                ->maxLength(255)
                ->live(onBlur: true)
                ->afterStateUpdated(function (Get $get, Set $set, ?string $state) {
                    if (!$get('key') && $state) {
                        $set('key', Str::slug($state));
                    }
                }),

            TextInput::make('key')
                ->label('Chave (Key)')
                ->required()
                ->maxLength(255)
                ->unique(table: Skill::class, column: 'key', ignoreRecord: true)
                ->alphaDash()
                ->helperText($useRelationshipFields ? null : 'Identificador único da habilidade (gerado automaticamente)'),

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
                ->label('Metadados (opcional)')
                ->keyLabel('Chave')
                ->valueLabel('Valor')
                ->default([])
                ->dehydrateStateUsing(fn ($state) => empty($state) ? null : $state),
        ];
    }
}

