<?php

namespace App\Filament\Widgets;

use App\Models\Project;
use Filament\Widgets\ChartWidget;
use BezhanSalleh\FilamentShield\Traits\HasWidgetShield;

class ProjectsPerMonthChart extends ChartWidget
{
    use HasWidgetShield;

    public function getHeading(): string
    {
        $currentYear = now()->year;
        return "عدد المشاريع في {$currentYear}";
    }

    protected static ?int $sort = 4; // عشان يظهر بعد الرسومات الأخرى

    protected function getData(): array
    {
        $currentYear = now()->year;

        $projects = Project::selectRaw("strftime('%m', start_date) as month, COUNT(*) as total")
            ->whereYear('start_date', $currentYear)
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month')
            ->toArray();

        $arabicMonths = [
            '01' => 'يناير', '02' => 'فبراير', '03' => 'مارس', '04' => 'أبريل', '05' => 'مايو', '06' => 'يونيو',
            '07' => 'يوليو', '08' => 'أغسطس', '09' => 'سبتمبر', '10' => 'أكتوبر', '11' => 'نوفمبر', '12' => 'ديسمبر'
        ];

        $labels = [];
        $data = [];

        foreach (range(1, 12) as $month) {
            $monthKey = sprintf('%02d', $month);
            $labels[] = $arabicMonths[$monthKey];
            $data[] = intval($projects[$monthKey] ?? 0);
        }

        return [
            'datasets' => [
                [
                    'label' => 'عدد المشاريع',
                    'data' => $data,
                    'backgroundColor' => 'rgba(54, 162, 235, 0.2)',
                    'borderColor' => '#36A2EB',
                    'borderWidth' => 2,
                    'fill' => true,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
