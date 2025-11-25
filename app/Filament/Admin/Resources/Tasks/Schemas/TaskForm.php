<?php
namespace App\Filament\Admin\Resources\Tasks\Schemas;
use App\Filament\Shared\Schemas\TaskSchema;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
class TaskForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informações da Tarefa')
                    ->schema(TaskSchema::getBase())
                    ->columns(2),
            ])->columns(1);
    }
}
