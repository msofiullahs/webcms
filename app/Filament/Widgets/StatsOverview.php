<?php

namespace App\Filament\Widgets;

use App\Models\ContactSubmission;
use App\Models\Language;
use App\Models\Page;
use App\Models\Post;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Posts', Post::count())
                ->icon('heroicon-o-document-text')
                ->color('primary'),
            Stat::make('Total Pages', Page::count())
                ->icon('heroicon-o-document-duplicate')
                ->color('success'),
            Stat::make('Unread Messages', ContactSubmission::unread()->count())
                ->icon('heroicon-o-envelope')
                ->color('warning'),
            Stat::make('Active Languages', Language::active()->count())
                ->icon('heroicon-o-language')
                ->color('info'),
        ];
    }
}
