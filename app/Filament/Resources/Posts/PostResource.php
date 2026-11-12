<?php

namespace App\Filament\Resources\Posts;

use App\Filament\Resources\Posts\Pages\CreatePost;
use App\Filament\Resources\Posts\Pages\EditPost;
use App\Filament\Resources\Posts\Pages\ListPosts;
use App\Filament\Support\Fields;
use App\Models\Post;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use UnitEnum;

class PostResource extends Resource
{
    protected static ?string $model = Post::class;

    protected static ?string $recordRouteKeyName = 'id';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedNewspaper;

    protected static string|UnitEnum|null $navigationGroup = 'المحتوى';

    protected static ?string $navigationLabel = 'المقالات';

    protected static ?string $modelLabel = 'مقال';

    protected static ?string $pluralModelLabel = 'المقالات';

    protected static ?int $navigationSort = 2;

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Schema $schema): Schema
    {
        return $schema->columns(3)->components([
            Group::make([
                Section::make('المقال')->schema([
                    Fields::title(label: 'عنوان المقال'),
                    Fields::slug(),
                    Textarea::make('excerpt')
                        ->label('مقتطف')
                        ->rows(3)
                        ->maxLength(400)
                        ->helperText('ملخص قصير يظهر في قائمة المقالات ونتائج البحث.'),
                    Fields::richText('body', 'نص المقال'),
                ]),
                Fields::seo(),
            ])->columnSpan(['lg' => 2]),

            Group::make([
                Section::make('النشر')->schema([
                    Toggle::make('is_published')
                        ->label('منشور')
                        ->helperText('المقالات غير المنشورة لا تظهر للزوار.'),
                    DateTimePicker::make('published_at')
                        ->label('تاريخ النشر')
                        ->seconds(false)
                        ->helperText('يُملأ تلقائيًا عند النشر إن تُرك فارغًا.'),
                    Select::make('post_category_id')
                        ->label('التصنيف')
                        ->relationship('category', 'name')
                        ->searchable()
                        ->preload()
                        ->createOptionForm([
                            Fields::title('name', 'اسم التصنيف'),
                            Fields::slug(),
                        ]),
                ]),
                Section::make('صورة المقال')->schema([
                    Fields::image(directory: 'posts'),
                ]),
            ])->columnSpan(['lg' => 1]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image')->label('')->disk('public')->imageSize(48),
                TextColumn::make('title')->label('العنوان')->searchable()->wrap(),
                TextColumn::make('category.name')->label('التصنيف')->badge()->placeholder('—'),
                IconColumn::make('is_published')->label('منشور')->boolean(),
                TextColumn::make('published_at')->label('تاريخ النشر')->date('Y/m/d')->sortable()->placeholder('—'),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                TernaryFilter::make('is_published')->label('حالة النشر'),
                SelectFilter::make('post_category_id')->label('التصنيف')->relationship('category', 'name'),
            ])
            ->recordActions([EditAction::make()])
            ->toolbarActions([BulkActionGroup::make([DeleteBulkAction::make()])]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPosts::route('/'),
            'create' => CreatePost::route('/create'),
            'edit' => EditPost::route('/{record}/edit'),
        ];
    }
}
