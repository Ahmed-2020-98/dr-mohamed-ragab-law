<?php

namespace App\Filament\Resources\Videos;

use App\Filament\Resources\Videos\Pages\ManageVideos;
use App\Filament\Support\Fields;
use App\Models\Video;
use BackedEnum;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use UnitEnum;

class VideoResource extends Resource
{
    protected static ?string $model = Video::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedVideoCamera;

    protected static string|UnitEnum|null $navigationGroup = 'الوسائط';

    protected static ?string $navigationLabel = 'الفيديوهات';

    protected static ?string $modelLabel = 'فيديو';

    protected static ?string $pluralModelLabel = 'الفيديوهات';

    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return $schema->columns(1)->components([
            TextInput::make('title')->label('عنوان الفيديو')->required()->maxLength(255),
            Textarea::make('description')->label('وصف مختصر')->rows(2)->maxLength(500),
            FileUpload::make('file')
                ->label('ملف الفيديو')
                ->disk('public')
                ->visibility('public')
                ->directory('videos')
                ->acceptedFileTypes(['video/mp4', 'video/webm', 'video/quicktime'])
                ->maxSize(102400)
                ->helperText('MP4 بحد أقصى 100 ميجابايت — أو اترك الحقل فارغًا وضع رابطًا خارجيًا بالأسفل.'),
            TextInput::make('url')
                ->label('رابط خارجي (يوتيوب / تيك توك / فيسبوك)')
                ->url()
                ->maxLength(255)
                ->requiredWithout('file')
                ->validationMessages(['required_without' => 'ارفع ملف الفيديو أو ضع رابطًا خارجيًا.']),
            Fields::image('poster', 'صورة الغلاف', 'videos/posters'),
            Toggle::make('is_featured')->label('يظهر في الصفحة الرئيسية'),
            Fields::active(),
            Fields::sortOrder(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('poster')->label('')->disk('public')->imageSize(56),
                TextColumn::make('title')->label('العنوان')->searchable()->wrap(),
                IconColumn::make('is_featured')->label('في الرئيسية')->boolean(),
                IconColumn::make('is_active')->label('ظاهر')->boolean(),
                TextColumn::make('sort_order')->label('الترتيب')->sortable(),
            ])
            ->defaultSort('sort_order')
            ->reorderable('sort_order')
            ->recordActions([EditAction::make(), DeleteAction::make()]);
    }

    public static function getPages(): array
    {
        return ['index' => ManageVideos::route('/')];
    }
}
