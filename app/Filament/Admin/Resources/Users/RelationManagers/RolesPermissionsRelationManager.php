<?php

namespace App\Filament\Admin\Resources\Users\RelationManagers;

use App\Models\CompanyUser;
use App\Models\Role;
use Filament\Actions\Action;
use Filament\Actions\AttachAction;
use Filament\Actions\DetachAction;
use Filament\Actions\DetachBulkAction;
use Filament\Forms\Components\Select;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Notifications\Notification;

class RolesPermissionsRelationManager extends RelationManager
{
    protected static string $relationship = 'companies';

    protected static ?string $title = 'Roles';

    protected static ?string $modelLabel = 'empresa/role';

    protected static ?string $pluralModelLabel = 'empresas/roles';

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Empresa')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('pivot.id')
                    ->label('Roles')
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
            ])
            ->recordActions([
                Action::make('manage_roles')
                    ->label('Gerenciar Roles')
                    ->icon(Heroicon::OutlinedKey)
                    ->schema(function ($record) {
                        $companyUser = CompanyUser::where('company_id', $record->id)
                            ->where('user_id', $this->getOwnerRecord()->id)
                            ->first();

                        return [
                            Select::make('roles')
                                ->label('Roles')
                                ->options(Role::pluck('name', 'id'))
                                ->searchable()
                                ->multiple()
                                ->preload()
                                ->default($companyUser->roles->pluck('id')->toArray() ?? [])
                        ];
                    })
                    ->action(function ($record, array $data) {
                        $companyUser = CompanyUser::where('company_id', $record->id)
                            ->where('user_id', $this->getOwnerRecord()->id)
                            ->first();

                        if ($companyUser) {
                            $companyUser->roles()->sync($data['roles'] ?? []);

                            Notification::make()
                                ->title('Roles atualizadas com sucesso.')
                                ->body('As Roles foram sincronizadas com sucesso.')
                                ->success()
                                ->send();
                        }
                    }),
            ])
            ->emptyStateHeading('Nenhuma empresa vinculada')
            ->emptyStateDescription('Este usuário precisa estar vinculado a empresas para ter papéis atribuídos.')
            ->emptyStateIcon('heroicon-o-building-office');
    }
}

