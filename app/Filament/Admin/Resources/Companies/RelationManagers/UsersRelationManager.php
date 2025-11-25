<?php

namespace App\Filament\Admin\Resources\Companies\RelationManagers;

use App\Filament\Shared\Schemas\CompanyUserRelationSchema;
use App\Filament\Shared\Schemas\RoleAttachSchema;
use App\Filament\Shared\Tables\UsersTable as SharedUsersTable;
use Filament\Actions\ActionGroup;
use Filament\Actions\AttachAction;
use Filament\Actions\DetachAction;
use Filament\Actions\DetachBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use App\Models\CompanyUser;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class UsersRelationManager extends RelationManager
{
    protected static string $relationship = 'users';

    protected static ?string $title = 'Usuários';

    protected static ?string $modelLabel = 'usuário';

    protected static ?string $pluralModelLabel = 'usuários';

    protected static ?string $recordTitleAttribute = 'name';

    public function table(Table $table): Table
    {
        return $table
            ->columns(SharedUsersTable::getBase(includeRelationshipFields: true))
            ->filters(SharedUsersTable::getFilters())
            ->columnManagerMaxHeight('400px')
            ->headerActions([
                AttachAction::make()
                    ->label('Vincular usuário')
                    ->preloadRecordSelect()
                    ->schema(fn (AttachAction $action): array => [
                        $action
                            ->getRecordSelect()
                            ->label('Usuário')
                            ->searchable()
                            ->multiple(),
                        ...CompanyUserRelationSchema::getBase()
                    ]),
            ])
            ->recordActions([
                ActionGroup::make([
                    EditAction::make()
                        ->label('Editar vínculo')
                        ->schema(CompanyUserRelationSchema::getBase()),

                    Action::make('manage_roles')
                        ->label('Gerenciar Papéis')
                        ->icon(Heroicon::OutlinedKey)
                        ->modalHeading('Gerenciar Papéis do Usuário')
                        ->fillForm(function ($record) {
                            $companyUser = CompanyUser::where('company_id', $this->getOwnerRecord()->id)
                                ->where('user_id', $record->id)
                                ->first();

                            return [
                                'roles' => $companyUser?->roles->pluck('id')->toArray() ?? [],
                            ];
                        })
                        ->schema(RoleAttachSchema::getBase())
                        ->action(function ($record, array $data) {
                            $companyUser = CompanyUser::where('company_id', $this->getOwnerRecord()->id)
                                ->where('user_id', $record->id)
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
                        ->label('Desvincular usuário'),
                ]),
            ])
            ->toolbarActions([
                DetachBulkAction::make()
                    ->label('Desvincular Selecionados'),
            ]);
    }
}
