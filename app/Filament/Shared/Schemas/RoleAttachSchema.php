<?php

namespace App\Filament\Shared\Schemas;

use App\Enums\RoleScopeEnum;
use App\Models\Permission;
use App\Models\Role;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Illuminate\Support\Str;

class RoleAttachSchema
{
    public static function getBase(): array
    {
        return [
            Select::make('roles')
                ->label('Papéis')
                ->options(fn () => Role::orderBy('name')->pluck('name', 'id'))
                ->searchable()
                ->multiple()
                ->preload()
                ->createOptionForm([
                    TextInput::make('name')
                        ->label('Nome do Papel')
                        ->required()
                        ->maxLength(255)
                        ->live(onBlur: true)
                        ->afterStateUpdated(fn ($state, callable $set) => $set('key', Str::slug($state))),

                    TextInput::make('key')
                        ->label('Chave (Key)')
                        ->required()
                        ->maxLength(255)
                        ->alphaDash()
                        ->unique(table: Role::class, column: 'key'),

                    Select::make('scope')
                        ->label('Escopo')
                        ->options(RoleScopeEnum::labels())
                        ->default(RoleScopeEnum::COMPANY->value)
                        ->required()
                        ->native(false),

                    Select::make('permissions')
                        ->label('Permissões')
                        ->options(fn () => Permission::orderBy('name')->pluck('name','id'))
                        ->multiple()
                        ->searchable()
                        ->preload(),
                ])
                ->createOptionUsing(function (array $data): int {
                    $permissions = $data['permissions'] ?? [];
                    unset($data['permissions']);

                    $role = Role::create([
                        'name' => $data['name'],
                        'key' => $data['key'],
                        'scope' => $data['scope'] ?? RoleScopeEnum::COMPANY->value,
                    ]);

                    if (!empty($permissions)) {
                        $role->permissions()->sync($permissions);
                    }

                    return $role->id;
                })
                ->createOptionModalHeading('Criar Novo Papel'),
        ];
    }
}
