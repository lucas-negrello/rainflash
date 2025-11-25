<?php

namespace App\Filament\Admin\Resources\Projects\RelationManagers;

use App\Filament\Shared\Schemas\TaskSchema;
use App\Filament\Shared\Tables\TasksTable as SharedTasksTable;
use Filament\Actions\AttachAction;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DetachAction;
use Filament\Actions\DetachBulkAction;
use Filament\Actions\EditAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;

class TasksRelationManager extends RelationManager
{
    protected static string $relationship = 'tasks';

    protected static ?string $title = 'Tarefas';

    protected static ?string $modelLabel = 'tarefa';

    protected static ?string $pluralModelLabel = 'tarefas';

    protected static ?string $recordTitleAttribute = 'title';

    public function table(Table $table): Table
    {
        return $table
            ->columns(SharedTasksTable::getBase(includeRelationshipFields: true))
            ->headerActions([
                CreateAction::make()
                    ->label('Nova Tarefa')
                    ->schema(TaskSchema::getBase(useRelationshipFields: true))
                    ->mutateDataUsing(function (array $data): array {
                        $data['project_id'] = $this->getOwnerRecord()->id;
                        return $data;
                    }),
            ])
            ->recordActions([
                EditAction::make()
                    ->label('Editar')
                    ->schema(TaskSchema::getBase(useRelationshipFields: true)),
                DeleteAction::make()
                    ->label('Excluir'),
            ])
            ->toolbarActions([
                DeleteBulkAction::make()
                    ->label('Excluir Selecionadas'),
            ])
            ->defaultSort('created_at', 'desc');
    }
}

