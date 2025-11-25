<?php

namespace App\Filament\Admin\Resources\Tasks\RelationManagers;

use App\Enums\TimeEntryOriginEnum;
use App\Enums\TimeEntryStatusEnum;
use App\Filament\Shared\Tables\TimeEntriesTable as SharedTimeEntriesTable;
use App\Models\CompanyUser;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;

class TimeEntriesRelationManager extends RelationManager
{
    protected static string $relationship = 'timeEntries';

    protected static ?string $title = 'Apontamentos de Horas';

    protected static ?string $modelLabel = 'apontamento';

    protected static ?string $pluralModelLabel = 'apontamentos';

    public function table(Table $table): Table
    {
        return $table
            ->columns(SharedTimeEntriesTable::getBase(includeRelationshipFields: true))
            ->headerActions([
                CreateAction::make()
                    ->label('Novo Apontamento')
                    ->schema($this->getTimeEntryFormSchema())
                    ->mutateDataUsing(function (array $data): array {
                        $data['task_id'] = $this->getOwnerRecord()->id;
                        $data['project_id'] = $this->getOwnerRecord()->project_id;
                        $data['origin'] = TimeEntryOriginEnum::MANUAL->value;

                        if ((!isset($data['duration_minutes']) || !$data['duration_minutes']) && isset($data['started_at']) && isset($data['ended_at'])) {
                            $start = \Carbon\Carbon::parse($data['started_at']);
                            $end = \Carbon\Carbon::parse($data['ended_at']);
                            $data['duration_minutes'] = $start->diffInMinutes($end);
                        }

                        return $data;
                    }),
            ])
            ->recordActions([
                \Filament\Actions\ActionGroup::make([
                    \Filament\Actions\Action::make('approve')
                        ->label('Aprovar')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->requiresConfirmation()
                        ->modalHeading('Aprovar Apontamento')
                        ->modalDescription('Tem certeza que deseja aprovar este apontamento?')
                        ->action(function ($record) {
                            $record->update([
                                'status' => TimeEntryStatusEnum::APPROVED->value,
                                'approved_at' => now(),
                                'reviewed_by_company_user_id' => auth()->user()->companyUsers()->first()?->id,
                            ]);

                            \Filament\Notifications\Notification::make()
                                ->title('Apontamento aprovado')
                                ->success()
                                ->send();
                        })
                        ->visible(fn ($record) => $record->status === TimeEntryStatusEnum::PENDING),

                    \Filament\Actions\Action::make('reprove')
                        ->label('Reprovar')
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->requiresConfirmation()
                        ->modalHeading('Reprovar Apontamento')
                        ->modalDescription('Tem certeza que deseja reprovar este apontamento?')
                        ->action(function ($record) {
                            $record->update([
                                'status' => TimeEntryStatusEnum::REPROVED->value,
                                'reviewed_by_company_user_id' => auth()->user()->companyUsers()->first()?->id,
                            ]);

                            \Filament\Notifications\Notification::make()
                                ->title('Apontamento reprovado')
                                ->success()
                                ->send();
                        })
                        ->visible(fn ($record) => $record->status === TimeEntryStatusEnum::PENDING),

                    EditAction::make()
                        ->label('Editar')
                        ->schema($this->getTimeEntryFormSchema())
                        ->mutateDataUsing(function (array $data): array {
                            if ((!isset($data['duration_minutes']) || !$data['duration_minutes']) && isset($data['started_at']) && isset($data['ended_at'])) {
                                $start = \Carbon\Carbon::parse($data['started_at']);
                                $end = \Carbon\Carbon::parse($data['ended_at']);
                                $data['duration_minutes'] = $start->diffInMinutes($end);
                            }

                            return $data;
                        }),

                    DeleteAction::make()
                        ->label('Excluir'),
                ]),
            ])
            ->filters([
                \Filament\Tables\Filters\SelectFilter::make('status')
                    ->label('Status')
                    ->options(TimeEntryStatusEnum::labels())
                    ->native(false)
                    ->placeholder('Todos os status'),
            ])
            ->filtersFormColumns(1)
            ->toolbarActions([
                DeleteBulkAction::make()
                    ->label('Excluir Selecionados'),
            ])
            ->defaultSort('status', 'asc')
            ->modifyQueryUsing(function ($query) {
                // Ordena para trazer pendentes primeiro, depois data mais recente
                $query->orderByRaw('CASE WHEN status = ? THEN 0 ELSE 1 END', [TimeEntryStatusEnum::PENDING->value])
                      ->orderBy('started_at', 'desc');
            });
    }

    protected function getTimeEntryFormSchema(): array
    {
        return [
            Select::make('created_by_company_user_id')
                ->label('Criado por')
                ->options(function () {
                    $projectId = $this->getOwnerRecord()->project_id;
                    return \App\Models\Assignment::where('project_id', $projectId)
                        ->with('companyUser.user')
                        ->get()
                        ->pluck('companyUser.user.name', 'company_user_id')
                        ->toArray();
                })
                ->searchable()
                ->required()
                ->native(false)
                ->helperText('Apenas usuários atribuídos ao projeto'),

            DateTimePicker::make('started_at')
                ->label('Início')
                ->required()
                ->displayFormat('d/m/Y H:i')
                ->seconds(false),

            DateTimePicker::make('ended_at')
                ->label('Fim')
                ->required()
                ->displayFormat('d/m/Y H:i')
                ->seconds(false)
                ->after('started_at'),

            TextInput::make('duration_minutes')
                ->label('Duração (horas)')
                ->numeric()
                ->minValue(0)
                ->step(0.5)
                ->suffix('h')
                ->helperText('Preenchido automaticamente se informar início e fim')
                ->formatStateUsing(fn ($state) => $state ? ($state / 60) : null)
                ->dehydrateStateUsing(fn ($state) => $state ? ((float)$state * 60) : null),


            Select::make('status')
                ->label('Status')
                ->options(TimeEntryStatusEnum::labels())
                ->native(false)
                ->required()
                ->default(TimeEntryStatusEnum::PENDING->value),

            Toggle::make('locked')
                ->label('Travado')
                ->default(false),

            Textarea::make('notes')
                ->label('Observações')
                ->rows(3)
                ->maxLength(65535)
                ->columnSpanFull(),

            KeyValue::make('meta')
                ->label('Metadados (opcional)')
                ->keyLabel('Chave')
                ->valueLabel('Valor')
                ->default([])
                ->dehydrateStateUsing(fn ($state) => empty($state) ? null : $state)
                ->columnSpanFull(),
        ];
    }
}

