<?php

namespace App\Filament\Pages;

use App\Filament\Support\Fields;
use BackedEnum;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Support\Icons\Heroicon;

class ManageGeneralSettings extends SettingsPage
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedIdentification;

    protected static ?string $navigationLabel = 'الهوية العامة';

    protected static ?string $title = 'الهوية العامة';

    protected static ?int $navigationSort = 1;

    protected static ?string $slug = 'settings/general';

    protected function fields(): array
    {
        return [
            Section::make('اسم المكتب والشعار')->columns(2)->schema([
                TextInput::make('site_name')->label('اسم المكتب')->required()->maxLength(120),
                TextInput::make('site_short_name')->label('الاسم المختصر')->maxLength(60)
                    ->helperText('يُستخدم في عناوين الصفحات: «الخدمات | الاسم المختصر».'),
                TextInput::make('site_tagline')->label('الشعار اللفظي')->maxLength(160)->columnSpanFull(),
                Fields::image('logo', 'الشعار', 'brand')
                    ->helperText('يُفضَّل PNG أو WebP بخلفية شفافة.'),
                Fields::image('lawyer_photo', 'صورة المستشار', 'brand'),
            ]),
            Section::make('التذييل')->schema([
                Textarea::make('footer_about')->label('نبذة التذييل')->rows(3)->maxLength(400),
            ]),
        ];
    }
}
