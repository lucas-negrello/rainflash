<?php

namespace App\Filament\Admin\Resources\Companies\Schemas;

use App\Enums\CompanyStatusEnum;
use App\Enums\CompanySubscriptionStatusEnum;
use App\Models\Plan;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Set;
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
                            ->columnSpanFull()
                            ->maxLength(255)
                            ->validationMessages([
                                'required' => 'O nome da empresa é obrigatório.',
                                'max' => 'O nome da empresa não pode ter mais de 255 caracteres.',
                            ])
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn(?string $state, Set $set) => $set('slug', Str::slug($state ?? ''))),

                        TextInput::make('slug')
                            ->label('Slug')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255)
                            ->validationMessages([
                                'required' => 'O slug da empresa é obrigatório.',
                                'max' => 'O slug da empresa não pode ter mais de 255 caracteres.',
                                'unique' => 'Este slug já está em uso por outra empresa.',
                            ])
                            ->alphaDash()
                            ->helperText('Identificador único da empresa (gerado automaticamente a partir do nome)'),

                        Select::make('status')
                            ->label('Status')
                            ->options(CompanyStatusEnum::labels())
                            ->default(CompanyStatusEnum::ACTIVE)
                            ->required()
                            ->validationMessages([
                                'required' => 'O status da empresa é obrigatório.',
                            ])
                            ->native(false),

                        KeyValue::make('meta')
                            ->label('Metadados Adicionais')
                            ->keyLabel('Chave')
                            ->valueLabel('Valor')
                            ->columnSpanFull()
                            ->reorderable(),
                    ])
                    ->columns(2),

                Section::make('Assinatura')
                    ->description('Configure o plano da empresa')
                    ->schema([
                        Select::make('current_plan_id')
                            ->label('Plano Atual')
                            ->options(Plan::pluck('name', 'id'))
                            ->searchable()
                            ->preload()
                            ->helperText('Selecione o plano desta empresa'),

                        Select::make('subscription_status')
                            ->label('Status da Assinatura')
                            ->options(CompanySubscriptionStatusEnum::dropdownOptions())
                            ->default(CompanySubscriptionStatusEnum::ACTIVE)
                            ->native(false)
                            ->helperText('Status atual da subscription'),

                        TextInput::make('subscription_seats_limit')
                            ->label('Limite de Usuários')
                            ->numeric()
                            ->minValue(1)
                            ->helperText('Deixe em branco para ilimitado'),

                        DateTimePicker::make('subscription_period_start')
                            ->label('Início do Período')
                            ->default(now())
                            ->displayFormat('d/m/Y')
                            ->helperText('Data de início da inscrição'),

                        DateTimePicker::make('subscription_period_end')
                            ->label('Fim do Período')
                            ->default(now()->addMonth())
                            ->displayFormat('d/m/Y')
                            ->after('subscription_period_start')
                            ->helperText('Data de término da inscrição'),

                        DateTimePicker::make('subscription_trial_end')
                            ->label('Fim do Trial')
                            ->displayFormat('d/m/Y')
                            ->helperText('Opcional: quando o trial termina'),

                        KeyValue::make('subscription_meta')
                            ->label('Metadados da Subscription')
                            ->keyLabel('Chave')
                            ->valueLabel('Valor')
                            ->columnSpanFull()
                            ->reorderable()
                            ->helperText('Informações adicionais sobre a subscription'),
                    ])
                    ->columns(2)
                    ->collapsible()
                    ->collapsed(),
            ])->columns(1);
    }
}
