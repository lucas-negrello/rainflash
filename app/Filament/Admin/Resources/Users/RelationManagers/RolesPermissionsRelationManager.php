<?php

namespace App\Filament\Admin\Resources\Users\RelationManagers;

use App\Models\CompanyUser;
use App\Models\Role;
use Filament\Actions\AttachAction;
use Filament\Actions\DetachAction;
use Filament\Actions\DetachBulkAction;
use Filament\Resources\RelationManagers\RelationManager;
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
                    ->label('Nome')
                    ->searchable()
                    ->sortable()
                    ->icon('heroicon-o-key'),

                TextColumn::make('key')
                    ->label('Chave')
                    ->searchable()
                    ->copyable()
                    ->badge(),
            ])
            ->headerActions([
                AttachAction::make()
                    ->label('Adicionar permissão')
                    ->preloadRecordSelect()
                    ->recordSelect(fn ($select) => $select
                        ->label('Permissão')
                        ->searchable(),
                    ),
            ])
            ->recordActions([
                DetachAction::make()
                    ->label('Remover'),
            ])
            ->toolbarActions([
                DetachBulkAction::make()
                    ->label('Remover selecionadas'),
            ])
            ->emptyStateHeading('Nenhuma permissão vinculada')
            ->emptyStateDescription('Adicione permissões a este papel usando o botão "Adicionar permissão".')
            ->emptyStateIcon('heroicon-o-key');
    }
}

