<?php

namespace App\Filament\Resources\Services;

use App\Filament\Resources\Services\Pages\CreateService;
use App\Filament\Resources\Services\Pages\EditService;
use App\Filament\Resources\Services\Pages\ListServices;
use App\Filament\Support\Fields;
use App\Models\Service;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use UnitEnum;

class ServiceResource extends Resource
{
    protected static ?string $model = Service::class;

    protected static ?string $recordRouteKeyName = 'id';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedScale;

    protected static string|UnitEnum|null $navigationGroup = 'المحتوى';

    protected static ?string $navigationLabel = 'الخدمات القانونية';

    protected static ?string $modelLabel = 'خدمة';

    protected static ?string $pluralModelLabel = 'الخدمات القانونية';

    protected static ?int $navigationSort = 1;

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Schema $schema): Schema
    {
        return $schema->columns(3)->components([
            Group::make([
                Section::make('بيانات الخدمة')->schema([
                    Fields::title(label: 'اسم الخدمة'),
                    Fields::slug(),
                    Textarea::make('excerpt')
                        ->label('وصف مختصر')
                        ->rows(3)
                        ->maxLength(400)
                        ->helperText('يظهر في فهرس الخدمات وفي نتائج البحث.'),
                    Fields::richText('body', 'الشرح التفصيلي'),
                    Repeater::make('highlights')
                        ->label('ما تشمله الخدمة')
                        ->simple(TextInput::make('text')->required()->maxLength(200))
                        ->addActionLabel('إضافة بند')
                        ->reorderable()
                        ->defaultItems(0),
                ]),
                Fields::seo(),
            ])->columnSpan(['lg' => 2]),

            Group::make([
                Section::make('الصورة والظهور')->schema([
                    Fields::image(directory: 'services'),
                    Fields::active(),
                    Fields::sortOrder(),
                ]),
            ])->columnSpan(['lg' => 1]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image')->label('')->disk('public')->imageSize(48),
                TextColumn::make('title')->label('الخدمة')->searchable()->wrap(),
                IconColumn::make('is_active')->label('ظاهر')->boolean(),
                TextColumn::make('sort_order')->label('الترتيب')->sortable(),
                TextColumn::make('updated_at')->label('آخر تعديل')->since()->sortable(),
            ])
            ->defaultSort('sort_order')
            ->reorderable('sort_order')
            ->recordActions([EditAction::make()])
            ->toolbarActions([BulkActionGroup::make([DeleteBulkAction::make()])]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListServices::route('/'),
            'create' => CreateService::route('/create'),
            'edit' => EditService::route('/{record}/edit'),
        ];
    }
}
