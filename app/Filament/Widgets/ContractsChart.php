<?php

namespace App\Filament\Widgets;

use App\Models\Project;
use Filament\Widgets\ChartWidget;

class ContractsPerMonthChart extends ChartWidget
{
    protected static ?int $sort = 2;

    public function getHeading(): string
    {
        $currentYear = now()->year;
        return "مشاريع السنة الحالية ({$currentYear})";
    }

    protected function getData(): array
    {
        $currentYear = now()->year;

        // جميع المشاريع في نفس السنة
        $projects = Project::whereYear('created_at', $currentYear)->get();

        // إنشاء مصفوفة بعدد المشاريع لكل شهر
        $monthlyCounts = array_fill(1, 12, 0);

        foreach ($projects as $project) {
            $month = (int) $project->created_at->format('n'); // من 1 إلى 12
            $monthlyCounts[$month]++;
        }

        // أسماء الشهور بالعربي
        $arabicMonths = [
            'يناير', 'فبراير', 'مارس', 'أبريل', 'مايو', 'يونيو',
            'يوليو', 'أغسطس', 'سبتمبر', 'أكتوبر', 'نوفمبر', 'ديسمبر'
        ];

        return [
            'datasets' => [
                [
                    'label' => 'عدد المشاريع',
                    'data' => array_values($monthlyCounts),
                    'backgroundColor' => 'rgba(255, 191, 64, 0.2)',
                    'borderColor' => '#DAA623',
                    'borderWidth' => 2,
                    'fill' => true,
                ],
            ],
            'labels' => $arabicMonths,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
