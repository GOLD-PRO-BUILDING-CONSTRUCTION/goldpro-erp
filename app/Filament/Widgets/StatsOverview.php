<?php

namespace App\Filament\Widgets;

use App\Models\Expense;
use App\Models\Transaction;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;
use BezhanSalleh\FilamentShield\Traits\HasWidgetShield;

class StatsOverview extends BaseWidget
{
    use HasWidgetShield;
    public function getHeading(): string
    {
        return 'التقارير المالية';
    }

    protected function getCards(): array
    {
        $totalIn = Transaction::where('type', 'in')->sum('amount');
        $totalOut = Transaction::where('type', 'out')->sum('amount');
        $expenses = Expense::sum('amount');
        $bankBalance = $totalIn - $totalOut;
        $profit = $totalIn - $expenses;

        return [
            Card::make('إجمالي الرصيد البنكي', number_format($bankBalance, 3) . ' د.ك')
                ->description('المبالغ الداخلة - الخارجة')
                ->color('warning'),

            Card::make('إجمالي مصروفات المشاريع', number_format($expenses, 3) . ' د.ك')
                ->description('جميع المصروفات المسجلة')
                ->color('danger'),

            Card::make('إجمالي الربح', number_format($profit, 3) . ' د.ك')
                ->description('الإيرادات - المصروفات')
                ->color('success'),
        ];
    }
}
