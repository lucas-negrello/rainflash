<?php

namespace App\Filament\Admin\Resources\Projects\RelationManagers;

use App\Enums\TimeEntryOriginEnum;
use App\Enums\TimeEntryStatusEnum;
use App\Filament\Shared\Tables\TimeEntriesTable as SharedTimeEntriesTable;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
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
                        $data['project_id'] = $this->getOwnerRecord()->id;
                        $data['origin'] = TimeEntryOriginEnum::MANUAL->value;


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
                        ->schema($this->getTimeEntryFormSchema()),

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

                \Filament\Tables\Filters\SelectFilter::make('month')
                    ->label('Mês')
                    ->options([
                        '01' => 'Janeiro',
                        '02' => 'Fevereiro',
                        '03' => 'Março',
                        '04' => 'Abril',
                        '05' => 'Maio',
                        '06' => 'Junho',
                        '07' => 'Julho',
                        '08' => 'Agosto',
                        '09' => 'Setembro',
                        '10' => 'Outubro',
                        '11' => 'Novembro',
                        '12' => 'Dezembro',
                    ])
                    ->query(function ($query, $state) {
                        if ($state['value'] ?? null) {
                            $query->whereRaw('EXTRACT(MONTH FROM date) = ?', [$state['value']]);
                        }
                    })
                    ->native(false)
                    ->placeholder('Todos os meses'),

                \Filament\Tables\Filters\SelectFilter::make('year')
                    ->label('Ano')
                    ->options(function () {
                        $currentYear = now()->year;
                        $years = [];
                        for ($i = $currentYear - 5; $i <= $currentYear + 1; $i++) {
                            $years[$i] = $i;
                        }
                        return $years;
                    })
                    ->query(function ($query, $state) {
                        if ($state['value'] ?? null) {
                            $query->whereRaw('EXTRACT(YEAR FROM date) = ?', [$state['value']]);
                        }
                    })
                    ->native(false)
                    ->placeholder('Todos os anos'),
            ])
            ->filtersFormColumns(3)
            ->toolbarActions([
                \Filament\Actions\BulkAction::make('approve_bulk')
                    ->label('Aprovar Selecionados')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading('Aprovar Apontamentos')
                    ->modalDescription('Tem certeza que deseja aprovar os apontamentos selecionados?')
                    ->action(function ($records) {
                        $records->each(function ($record) {
                            if ($record->status === TimeEntryStatusEnum::PENDING) {
                                $record->update([
                                    'status' => TimeEntryStatusEnum::APPROVED->value,
                                    'approved_at' => now(),
                                    'reviewed_by_company_user_id' => auth()->user()->companyUsers()->first()?->id,
                                ]);
                            }
                        });

                        \Filament\Notifications\Notification::make()
                            ->title('Apontamentos aprovados')
                            ->body('Os apontamentos pendentes foram aprovados com sucesso.')
                            ->success()
                            ->send();
                    })
                    ->deselectRecordsAfterCompletion(),

                \Filament\Actions\BulkAction::make('reprove_bulk')
                    ->label('Reprovar Selecionados')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->modalHeading('Reprovar Apontamentos')
                    ->modalDescription('Tem certeza que deseja reprovar os apontamentos selecionados?')
                    ->action(function ($records) {
                        $records->each(function ($record) {
                            if ($record->status === TimeEntryStatusEnum::PENDING) {
                                $record->update([
                                    'status' => TimeEntryStatusEnum::REPROVED->value,
                                    'reviewed_by_company_user_id' => auth()->user()->companyUsers()->first()?->id,
                                ]);
                            }
                        });

                        \Filament\Notifications\Notification::make()
                            ->title('Apontamentos reprovados')
                            ->body('Os apontamentos pendentes foram reprovados com sucesso.')
                            ->success()
                            ->send();
                    })
                    ->deselectRecordsAfterCompletion(),

                DeleteBulkAction::make()
                    ->label('Excluir Selecionados'),
            ])
            ->defaultSort('date', 'desc');
    }

    protected function getTimeEntryFormSchema(): array
    {
        return [
            Select::make('task_id')
                ->label('Tarefa')
                ->relationship('task', 'title', fn ($query) => $query->where('project_id', $this->getOwnerRecord()->id))
                ->searchable()
                ->preload()
                ->native(false),

            Select::make('created_by_company_user_id')
                ->label('Criado por')
                ->options(function () {
                    return \App\Models\Assignment::where('project_id', $this->getOwnerRecord()->id)
                        ->with('companyUser.user')
                        ->get()
                        ->pluck('companyUser.user.name', 'company_user_id')
                        ->toArray();
                })
                ->searchable()
                ->required()
                ->native(false)
                ->helperText('Apenas usuários atribuídos ao projeto'),

            DatePicker::make('date')
                ->label('Data')
                ->required()
                ->displayFormat('d/m/Y')
                ->default(now())
                ->maxDate(now()),

            TextInput::make('duration_minutes')
                ->label('Horas Trabalhadas')
                ->numeric()
                ->minValue(0)
                ->step(0.5)
                ->suffix('h')
                ->required()
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

