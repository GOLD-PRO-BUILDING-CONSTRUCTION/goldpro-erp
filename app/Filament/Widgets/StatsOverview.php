<?php

namespace App\Filament\Widgets;

use App\Models\Employee;
use App\Models\Expense;
use App\Models\Transaction;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;

class StatsOverview extends BaseWidget
{
    protected function getCards(): array
    {
        // المبالغ الداخلة (مثل الإيداع والتحويلات الواردة)
        $totalIn = Transaction::where('type', '!=', 'out')->sum('amount');

        // المبالغ الخارجة (سحب)
        $totalOut = Transaction::where('type', 'out')->sum('amount');

        // المصروفات
        $expenses = Expense::sum('amount');

        // الرصيد البنكي
        $bankBalance = $totalIn - $totalOut;

        // الربح = الرصيد البنكي - المصروفات
        $profit = $bankBalance - $expenses;

        return [
            Card::make('إجمالي الرصيد البنكي', number_format($bankBalance, 3) . ' د.ك')
                ->description('إجمالي الرصيد البنكي')
                ->color('warning'),

            Card::make('إجمالي مصروفات المشاريع', number_format($expenses, 3) . ' د.ك')
                ->description('إجمالي مصروفات المشاريع')
                ->color('danger'),

            Card::make('إجمالي الربح', number_format($profit, 3) . ' د.ك')
                ->description('الرصيد البنكي - مصروفات المشاريع')
                ->color('success'),
        ];
    }
}
