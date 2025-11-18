<?php

namespace App\Filament\Admin\Resources\Permissions;

use App\Enums\NavigationGroupEnum;
use App\Filament\Admin\Resources\Permissions\Pages\CreatePermission;
use App\Filament\Admin\Resources\Permissions\Pages\EditPermission;
use App\Filament\Admin\Resources\Permissions\Pages\ListPermissions;
use App\Models\Permission;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Form;
use Filament\Schemas\Components\Section;
use Filament\Support\Icons\Heroicon;
use Filament\Tables;
use Filament\Tables\Table;

class PermissionResource extends Resource
{
    protected static ?string $model = Permission::class;

    protected static string|null|BackedEnum $navigationIcon = Heroicon::OutlinedKey;

    protected static ?string $recordTitleAttribute = 'name';

    protected static string|null|\UnitEnum $navigationGroup = NavigationGroupEnum::ADMINISTRATION->value;

    protected static ?string $navigationLabel = 'Permissões';

    protected static ?string $modelLabel = 'Permissão';

    protected static ?string $pluralModelLabel = 'Permissões';

    protected static ?int $navigationSort = 4;

    public static function form(Form|\Filament\Schemas\Schema $schema): \Filament\Schemas\Schema
    {
        return $schema
            ->schema([
                Section::make('Informações da Permissão')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Nome')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('key')
                            ->label('Chave (Key)')
                            ->required()
                            ->readOnly()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255)
                            ->alphaDash()
                            ->helperText('Identificador único da permissão'),

                        Forms\Components\Textarea::make('description')
                            ->label('Descrição')
                            ->rows(3)
                            ->columnSpanFull()
                            ->helperText('Descrição detalhada sobre o que esta permissão permite fazer'),
                    ])
                    ->columns(2),

                Section::make('Metadados')
                    ->schema([
                        Forms\Components\KeyValue::make('meta')
                            ->label('Metadados Adicionais')
                            ->keyLabel('Chave')
                            ->valueLabel('Valor')
                            ->reorderable(),
                    ])
                    ->collapsible()
                    ->collapsed(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nome')
                    ->searchable()
                    ->sortable()
                    ->icon('heroicon-o-key'),

                Tables\Columns\TextColumn::make('key')
                    ->label('Chave')
                    ->searchable()
                    ->copyable()
                    ->badge()
                    ->color('gray'),

                Tables\Columns\TextColumn::make('roles_count')
                    ->label('Papéis')
                    ->counts('roles')
                    ->badge()
                    ->color('success'),

                Tables\Columns\TextColumn::make('description')
                    ->label('Descrição')
                    ->limit(50)
                    ->wrap()
                    ->placeholder('—')
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Criado em')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Atualizado em')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                EditAction::make(),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPermissions::route('/'),
            'edit' => EditPermission::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
}

