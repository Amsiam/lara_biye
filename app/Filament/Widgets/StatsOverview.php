<?php

namespace App\Filament\Widgets;

use App\Models\User;
use App\Models\Purchase;
use App\Models\Package;
use App\Models\Connection;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;

class StatsOverview extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        // Calculate statistics
        $totalUsers = User::where('is_admin', false)->count();
        $todayUsers = User::where('is_admin', false)->whereDate('created_at', today())->count();
        $yesterdayUsers = User::where('is_admin', false)->whereDate('created_at', today()->subDay())->count();
        $userTrend = $yesterdayUsers > 0 ? (($todayUsers - $yesterdayUsers) / $yesterdayUsers) * 100 : 0;

        $totalRevenue = Purchase::where('payment_stage', Purchase::STAGE_COMPLETED)->sum('amount');
        $todayRevenue = Purchase::where('payment_stage', Purchase::STAGE_COMPLETED)->whereDate('created_at', today())->sum('amount');
        $yesterdayRevenue = Purchase::where('payment_stage', Purchase::STAGE_COMPLETED)->whereDate('created_at', today()->subDay())->sum('amount');
        $revenueTrend = $yesterdayRevenue > 0 ? (($todayRevenue - $yesterdayRevenue) / $yesterdayRevenue) * 100 : 0;

        $totalPurchases = Purchase::where('payment_stage', Purchase::STAGE_COMPLETED)->count();
        $pendingPurchases = Purchase::whereIn('payment_stage', [Purchase::STAGE_INITIATED, Purchase::STAGE_PENDING])->count();

        $activePackages = Package::where('is_active', true)->count();

        $totalConnectionsDistributed = Connection::sum(DB::raw('CAST(`connection` AS SIGNED)'));

        $verifiedProfiles = User::where('is_admin', false)->whereNotNull('profile_verified_at')->count();
        $unverifiedProfiles = User::where('is_admin', false)->whereNull('profile_verified_at')->count();
        $verificationRate = $totalUsers > 0 ? ($verifiedProfiles / $totalUsers) * 100 : 0;

        return [
            Stat::make('Total Users', number_format($totalUsers))
                ->description($userTrend >= 0 ? '+' . number_format($userTrend, 1) . '% from yesterday' : number_format($userTrend, 1) . '% from yesterday')
                ->descriptionIcon($userTrend >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->color($userTrend >= 0 ? 'success' : 'danger')
                ->chart([7, 12, 9, 15, 18, 22, $todayUsers]),

            Stat::make('Total Revenue', '৳' . number_format($totalRevenue, 2))
                ->description($revenueTrend >= 0 ? '+' . number_format($revenueTrend, 1) . '% from yesterday' : number_format($revenueTrend, 1) . '% from yesterday')
                ->descriptionIcon($revenueTrend >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->color($revenueTrend >= 0 ? 'success' : 'danger')
                ->chart([450, 520, 480, 600, 720, 850, $todayRevenue]),

            Stat::make('Completed Purchases', number_format($totalPurchases))
                ->description('Total successful transactions')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),

            Stat::make('Pending Payments', number_format($pendingPurchases))
                ->description('Awaiting completion')
                ->descriptionIcon('heroicon-m-clock')
                ->color('warning'),

            Stat::make('Active Packages', number_format($activePackages))
                ->description('Currently available')
                ->descriptionIcon('heroicon-m-cube')
                ->color('info'),

            Stat::make('Connections Distributed', number_format($totalConnectionsDistributed))
                ->description('Total profile views available')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('primary'),

            Stat::make('Verified Profiles', number_format($verificationRate, 1) . '%')
                ->description($verifiedProfiles . ' of ' . $totalUsers . ' profiles')
                ->descriptionIcon('heroicon-m-shield-check')
                ->color($verificationRate >= 80 ? 'success' : ($verificationRate >= 50 ? 'warning' : 'danger')),

            Stat::make('Unverified Profiles', number_format($unverifiedProfiles))
                ->description('Click to view unverified profiles')
                ->descriptionIcon('heroicon-m-exclamation-triangle')
                ->color('danger')
                ->url(route('filament.admin.resources.users.index') . '?tab=profile_not_verified')
                ->extraAttributes(['class' => 'cursor-pointer']),

            Stat::make('Today\'s Revenue', '৳' . number_format($todayRevenue, 2))
                ->description('Sales made today')
                ->descriptionIcon('heroicon-m-currency-dollar')
                ->color('success'),
        ];
    }
}
