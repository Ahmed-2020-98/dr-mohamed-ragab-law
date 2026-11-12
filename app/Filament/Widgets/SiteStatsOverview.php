<?php

namespace App\Filament\Widgets;

use App\Models\ConsultationRequest;
use App\Models\Post;
use App\Models\Service;
use App\Models\Video;
use Filament\Support\Icons\Heroicon;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class SiteStatsOverview extends StatsOverviewWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        return [
            Stat::make('طلبات استشارة جديدة', ConsultationRequest::where('status', 'new')->count())
                ->description('إجمالي الطلبات: '.ConsultationRequest::count())
                ->icon(Heroicon::OutlinedInboxArrowDown)
                ->color('warning'),
            Stat::make('الخدمات الظاهرة', Service::where('is_active', true)->count())
                ->icon(Heroicon::OutlinedScale),
            Stat::make('المقالات المنشورة', Post::where('is_published', true)->count())
                ->description('مسودات: '.Post::where('is_published', false)->count())
                ->icon(Heroicon::OutlinedNewspaper),
            Stat::make('الفيديوهات', Video::where('is_active', true)->count())
                ->icon(Heroicon::OutlinedVideoCamera),
        ];
    }
}
