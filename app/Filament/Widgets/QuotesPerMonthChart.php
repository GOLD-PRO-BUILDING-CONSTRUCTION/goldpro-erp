<?php

namespace App\Filament\Widgets;

use App\Models\Quotation;
use Filament\Widgets\ChartWidget;
use BezhanSalleh\FilamentShield\Traits\HasWidgetShield;

class QuotesPerMonthChart extends ChartWidget
{
    use HasWidgetShield;

    protected static ?int $sort = 2;

    public function getHeading(): string
    {
        $currentYear = now()->year;
        return "تسعير السنة الحالية ({$currentYear})";
    }

    protected function getData(): array
    {
        $currentYear = now()->year;

        // جلب عروض الأسعار في هذه السنة
        $quotations = Quotation::whereYear('date', $currentYear)->get();

        // عدد العروض لكل شهر
        $monthlyCounts = array_fill(1, 12, 0);

        foreach ($quotations as $quote) {
            $month = (int) \Carbon\Carbon::parse($quote->date)->format('n'); // 1-12
            $monthlyCounts[$month]++;
        }

        // أسماء الأشهر باللغة العربية
        $arabicMonths = [
            'يناير', 'فبراير', 'مارس', 'أبريل', 'مايو', 'يونيو',
            'يوليو', 'أغسطس', 'سبتمبر', 'أكتوبر', 'نوفمبر', 'ديسمبر'
        ];

        return [
            'datasets' => [
                [
                    'label' => 'عدد عروض الأسعار',
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
