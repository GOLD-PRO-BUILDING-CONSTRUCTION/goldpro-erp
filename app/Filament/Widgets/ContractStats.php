<?php

namespace App\Filament\Widgets;

use App\Models\Client;
use App\Models\Project;
use App\Models\Transaction;
use App\Models\Employee;
use App\Models\Quotation;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;

class ContractStats extends BaseWidget
{
    protected function getCards(): array
    {

        return [
            Card::make('عدد العملاء', Client::count())
                ->description('إجمالي العملاء')
                ->color('success'),

            Card::make('عدد عروض الاسعار', Quotation::count())
                ->description('إجمالي عروض الاسعار')
                ->color('success'),

            Card::make('عدد العقود', Project::count())
                ->description('إجمالي عدد العقود')
                ->color('info'),
                
            Card::make('عدد المقاولين', Employee::where('type', 'مقاول')->count())
                ->description('المقاولين')
                ->color('warning'),
        ];
    }
}
