<?php

namespace App\Filament\Widgets;

use App\Models\Audit;
use App\Models\Document;
use App\Models\Survey;
use App\Models\SurveyResponse;
use App\Models\User;
use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;
    protected static ?string $pollingInterval = '15s';

    protected function getStats(): array
    {
        return [
            Stat::make('Survei Aktif', Survey::where('status', 'active')->count())
                ->description('Survei yang sedang berjalan')
                ->descriptionIcon('heroicon-m-clipboard-document-check')
                ->chart([7, 4, 6, 8, 5, 3, 8])
                ->color('success'),

            Stat::make('Total Respons', SurveyResponse::where('is_completed', true)->count())
                ->description('Jumlah respons yang telah diterima')
                ->descriptionIcon('heroicon-m-users')
                ->chart([8, 3, 4, 5, 6, 3, 5, 7])
                ->color('primary'),

            Stat::make('Audit Yang Berlangsung', Audit::where('status', 'ongoing')->count())
                ->description('Audit yang sedang berlangsung')
                ->descriptionIcon('heroicon-m-clipboard-document-list')
                ->chart([5, 2, 4, 6, 7, 8, 5])
                ->color('warning'),

            Stat::make('Dokumen', Document::count())
                ->description('Jumlah dokumen dalam sistem')
                ->descriptionIcon('heroicon-m-document')
                ->chart([6, 5, 8, 4, 5, 7, 9])
                ->color('info'),
        ];
    }
}
