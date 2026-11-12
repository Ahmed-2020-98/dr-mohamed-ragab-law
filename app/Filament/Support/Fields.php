<?php

namespace App\Filament\Support;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;

/**
 * Shared form fields so every resource labels and stores things the same way.
 */
class Fields
{
    /** Title input that fills the slug while the record is being created. */
    public static function title(string $name = 'title', string $label = 'العنوان'): TextInput
    {
        return TextInput::make($name)
            ->label($label)
            ->required()
            ->maxLength(255)
            ->live(onBlur: true)
            ->afterStateUpdated(function (Get $get, Set $set, ?string $state, string $operation): void {
                if ($operation === 'create' || blank($get('slug'))) {
                    $set('slug', ar_slug((string) $state));
                }
            });
    }

    public static function slug(): TextInput
    {
        return TextInput::make('slug')
            ->label('الرابط (slug)')
            ->required()
            ->maxLength(255)
            ->unique(ignoreRecord: true)
            ->dehydrateStateUsing(fn (?string $state): string => ar_slug((string) $state))
            ->helperText('يظهر في رابط الصفحة. يُفضَّل عدم تغييره بعد النشر حتى لا تتأثر نتائج البحث.');
    }

    public static function image(string $name = 'image', string $label = 'الصورة', string $directory = 'uploads'): FileUpload
    {
        return FileUpload::make($name)
            ->label($label)
            ->image()
            ->disk('public')
            ->visibility('public')
            ->directory($directory)
            ->imageEditor()
            ->maxSize(5120)
            ->helperText('JPG أو PNG أو WebP — بحد أقصى 5 ميجابايت.');
    }

    public static function active(string $name = 'is_active', string $label = 'ظاهر في الموقع'): Toggle
    {
        return Toggle::make($name)->label($label)->default(true);
    }

    public static function sortOrder(): TextInput
    {
        return TextInput::make('sort_order')
            ->label('الترتيب')
            ->numeric()
            ->minValue(0)
            ->default(0)
            ->helperText('الأصغر يظهر أولًا.');
    }

    public static function richText(string $name = 'body', string $label = 'المحتوى'): RichEditor
    {
        return RichEditor::make($name)
            ->label($label)
            ->fileAttachmentsDisk('public')
            ->fileAttachmentsDirectory('editor')
            ->columnSpanFull();
    }

    public static function seo(): Section
    {
        return Section::make('تحسين محركات البحث (SEO)')
            ->description('اتركها فارغة ليُستخدم العنوان والمقتطف تلقائيًا.')
            ->collapsed()
            ->schema([
                TextInput::make('meta_title')
                    ->label('عنوان الصفحة في جوجل')
                    ->maxLength(70)
                    ->helperText('حتى 60 حرفًا تقريبًا.'),
                Textarea::make('meta_description')
                    ->label('وصف الصفحة في جوجل')
                    ->rows(3)
                    ->maxLength(320)
                    ->helperText('من 140 إلى 160 حرفًا تقريبًا.'),
            ]);
    }
}
