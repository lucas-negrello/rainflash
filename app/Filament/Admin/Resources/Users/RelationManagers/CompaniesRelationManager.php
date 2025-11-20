<?php

namespace App\Filament\Admin\Resources\Users\RelationManagers;

use App\Filament\Shared\Schemas\CompanyUserRelationSchema;
use App\Models\CompanyUser;
use App\Models\Role;
use App\Models\Permission;
use App\Enums\RoleScopeEnum;
use Illuminate\Support\Str;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\AttachAction;
use Filament\Actions\DetachAction;
use Filament\Actions\DetachBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\KeyValue;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class CompaniesRelationManager extends RelationManager
{
    protected static string $relationship = 'companies';

    protected static ?string $title = 'Empresas';

    protected static ?string $modelLabel = 'empresa';

    protected static ?string $pluralModelLabel = 'empresas';
    protected static ?string $recordTitleAttribute = 'name';

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nome')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('pivot.joined_at')
                    ->label('Entrada')
                    ->dateTime('d/m/Y')
                    ->sortable(),

                TextColumn::make('pivot.id')
                    ->label('Papéis')
                    ->getStateUsing(function ($record) {
                        $companyUser = CompanyUser::where('company_id', $record->id)
                            ->where('user_id', $this->getOwnerRecord()->id)
                            ->first();

                        if (!$companyUser) {
                            return '—';
                        }

                        return $companyUser->roles->pluck('name')->join(', ') ?: '—';
                    })
                    ->wrap(),

                TextColumn::make('pivot.currency')
                    ->label('Moeda')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->badge(),

                TextColumn::make('pivot.primary_title')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label('Cargo'),

                TextColumn::make('pivot.left_at')
                    ->label('Saída')
                    ->dateTime('d/m/Y')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->placeholder('—'),
            ])
            ->headerActions([
                AttachAction::make()
                    ->label('Vincular empresa')
                    ->preloadRecordSelect()
                    ->schema(fn (AttachAction $action): array => [
                        $action
                            ->getRecordSelect()
                            ->multiple()
                            ->label('Empresa')
                            ->searchable(),
                        ...CompanyUserRelationSchema::getBase()
                    ]),
            ])
            ->recordActions([
                ActionGroup::make([
                    EditAction::make()
                        ->label('Editar Vínculo')
                        ->schema(CompanyUserRelationSchema::getBase()),
                    Action::make('manage_roles')
                        ->label('Gerenciar Papéis')
                        ->icon(Heroicon::OutlinedKey)
                        ->modalHeading('Gerenciar Papéis do Usuário')
                        ->fillForm(function ($record) {
                            $companyUser = CompanyUser::where('company_id', $record->id)
                                ->where('user_id', $this->getOwnerRecord()->id)
                                ->first();

                            return [
                                'roles' => $companyUser?->roles->pluck('id')->toArray() ?? [],
                            ];
                        })
                        ->form([
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
                        ])
                        ->action(function ($record, array $data) {
                            $companyUser = CompanyUser::where('company_id', $record->id)
                                ->where('user_id', $this->getOwnerRecord()->id)
                                ->first();

                            if ($companyUser) {
                                $companyUser->roles()->sync($data['roles'] ?? []);

                                Notification::make()
                                    ->title('Papéis atualizados')
                                    ->body('Os papéis do usuário foram sincronizados com sucesso.')
                                    ->success()
                                    ->send();
                            }
                        }),
                    DetachAction::make()
                        ->label('Desvincular Empresa'),
                ]),
            ])->toolbarActions([
                DetachBulkAction::make()
                    ->label('Desvincular Empresas'),
            ]);
    }
}
