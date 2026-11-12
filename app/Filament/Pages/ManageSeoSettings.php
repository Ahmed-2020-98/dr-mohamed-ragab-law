<?php

namespace App\Filament\Pages;

use App\Filament\Support\Fields;
use BackedEnum;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Support\Icons\Heroicon;

class ManageSeoSettings extends SettingsPage
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedMagnifyingGlass;

    protected static ?string $navigationLabel = 'محركات البحث (SEO)';

    protected static ?string $title = 'إعدادات محركات البحث';

    protected static ?int $navigationSort = 4;

    protected static ?string $slug = 'settings/seo';

    protected function fields(): array
    {
        return [
            Section::make('الصفحة الرئيسية والقيم الافتراضية')->schema([
                TextInput::make('seo_title')->label('عنوان الصفحة الرئيسية في جوجل')->maxLength(70),
                Textarea::make('seo_description')->label('الوصف الافتراضي')->rows(3)->maxLength(320)
                    ->helperText('يُستخدم للصفحة الرئيسية ولأي صفحة ليس لها وصف خاص.'),
                Fields::image('seo_og_image', 'صورة المشاركة (واتساب / فيسبوك)', 'brand')
                    ->helperText('المقاس المثالي 1200×630 بكسل.'),
            ]),
            Section::make('أدوات جوجل')->schema([
                TextInput::make('google_verification')->label('كود توثيق Search Console')->maxLength(120)
                    ->helperText('قيمة content فقط من وسم google-site-verification.'),
                TextInput::make('ga_measurement_id')->label('معرّف Google Analytics')->maxLength(30)->placeholder('G-XXXXXXXXXX'),
                Toggle::make('noindex')->label('إخفاء الموقع عن محركات البحث')
                    ->helperText('فعّله فقط أثناء التجهيز قبل الإطلاق.'),
            ]),
        ];
    }
}
