<?php

namespace App\Filament\Admin\Resources\Permissions;

use App\Enums\NavigationGroupEnum;
use App\Filament\Admin\Resources\Permissions\Pages\EditPermission;
use App\Filament\Admin\Resources\Permissions\Pages\ListPermissions;
use App\Filament\Admin\Resources\Permissions\Schemas\PermissionForm;
use App\Filament\Admin\Resources\Permissions\Tables\PermissionsTable;
use App\Models\Permission;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Form;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
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

    public static function form(Schema $schema): Schema
    {
        return PermissionForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PermissionsTable::configure($table);
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

