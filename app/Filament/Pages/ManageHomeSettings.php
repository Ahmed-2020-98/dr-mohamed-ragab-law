<?php

namespace App\Filament\Pages;

use App\Filament\Support\Fields;
use BackedEnum;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Support\Icons\Heroicon;

class ManageHomeSettings extends SettingsPage
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedHome;

    protected static ?string $navigationLabel = 'محتوى الموقع';

    protected static ?string $title = 'محتوى الصفحة الرئيسية و«من نحن»';

    protected static ?int $navigationSort = 2;

    protected static ?string $slug = 'settings/content';

    protected function fields(): array
    {
        return [
            Tabs::make('content')->tabs([
                Tab::make('الواجهة (الهيرو)')->schema([
                    TextInput::make('hero_kicker')->label('السطر العلوي الصغير')->maxLength(120),
                    TextInput::make('hero_title')->label('العنوان الرئيسي')->required()->maxLength(120),
                    Textarea::make('hero_subtitle')->label('النص التعريفي')->rows(3)->maxLength(400),
                    Repeater::make('hero_credentials')
                        ->label('الصفات المهنية')
                        ->simple(TextInput::make('text')->required()->maxLength(120))
                        ->addActionLabel('إضافة صفة')
                        ->defaultItems(0),
                ]),
                Tab::make('من نحن')->schema([
                    TextInput::make('about_title')->label('عنوان القسم')->maxLength(160),
                    Textarea::make('about_lead')->label('الفقرة الافتتاحية')->rows(4),
                    Fields::richText('about_body', 'النص الكامل'),
                ]),
                Tab::make('الرؤية والرسالة')->schema([
                    Textarea::make('vision')->label('الرؤية')->rows(4),
                    Textarea::make('mission')->label('الرسالة')->rows(4),
                ]),
                Tab::make('القيم')->schema([
                    Repeater::make('values')
                        ->label('قيمنا / أهدافنا')
                        ->schema([
                            TextInput::make('title')->label('العنوان')->required()->maxLength(80),
                            Textarea::make('text')->label('الشرح')->rows(2)->maxLength(300),
                        ])
                        ->itemLabel(fn (array $state): ?string => $state['title'] ?? null)
                        ->collapsible()
                        ->addActionLabel('إضافة قيمة')
                        ->defaultItems(0),
                ]),
                Tab::make('الأرقام')->schema([
                    Repeater::make('stats')
                        ->label('إحصائيات المكتب')
                        ->helperText('اختياري — القسم لا يظهر في الموقع إلا بعد إضافة أرقام حقيقية (مثل: سنوات الخبرة، عدد القضايا).')
                        ->schema([
                            TextInput::make('value')->label('الرقم')->required()->maxLength(20)->placeholder('+15'),
                            TextInput::make('label')->label('الوصف')->required()->maxLength(80)->placeholder('سنة خبرة'),
                        ])
                        ->columns(2)
                        ->addActionLabel('إضافة رقم')
                        ->defaultItems(0),
                ]),
                Tab::make('نطاق العمل')->schema([
                    Repeater::make('jurisdictions')
                        ->label('الدول')
                        ->simple(TextInput::make('text')->required()->maxLength(60))
                        ->addActionLabel('إضافة دولة')
                        ->defaultItems(0),
                    Textarea::make('coverage_text')->label('وصف نطاق العمل')->rows(3),
                ]),
            ]),
        ];
    }
}
