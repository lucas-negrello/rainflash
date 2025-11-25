<?php

namespace App\Filament\Shared\Schemas;

use App\Contracts\Filament\SharedFilamentSchema;
use App\Enums\FeatureTypeEnum;
use App\Models\Feature;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Illuminate\Support\Str;

class FeatureSchema implements SharedFilamentSchema
{
    public static function getBase(bool $useRelationshipFields = false): array
    {
        return [
            TextInput::make('name')
                ->label('Nome')
                ->required()
                ->disabled()
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
                ->disabled()
                ->unique(table: Feature::class, column: 'key', ignoreRecord: true)
                ->alphaDash()
                ->helperText($useRelationshipFields ? null : 'Identificador único da feature (gerado automaticamente)'),

            Select::make('type')
                ->label('Tipo')
                ->disabled()
                ->options(FeatureTypeEnum::labels())
                ->native(false)
                ->required()
                ->helperText($useRelationshipFields ? null : 'Boleano: sim/não | Limite: valor numérico | Nível: tier/categoria'),
        ];
    }
}

