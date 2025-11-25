<?php

namespace App\Filament\Admin\Resources\Features;

use App\Enums\NavigationGroupEnum;
use App\Filament\Admin\Resources\Features\Pages\CreateFeature;
use App\Filament\Admin\Resources\Features\Pages\EditFeature;
use App\Filament\Admin\Resources\Features\Pages\ListFeatures;
use App\Filament\Admin\Resources\Features\Schemas\FeatureForm;
use App\Filament\Admin\Resources\Features\Tables\FeaturesTable;
use App\Models\Feature;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class FeatureResource extends Resource
{
    protected static ?string $model = Feature::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedStar;

    protected static string|null|\UnitEnum $navigationGroup = NavigationGroupEnum::FINANCE->value;

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $navigationLabel = 'Features';

    protected static ?string $modelLabel = 'Feature';

    protected static ?string $pluralModelLabel = 'Features';

    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return FeatureForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return FeaturesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListFeatures::route('/'),
            'create' => CreateFeature::route('/create'),
            'edit' => EditFeature::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
}
