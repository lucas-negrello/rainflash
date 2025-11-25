<?php

namespace App\Filament\Shared\Tables;

use App\Contracts\Filament\SharedFilamentTable;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\ColumnGroup;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;

class UsersTable implements SharedFilamentTable
{
    public static function getBase(array $extraFields = [], bool $includeRelationshipFields = false): array
    {
        $userColumns = [
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
        ];

        // Adiciona campos de relacionamento apenas se estiver em um RelationManager
        if ($includeRelationshipFields) {
            $userColumns[] = IconColumn::make('active')
                ->label('Ativo')
                ->getStateUsing(fn ($record) => $record->pivot?->active ?? false)
                ->boolean()
                ->trueIcon(Heroicon::CheckCircle)
                ->falseIcon(Heroicon::XCircle)
                ->trueColor('success')
                ->falseColor('danger');

            $userColumns[] = TextColumn::make('currency')
                ->label('Moeda')
                ->getStateUsing(fn ($record) => $record->pivot?->currency)
                ->badge();
        }

        return [
            ColumnGroup::make('Usuário', $userColumns),

            ColumnGroup::make('Localização', [
                TextColumn::make('locale')
                    ->label('Idioma')
                    ->badge()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('timezone')
                    ->label('Fuso Horário')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ]),

            ColumnGroup::make('Datas', [
                ...($includeRelationshipFields ? [
                    TextColumn::make('joined_at')
                        ->label('Data de Entrada')
                        ->getStateUsing(fn ($record) => $record->pivot?->joined_at)
                        ->date('d/m/Y')
                        ->sortable(),

                    TextColumn::make('left_at')
                        ->label('Data de Saída')
                        ->getStateUsing(fn ($record) => $record->pivot?->left_at)
                        ->date('d/m/Y')
                        ->toggleable(isToggledHiddenByDefault: true)
                        ->placeholder('-'),
                ] : []),

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
            ]),

            ColumnGroup::make('Estatísticas', [
                TextColumn::make('companies_count')
                    ->label('Empresas')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->counts('companies')
                    ->badge()
                    ->color('success'),
            ]),

            ...$extraFields,
        ];
    }

    public static function getFilters(array $extraFilters = []): array
    {
        return [
            Filter::make('without_company')
                ->label('Sem Empresa')
                ->query(fn (Builder $query): Builder => $query->doesntHave('companies')),

            Filter::make('unverified')
                ->label('Email Não Verificado')
                ->query(fn (Builder $query): Builder => $query->whereNull('email_verified_at')),

            Filter::make('portuguese')
                ->label('Usuários em Português')
                ->query(fn (Builder $query): Builder => $query->where('locale', 'pt_BR')),

            ...$extraFilters
        ];
    }
}
