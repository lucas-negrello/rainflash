<?php

namespace App\Filament\Admin\Resources\Users\RelationManagers;

use App\Models\CompanyUser;
use App\Models\Role;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\AttachAction;
use Filament\Actions\DetachAction;
use Filament\Actions\DetachBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\KeyValue;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
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
            ->columns([
                TextColumn::make('name')
                    ->label('Nome')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('pivot.joined_at')
                    ->label('Entrada')
                    ->dateTime('d/m/Y')
                    ->sortable(),

                TextColumn::make('pivot.id')
                    ->label('Papéis')
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

                TextColumn::make('pivot.currency')
                    ->label('Moeda')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->badge(),

                TextColumn::make('pivot.primary_title')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label('Cargo'),

                TextColumn::make('pivot.left_at')
                    ->label('Saída')
                    ->dateTime('d/m/Y')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->placeholder('—'),
            ])
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
                        ...$this->getPivotFormSchema()
                    ]),
            ])
            ->recordActions([
                ActionGroup::make([
                    EditAction::make()
                        ->label('Editar Vínculo')
                        ->schema($this->getPivotFormSchema()),
                    Action::make('manage_roles')
                        ->label('Gerenciar Papéis')
                        ->icon(Heroicon::OutlinedKey)
                        ->schema(function ($record) {
                            $companyUser = CompanyUser::where('company_id', $record->id)
                                ->where('user_id', $this->getOwnerRecord()->id)
                                ->first();

                            return [
                                Select::make('roles')
                                    ->label('Papéis')
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
                                    ->title('Papéos atualizados com sucesso.')
                                    ->body('Os Papéis foram sincronizados com sucesso.')
                                    ->success()
                                    ->send();
                            }
                        }),
                    DetachAction::make()
                        ->label('Desvincular Empresa'),
                ]),
            ])->toolbarActions([
                DetachBulkAction::make()
                    ->label('Desvincular Empresas'),
            ]);
    }

    protected function getPivotFormSchema(): array
    {
        return [
            TextInput::make('primary_title')
                ->label('Cargo/Título')
                ->maxLength(255),

            Select::make('currency')
                ->label('Moeda')
                ->options([
                    'BRL' => 'Real (BRL)',
                    'USD' => 'Dólar (USD)',
                    'EUR' => 'Euro (EUR)',
                ])
                ->default('BRL'),

            Toggle::make('active')
                ->label('Ativo')
                ->default(true),

            DateTimePicker::make('joined_at')
                ->label('Data de entrada')
                ->displayFormat('d/m/Y')
                ->default(now()),

            DateTimePicker::make('left_at')
                ->label('Data de saída')
                ->displayFormat('d/m/Y'),

            KeyValue::make('meta')
                ->label('Metadados (opcional)')
                ->keyLabel('Chave')
                ->valueLabel('Valor')
                ->default([])
                ->dehydrateStateUsing(fn ($state) => empty($state) ? null : $state),
        ];
    }
}
