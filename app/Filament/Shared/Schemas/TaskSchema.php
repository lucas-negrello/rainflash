<?php

namespace App\Filament\Shared\Schemas;

use App\Contracts\Filament\SharedFilamentSchema;
use App\Enums\TaskStatusEnum;
use App\Enums\TaskTypeEnum;
use App\Models\Task;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;

class TaskSchema implements SharedFilamentSchema
{
    /**
     * Retorna os campos base do formulário de Task.
     *
     * Estes campos são usados tanto no create/edit do TaskResource
     * quanto em RelationManagers.
     *
     * @param bool $useRelationshipFields Se true, mostra versão simplificada para uso em modals
     * @return array
     */
    public static function getBase(bool $useRelationshipFields = false): array
    {
        $fields = [];

        // Só mostra project_id se NÃO estiver em RelationManager
        if (!$useRelationshipFields) {
            $fields[] = Select::make('project_id')
                ->label('Projeto')
                ->relationship('project', 'name')
                ->searchable()
                ->preload()
                ->required()
                ->native(false);
        }

        $fields = array_merge($fields, [
            TextInput::make('title')
                ->label('Título')
                ->required()
                ->maxLength(255),

            RichEditor::make('description')
                ->label('Descrição')
                ->columnSpanFull(),

            Select::make('type')
                ->label('Tipo')
                ->options(TaskTypeEnum::labels())
                ->native(false)
                ->required()
                ->default(TaskTypeEnum::FEATURE->value),

            Select::make('status')
                ->label('Status')
                ->options(TaskStatusEnum::labels())
                ->native(false)
                ->required()
                ->default(TaskStatusEnum::OPEN->value),

            TextInput::make('estimated_minutes')
                ->label('Estimativa (horas)')
                ->numeric()
                ->minValue(0)
                ->step(0.5)
                ->suffix('h')
                ->helperText($useRelationshipFields ? null : 'Tempo estimado em horas')
                ->formatStateUsing(fn ($state) => $state ? ($state / 60) : null)
                ->dehydrateStateUsing(fn ($state) => $state ? ((float)$state * 60) : null),

            Select::make('assignee_company_user_id')
                ->label('Responsável')
                ->options(function (callable $get) {
                    $projectId = $get('project_id');
                    if (!$projectId) return [];

                    return \App\Models\Assignment::where('project_id', $projectId)
                        ->with('companyUser.user')
                        ->get()
                        ->pluck('companyUser.user.name', 'company_user_id')
                        ->toArray();
                })
                ->searchable()
                ->preload()
                ->native(false)
                ->helperText('Apenas usuários atribuídos ao projeto'),

            Select::make('created_by_company_user_id')
                ->label('Criado por')
                ->options(function (callable $get) {
                    $projectId = $get('project_id');
                    if (!$projectId) return [];

                    return \App\Models\Assignment::where('project_id', $projectId)
                        ->with('companyUser.user')
                        ->get()
                        ->pluck('companyUser.user.name', 'company_user_id')
                        ->toArray();
                })
                ->searchable()
                ->required()
                ->native(false)
                ->helperText('Apenas usuários atribuídos ao projeto')
                ->default(fn () => auth()->user()->companyUsers()->first()?->id),

            KeyValue::make('meta')
                ->label('Metadados (opcional)')
                ->keyLabel('Chave')
                ->valueLabel('Valor')
                ->default([])
                ->dehydrateStateUsing(fn ($state) => empty($state) ? null : $state)
                ->columnSpanFull(),
        ]);

        return $fields;
    }
}

