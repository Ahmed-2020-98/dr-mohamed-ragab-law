<?php

namespace App\Filament\Pages;

use BackedEnum;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Support\Icons\Heroicon;

class ManageContactSettings extends SettingsPage
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedPhone;

    protected static ?string $navigationLabel = 'بيانات التواصل';

    protected static ?string $title = 'بيانات التواصل والسوشيال';

    protected static ?int $navigationSort = 3;

    protected static ?string $slug = 'settings/contact';

    protected function fields(): array
    {
        return [
            Section::make('الهاتف والواتساب')->columns(2)->schema([
                Repeater::make('phones')
                    ->label('أرقام الهاتف')
                    ->simple(TextInput::make('number')->required()->tel()->maxLength(30))
                    ->addActionLabel('إضافة رقم')
                    ->defaultItems(0)
                    ->columnSpanFull(),
                TextInput::make('whatsapp_eg')->label('واتساب مصر')->tel()->maxLength(30)
                    ->helperText('الرقم المستخدم في أزرار «احجز استشارة».'),
                TextInput::make('whatsapp_sa')->label('واتساب السعودية')->tel()->maxLength(30),
                TextInput::make('email')->label('البريد الإلكتروني المعروض')->email()->maxLength(120),
                TextInput::make('notify_email')->label('بريد استقبال الطلبات')->email()->maxLength(120)
                    ->helperText('يصله إشعار عند كل طلب استشارة جديد (يتطلب ضبط البريد في الاستضافة).'),
            ]),
            Section::make('العنوان ومواعيد العمل')->columns(2)->schema([
                Textarea::make('address')->label('العنوان')->rows(2)->columnSpanFull(),
                TextInput::make('working_hours')->label('مواعيد العمل')->maxLength(160)->columnSpanFull(),
                TextInput::make('map_url')->label('رابط الموقع على خرائط جوجل')->url()->maxLength(500),
                TextInput::make('map_embed')->label('رابط تضمين الخريطة (embed)')->url()->maxLength(1000)
                    ->helperText('من خرائط جوجل: مشاركة ← تضمين خريطة ← انسخ قيمة src فقط.'),
            ]),
            Section::make('حسابات التواصل الاجتماعي')->columns(2)->schema([
                TextInput::make('facebook')->label('فيسبوك')->url()->maxLength(255),
                TextInput::make('tiktok')->label('تيك توك')->url()->maxLength(255),
                TextInput::make('linkedin')->label('لينكدإن')->url()->maxLength(255),
                TextInput::make('youtube')->label('يوتيوب')->url()->maxLength(255),
                TextInput::make('instagram')->label('إنستجرام')->url()->maxLength(255),
                TextInput::make('x_twitter')->label('إكس (تويتر)')->url()->maxLength(255),
            ]),
        ];
    }
}
