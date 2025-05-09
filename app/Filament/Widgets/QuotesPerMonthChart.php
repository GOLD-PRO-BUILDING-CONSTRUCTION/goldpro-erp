<?php

namespace App\Filament\Widgets;

use App\Models\Quotation;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class QuotesPerMonthChart extends ChartWidget
{
    protected static ?string $heading = 'عروض الأسعار حسب الشهر (السنة الحالية)';
    protected static ?int $sort = 2;

    protected function getData(): array
    {
        $currentYear = now()->year;

        // استعلام للحصول على البيانات باستخدام حقل 'date' بدلاً من 'created_at'
        $quotes = Quotation::selectRaw("strftime('%m', date) as month, COUNT(*) as total")
            ->whereYear('date', $currentYear)  // استخدام حقل 'date' بدلاً من 'created_at'
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month')
            ->toArray();

        // أسماء الأشهر باللغة العربية
        $arabicMonths = [
            '01' => 'يناير', '02' => 'فبراير', '03' => 'مارس', '04' => 'أبريل', '05' => 'مايو', '06' => 'يونيو',
            '07' => 'يوليو', '08' => 'أغسطس', '09' => 'سبتمبر', '10' => 'أكتوبر', '11' => 'نوفمبر', '12' => 'ديسمبر'
        ];

        $labels = [];
        $data = [];

        // تجهيز البيانات بحيث يكون لدينا قيمة 0 للأشهر التي ليس لها بيانات
        foreach (range(1, 12) as $month) {
            $monthKey = sprintf('%02d', $month);
            $labels[] = $arabicMonths[$monthKey]; // الحصول على اسم الشهر بالعربية
            $data[] = intval($quotes[$monthKey] ?? 0); // التأكد من أن العدد بدون كسور باستخدام intval
        }

        return [
            'datasets' => [
                [
                    'label' => 'عدد عروض الأسعار',
                    'data' => $data,
                    'backgroundColor' => 'rgba(255, 191, 64, 0.2)', // لون التعبئة
                    'borderColor' => '#DAA623', // لون الحدود
                    'borderWidth' => 2, // سمك الخط
                    'fill' => true, // تفعيل التعبئة أسفل الخط
                ],
            ],
            'labels' => $labels, // أسماء الأشهر باللغة العربية
        ];
    }

    // تعيين نوع الشارت إلى Bar (أعمدة)
    protected function getType(): string
    {
        return 'bar';
    }
}
