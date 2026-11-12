<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\ConsultationRequests\ConsultationRequestResource;
use App\Models\ConsultationRequest;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;

class LatestConsultationRequests extends TableWidget
{
    protected static ?int $sort = 2;

    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->heading('أحدث طلبات الاستشارة')
            ->query(ConsultationRequest::query()->with('service')->latest()->limit(6))
            ->paginated(false)
            ->columns([
                TextColumn::make('name')->label('الاسم'),
                TextColumn::make('phone')->label('الهاتف')->copyable(),
                TextColumn::make('service.title')->label('الخدمة')->placeholder('—'),
                TextColumn::make('status')->label('الحالة')->badge()
                    ->formatStateUsing(fn (string $state): string => ConsultationRequest::STATUSES[$state] ?? $state)
                    ->color(fn (string $state): string => match ($state) {
                        'new' => 'warning',
                        'contacted' => 'info',
                        default => 'gray',
                    }),
                TextColumn::make('created_at')->label('منذ')->since(),
            ])
            ->recordUrl(fn (): string => ConsultationRequestResource::getUrl('index'))
            ->emptyStateHeading('لا توجد طلبات بعد');
    }
}
