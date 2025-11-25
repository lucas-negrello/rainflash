<?php

namespace App\Filament\Admin\Resources\Roles\RelationManagers;

use App\Filament\Shared\Tables\PermissionsTable;
use App\Models\Permission;
use Filament\Actions\AttachAction;
use Filament\Actions\DetachAction;
use Filament\Actions\DetachBulkAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;

class PermissionsRelationManager extends RelationManager
{
    protected static string $relationship = 'permissions';

    protected static ?string $title = 'Permissões';

    protected static ?string $modelLabel = 'permissão';

    protected static ?string $pluralModelLabel = 'permissões';

    public function table(Table $table): Table
    {
        return $table
            ->columns(PermissionsTable::getBase())
            ->headerActions([
                AttachAction::make()
                    ->label('Vincular Permissão')
                    ->preloadRecordSelect()
                    ->schema(fn (AttachAction $action): array => [
                        $action->getRecordSelect()
                            ->label('Permissão')
                            ->options(Permission::pluck('name', 'id'))
                            ->searchable()
                            ->required()
                            ->multiple(),
                    ]),
            ])
            ->recordActions([
                DetachAction::make()
                    ->label('Desvincular'),
            ])
            ->toolbarActions([
                DetachBulkAction::make()
                    ->label('Desvincular Selecionadas'),
            ])
            ->emptyStateHeading('Nenhuma permissão vinculada')
            ->emptyStateDescription('Vincule permissões a esta role para definir o que os usuários podem fazer.')
            ->emptyStateIcon('heroicon-o-key');
    }
}

