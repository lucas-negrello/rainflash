<?php

namespace App\Filament\Shared\Schemas;

use App\Contracts\Filament\SharedFilamentSchema;
use App\Enums\ProjectBillingModelEnum;
use App\Enums\ProjectStatusEnum;
use App\Enums\ProjectTypeEnum;
use App\Models\Company;
use App\Models\Project;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Illuminate\Support\Str;

class ProjectSchema implements SharedFilamentSchema
{
    /**
     * Retorna os campos base do formulário de Project.
     *
     * Estes campos são usados tanto no create/edit do ProjectResource
     * quanto em outros contextos onde Projects são criados.
     *
     * @param bool $useRelationshipFields Se true, mostra versão simplificada para uso em modals
     * @return array
     */
    public static function getBase(bool $useRelationshipFields = false): array
    {
        $fields = [
            TextInput::make('name')
                ->label('Nome')
                ->required()
                ->columnSpanFull()
                ->maxLength(255),

            Select::make('company_id')
                ->label('Empresa')
                ->relationship('company', 'name')
                ->searchable()
                ->preload()
                ->required()
                ->native(false),

            TextInput::make('code')
                ->label('Código')
                ->maxLength(255)
                ->disabled()
                ->dehydrated(false)
                ->placeholder('Gerado automaticamente')
                ->helperText('Código único gerado automaticamente baseado no nome do projeto')
                ->hidden(fn (string $operation = null) => $operation === 'create'),

            Select::make('type')
                ->label('Tipo')
                ->options(ProjectTypeEnum::labels())
                ->native(false)
                ->required()
                ->default(ProjectTypeEnum::PRODUCT->value),

            Select::make('billing_model')
                ->label('Modelo de Cobrança')
                ->options(ProjectBillingModelEnum::labels())
                ->native(false)
                ->required()
                ->default(ProjectBillingModelEnum::TNM->value),

            Select::make('status')
                ->label('Status')
                ->options(ProjectStatusEnum::labels())
                ->native(false)
                ->required()
                ->default(ProjectStatusEnum::ACTIVE->value),

            KeyValue::make('meta')
                ->label('Metadados (opcional)')
                ->keyLabel('Chave')
                ->valueLabel('Valor')
                ->columnSpanFull()
                ->default([])
                ->dehydrateStateUsing(fn ($state) => empty($state) ? null : $state),
        ];

        return $fields;
    }
}

