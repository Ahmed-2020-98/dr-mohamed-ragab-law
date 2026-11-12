<?php

namespace App\Filament\Resources\Testimonials;

use App\Filament\Resources\Testimonials\Pages\ManageTestimonials;
use App\Filament\Support\Fields;
use App\Models\Testimonial;
use BackedEnum;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use UnitEnum;

class TestimonialResource extends Resource
{
    protected static ?string $model = Testimonial::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedChatBubbleLeftRight;

    protected static string|UnitEnum|null $navigationGroup = 'المحتوى';

    protected static ?string $navigationLabel = 'آراء العملاء';

    protected static ?string $modelLabel = 'رأي';

    protected static ?string $pluralModelLabel = 'آراء العملاء';

    protected static ?int $navigationSort = 6;

    public static function form(Schema $schema): Schema
    {
        return $schema->columns(1)->components([
            TextInput::make('name')->label('اسم العميل')->required()->maxLength(255),
            TextInput::make('role')->label('الصفة / نوع القضية')->maxLength(255),
            Textarea::make('body')->label('نص الرأي')->required()->rows(4)->maxLength(800),
            Fields::active(),
            Fields::sortOrder(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->label('العميل')->searchable(),
                TextColumn::make('body')->label('الرأي')->limit(70)->wrap(),
                IconColumn::make('is_active')->label('ظاهر')->boolean(),
                TextColumn::make('sort_order')->label('الترتيب')->sortable(),
            ])
            ->defaultSort('sort_order')
            ->reorderable('sort_order')
            ->emptyStateHeading('لا توجد آراء بعد')
            ->emptyStateDescription('قسم آراء العملاء يظهر في الموقع تلقائيًا عند إضافة أول رأي.')
            ->recordActions([EditAction::make(), DeleteAction::make()]);
    }

    public static function getPages(): array
    {
        return ['index' => ManageTestimonials::route('/')];
    }
}
