<?php

namespace App\Filament\Admin\Resources\Users\RelationManagers;

use App\Models\CompanyUser;
use App\Models\Role;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Components\Section;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Notifications\Notification;

class RolesPermissionsRelationManager extends RelationManager
{
    protected static string $relationship = 'companies';

    protected static ?string $title = 'Papéis & Permissões';

    protected static ?string $modelLabel = 'papéis & permissões';

    protected static ?string $pluralModelLabel = 'papeis e permissões';

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Empresa')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('companyUser.roles_count')
                    ->label('Papéis')
                    ->getStateUsing(function ($record) {
                        $companyUser = CompanyUser::where('company_id', $record->id)
                            ->where('user_id', $this->getOwnerRecord()->id)
                            ->first();
                        return $companyUser?->roles()->count() ?? 0;
                    })
                    ->badge()
                    ->color('info'),

                TextColumn::make('companyUser.roles')
                    ->label('Papéis Atribuídos')
                    ->getStateUsing(function ($record) {
                        $companyUser = CompanyUser::where('company_id', $record->id)
                            ->where('user_id', $this->getOwnerRecord()->id)
                            ->first();
                        return $companyUser?->roles()->pluck('name')->join(', ') ?? '—';
                    })
                    ->wrap(),
            ])
            ->recordActions([
                Action::make('manage_roles')
                    ->label('Gerenciar Papéis')
                    ->icon('heroicon-o-shield-check')
                    ->form(function ($record) {
                        $companyUser = CompanyUser::where('company_id', $record->id)
                            ->where('user_id', $this->getOwnerRecord()->id)
                            ->first();

                        return [
                            Section::make('Papéis')
                                ->schema([
                                    Select::make('roles')
                                        ->label('Papéis')
                                        ->multiple()
                                        ->options(Role::where('scope', \App\Enums\RoleScopeEnum::COMPANY)
                                            ->pluck('name', 'id'))
                                        ->default($companyUser?->roles()->pluck('roles.id')->toArray() ?? [])
                                        ->preload()
                                        ->searchable(),
                                ]),
                        ];
                    })
                    ->action(function (array $data, $record) {
                        $companyUser = CompanyUser::where('company_id', $record->id)
                            ->where('user_id', $this->getOwnerRecord()->id)
                            ->first();

                        if ($companyUser) {
                            $companyUser->roles()->sync($data['roles'] ?? []);

                            Notification::make()
                                ->title('Papéis atualizados com sucesso')
                                ->success()
                                ->send();
                        }
                    }),
            ])
            ->emptyStateHeading('Nenhuma empresa vinculada')
            ->emptyStateDescription('Vincule o usuário a uma empresa primeiro para gerenciar papéis.');
    }
}

