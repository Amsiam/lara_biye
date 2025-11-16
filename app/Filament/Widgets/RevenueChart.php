<?php

namespace App\Filament\Widgets;

use App\Models\Purchase;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class RevenueChart extends ChartWidget
{
    protected ?string $heading = 'Revenue';

    protected static ?int $sort = 3;

    public ?string $filter = '7';

    protected function getData(): array
    {
        $days = (int) $this->filter;
        $data = [];
        $labels = [];

        // Get revenue for the selected period
        for ($i = $days - 1; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $labels[] = $date->format('M d');

            $revenue = Purchase::where('payment_stage', Purchase::STAGE_COMPLETED)
                ->whereDate('created_at', $date)
                ->sum('amount');

            $data[] = (float) $revenue;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Revenue (৳)',
                    'data' => $data,
                    'borderColor' => '#ec4899',
                    'backgroundColor' => 'rgba(236, 72, 153, 0.1)',
                    'fill' => true,
                    'tension' => 0.4,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getFilters(): ?array
    {
        return [
            '7' => 'Last 7 days',
            '14' => 'Last 14 days',
            '30' => 'Last 30 days',
            '90' => 'Last 90 days',
        ];
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'display' => true,
                ],
            ],
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'ticks' => [
                        'callback' => 'function(value) { return "৳" + value.toLocaleString(); }',
                    ],
                ],
            ],
        ];
    }
}
