<?php

namespace App\Filament\Widgets;

use App\Models\Client;
use App\Models\Project;
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

            Card::make('إجمالي قيمة العقود', number_format(Project::sum('contract_value')) . ' د.ك')
                ->description('مجموع قيم العقود')
                ->color('warning'),
        ];
    }
}
