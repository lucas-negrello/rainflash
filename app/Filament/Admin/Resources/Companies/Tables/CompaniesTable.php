<?php

namespace App\Filament\Admin\Resources\Companies\Tables;

use App\Enums\CompanyStatusEnum;
use App\Enums\CompanySubscriptionStatusEnum;
use App\Models\Plan;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Schemas\Components\Section;
use Filament\Tables\Columns\ColumnGroup;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class CompaniesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([


                ColumnGroup::make('Empresa', [
                    TextColumn::make('name')
                        ->label('Nome')
                        ->searchable()
                        ->sortable()
                        ->icon('heroicon-o-building-office'),

                    TextColumn::make('status')
                        ->label('Status')
                        ->formatStateUsing(fn(CompanyStatusEnum $state): string => $state->label())
                        ->color(fn(CompanyStatusEnum $state): string => $state->color())
                        ->badge()
                        ->sortable(),

                    TextColumn::make('slug')
                        ->label('Slug')
                        ->searchable()
                        ->toggleable(isToggledHiddenByDefault: true)
                        ->badge()
                        ->color('gray'),

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

                ColumnGroup::make('Plano', [
                    TextColumn::make('current_plan_id')
                        ->label('Plano Atual')
                        ->badge()
                        ->color('primary')
                        ->formatStateUsing(fn(?int $state): string => Plan::find($state)?->name ?? 'Sem Plano'),

                    TextColumn::make('subscription_status')
                        ->label('Status')
                        ->formatStateUsing(fn(?CompanySubscriptionStatusEnum $state): string => $state?->label() ?? 'Indefinido')
                        ->color(fn(?CompanySubscriptionStatusEnum $state): string => $state?->color() ?? 'gray')
                        ->badge()
                        ->sortable(),

                    TextColumn::make('subscription_seats_limit')
                        ->label('Limite de Usuários')
                        ->sortable()
                        ->toggleable(isToggledHiddenByDefault: true),

                    TextColumn::make('subscription_period_start')
                        ->label('Início do Período')
                        ->dateTime('d/m/Y')
                        ->sortable()
                        ->toggleable(isToggledHiddenByDefault: true),

                    TextColumn::make('subscription_period_end')
                        ->label('Fim do Período')
                        ->dateTime('d/m/Y')
                        ->sortable()
                        ->toggleable(isToggledHiddenByDefault: true),

                    TextColumn::make('subscription_trial_end')
                        ->label('Fim do Período de Teste')
                        ->dateTime('d/m/Y')
                        ->sortable()
                        ->toggleable(isToggledHiddenByDefault: true),
                ]),

                ColumnGroup::make('Estatísticas', [
                    TextColumn::make('users_count')
                        ->label('Usuários')
                        ->counts('users')
                        ->toggleable(isToggledHiddenByDefault: true),

                    TextColumn::make('projects_count')
                        ->label('Projetos')
                        ->counts('projects')
                        ->badge()
                        ->color('warning')
                        ->toggleable(isToggledHiddenByDefault: true),
                ]),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Status')
                    ->options(CompanyStatusEnum::dropdownOptions())
                    ->native(false),

                // TODO: Ajustar esse filtro
                TernaryFilter::make('has_users')
                    ->label('Usuários')
                    ->trueLabel('Com Usuários')
                    ->falseLabel('Sem Usuários')
                    ->query(fn ($query) => $query->hasUsers())
                    ->native(false),


                SelectFilter::make('subscription_status')
                    ->label('Status da Assinatura')
                    ->columnSpanFull()
                    ->options(CompanySubscriptionStatusEnum::dropdownOptions())
                    ->native(false),

                Filter::make('active_subscription')
                    ->label('Assinatura Ativa')
                    ->query(fn ($query) => $query->hasActiveSubscription()),

                Filter::make('is_in_trial')
                    ->label('Em Período de Teste')
                    ->query(fn ($query) => $query->isInTrial()),

                Filter::make('has_features')
                    ->label('Com Recursos Adicionais')
                    ->query(fn ($query) => $query->hasFeatures()),

            ], layout: FiltersLayout::Modal)
            ->filtersFormColumns(1)
            ->filtersFormSchema(fn (array $filters): array => [
                Section::make('Empresa')
                    ->description('Filtros relacionados à empresas.')
                    ->schema([
                        $filters['status'],
                        $filters['has_users'],
                    ])->columns(2),

                Section::make('Plano')
                    ->description('Filtros relacionados ao plano e assinatura.')
                    ->schema([
                        $filters['subscription_status'],
                        $filters['active_subscription'],
                        $filters['is_in_trial'],
                        $filters['has_features'],
                    ])->columns(2),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                DeleteBulkAction::make(),
            ])
            ->columnManagerMaxHeight('300px')
            ->paginationPageOptions([10, 25, 50, 100])
            ->defaultSort('created_at', 'desc');
    }
}
