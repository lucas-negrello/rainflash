<?php

namespace App\Filament\Admin\Resources\Roles;

use App\Enums\NavigationGroupEnum;
use App\Filament\Admin\Resources\Roles\Pages\CreateRole;
use App\Filament\Admin\Resources\Roles\Pages\EditRole;
use App\Filament\Admin\Resources\Roles\Pages\ListRoles;
use App\Filament\Admin\Resources\Roles\Schemas\RoleForm;
use App\Filament\Admin\Resources\Roles\Tables\RolesTable;
use App\Models\Role;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class RoleResource extends Resource
{
    protected static ?string $model = Role::class;

    protected static string|null|BackedEnum $navigationIcon = Heroicon::OutlinedShieldCheck;

    protected static ?string $recordTitleAttribute = 'name';

    protected static string|null|\UnitEnum $navigationGroup = NavigationGroupEnum::ADMINISTRATION->value;

    protected static ?string $navigationLabel = 'Papéis';

    protected static ?string $modelLabel = 'Papel';

    protected static ?string $pluralModelLabel = 'Papéis';

    protected static ?int $navigationSort = 3;

    public static function form(Schema $schema): Schema
    {
        return RoleForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return RolesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\PermissionsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListRoles::route('/'),
            'create' => CreateRole::route('/create'),
            'edit' => EditRole::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
}
