<?php

namespace App\Filament\Resources\ConsultationRequests;

use App\Filament\Resources\ConsultationRequests\Pages\ManageConsultationRequests;
use App\Models\ConsultationRequest;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use UnitEnum;

class ConsultationRequestResource extends Resource
{
    protected static ?string $model = ConsultationRequest::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedInboxArrowDown;

    protected static string|UnitEnum|null $navigationGroup = 'التواصل';

    protected static ?string $navigationLabel = 'طلبات الاستشارة';

    protected static ?string $modelLabel = 'طلب استشارة';

    protected static ?string $pluralModelLabel = 'طلبات الاستشارة';

    protected static ?int $navigationSort = 1;

    public static function canCreate(): bool
    {
        return false;
    }

    public static function getNavigationBadge(): ?string
    {
        $count = ConsultationRequest::where('status', 'new')->count();

        return $count ? (string) $count : null;
    }

    public static function getNavigationBadgeColor(): string|array|null
    {
        return 'warning';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->columns(2)->components([
            TextInput::make('name')->label('الاسم')->disabled(),
            TextInput::make('phone')->label('الهاتف')->disabled(),
            TextInput::make('email')->label('البريد الإلكتروني')->disabled(),
            Select::make('service_id')->label('الخدمة المطلوبة')->relationship('service', 'title')->disabled(),
            Textarea::make('message')->label('تفاصيل الطلب')->rows(5)->disabled()->columnSpanFull(),
            Select::make('status')->label('الحالة')->options(ConsultationRequest::STATUSES)->required()->native(false),
            Textarea::make('notes')->label('ملاحظات داخلية')->rows(3)->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->label('الاسم')->searchable()->weight('medium'),
                TextColumn::make('phone')->label('الهاتف')->searchable()->copyable()
                    ->url(fn (ConsultationRequest $record): string => tel_link($record->phone)),
                TextColumn::make('service.title')->label('الخدمة')->placeholder('—')->wrap(),
                TextColumn::make('status')->label('الحالة')->badge()
                    ->formatStateUsing(fn (string $state): string => ConsultationRequest::STATUSES[$state] ?? $state)
                    ->color(fn (string $state): string => match ($state) {
                        'new' => 'warning',
                        'contacted' => 'info',
                        default => 'gray',
                    }),
                TextColumn::make('created_at')->label('تاريخ الطلب')->since()->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('status')->label('الحالة')->options(ConsultationRequest::STATUSES),
            ])
            ->emptyStateHeading('لا توجد طلبات بعد')
            ->emptyStateDescription('طلبات الاستشارة المرسلة من نموذج الموقع ستظهر هنا.')
            ->recordActions([
                Action::make('whatsapp')
                    ->label('واتساب')
                    ->icon(Heroicon::OutlinedChatBubbleLeftRight)
                    ->color('success')
                    ->url(fn (ConsultationRequest $record): string => wa_link($record->phone))
                    ->openUrlInNewTab(),
                EditAction::make()->label('عرض / تحديث'),
                DeleteAction::make(),
            ])
            ->toolbarActions([BulkActionGroup::make([DeleteBulkAction::make()])]);
    }

    public static function getPages(): array
    {
        return ['index' => ManageConsultationRequests::route('/')];
    }
}
