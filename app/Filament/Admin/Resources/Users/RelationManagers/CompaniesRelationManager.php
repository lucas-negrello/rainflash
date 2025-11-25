<?php

namespace App\Filament\Admin\Resources\Users\RelationManagers;

use App\Filament\Shared\Schemas\CompanyUserRelationSchema;
use App\Filament\Shared\Schemas\RoleAttachSchema;
use App\Filament\Shared\Tables\CompaniesTable as SharedCompaniesTable;
use App\Models\CompanyUser;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\AttachAction;
use Filament\Actions\DetachAction;
use Filament\Actions\DetachBulkAction;
use Filament\Actions\EditAction;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Support\Icons\Heroicon;
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
            ->columns(SharedCompaniesTable::getBase(
                includeRelationshipFields: true
            ))
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
                        ->visible(fn ($record): bool => $record->slug !== config('admin.company.slug'))
                        ->label('Editar Vínculo')
                        ->schema(CompanyUserRelationSchema::getBase()),
                    Action::make('manage_roles')
                        ->visible(fn ($record): bool => $record->slug !== config('admin.company.slug'))
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
                        ->schema(RoleAttachSchema::getBase())
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
                        ->visible(fn ($record): bool => $record->slug !== config('admin.company.slug'))
                        ->label('Desvincular Empresa'),
                ]),
            ]);
    }
}
