<?php

namespace App\Filament\Admin\Resources\Companies\RelationManagers;

use App\Filament\Shared\Schemas\ProjectSchema;
use App\Filament\Shared\Tables\ProjectsTable as SharedProjectsTable;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;

class ProjectsRelationManager extends RelationManager
{
    protected static string $relationship = 'projects';

    protected static ?string $title = 'Projetos';

    protected static ?string $modelLabel = 'projeto';

    protected static ?string $pluralModelLabel = 'projetos';

    protected static ?string $recordTitleAttribute = 'name';

    public function table(Table $table): Table
    {
        return $table
            ->columns(SharedProjectsTable::getBase(includeRelationshipFields: true))
            ->headerActions([
                CreateAction::make()
                    ->label('Novo Projeto')
                    ->schema(ProjectSchema::getBase(useRelationshipFields: true))
                    ->mutateDataUsing(function (array $data): array {
                        $data['company_id'] = $this->getOwnerRecord()->id;
                        return $data;
                    }),
            ])
            ->recordActions([
                EditAction::make()
                    ->label('Editar')
                    ->schema(ProjectSchema::getBase(useRelationshipFields: true)),
                DeleteAction::make()
                    ->label('Excluir'),
            ])
            ->toolbarActions([
                DeleteBulkAction::make()
                    ->label('Excluir Selecionados'),
            ])
            ->defaultSort('created_at', 'desc');
    }
}

