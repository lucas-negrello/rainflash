<?php

namespace App\Filament\Admin\Resources\Users\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nome')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->icon('heroicon-o-envelope'),

                IconColumn::make('email_verified_at')
                    ->label('Verificado')
                    ->getStateUsing(fn ($record) => $record->email_verified_at !== null)
                    ->boolean()
                    ->trueIcon(Heroicon::CheckCircle)
                    ->falseIcon(Heroicon::XCircle)
                    ->trueColor('success')
                    ->falseColor('danger')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),

                TextColumn::make('companies_count')
                    ->label('Empresas')
                    ->counts('companies')
                    ->badge()
                    ->color('success'),

                TextColumn::make('locale')
                    ->label('Idioma')
                    ->badge()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('timezone')
                    ->label('Fuso Horário')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('created_at')
                    ->label('Criado em')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')
                    ->label('Atualizado em')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Filter::make('without_company')
                    ->label('Sem Empresa')
                    ->query(fn (Builder $query): Builder => $query->doesntHave('companies')),
                Filter::make('unverified')
                    ->label('Email Não Verificado')
                    ->query(fn (Builder $query): Builder => $query->whereNull('email_verified_at')),
                Filter::make('portuguese')
                    ->label('Usuários em Português')
                    ->query(fn (Builder $query): Builder => $query->where('locale', 'pt_BR')),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
