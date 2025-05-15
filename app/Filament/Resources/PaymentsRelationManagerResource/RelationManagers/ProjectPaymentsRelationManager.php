<?php

namespace App\Filament\Resources\ProjectResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class ProjectPaymentsRelationManager extends RelationManager
{
    protected static string $relationship = 'payments';
    protected static ?string $title = 'الدفعات';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('payment_number')
                    ->label('رقم الدفعة')
                    ->disabled()
                    ->dehydrated(false), // لا يُرسل للحفظ لأنه يتم حسابه تلقائيًا

                Forms\Components\DatePicker::make('payment_date')
                    ->label('تاريخ الدفع')
                    ->required(),

                Forms\Components\Select::make('status')
                    ->label('حالة الدفع')
                    ->options([
                        'مدفوع' => 'مدفوع',
                        'غير مدفوع' => 'غير مدفوع',
                    ])
                    ->required(),

                Forms\Components\TextInput::make('amount')
                    ->label('قيمة الدفعة')
                    ->numeric()
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(function ($state, callable $set, $get, $component) {
                        $projectValue = $this->getOwnerRecord()->contract_value ?? 0;
                        if ($projectValue > 0) {
                            $percentage = round(($state / $projectValue) * 100, 2);
                            $set('percentage', $percentage);
                        }
                    }),

                Forms\Components\TextInput::make('percentage')
                    ->label('النسبة المئوية')
                    ->numeric()
                    ->disabled()
                    ->dehydrated(), // يتم حفظه لأنه يُحسب قبل الحفظ
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('payment_number')->label('رقم الدفعة'),
                Tables\Columns\TextColumn::make('payment_date')->label('تاريخ الدفع'),
            

                Tables\Columns\TextColumn::make('amount')->label('القيمة'),
                Tables\Columns\TextColumn::make('percentage')->label('النسبة (%)'),
                Tables\Columns\BadgeColumn::make('status')
                    ->label('الحالة')
                    ->colors([
                        'success' => fn ($state) => $state === 'مدفوع', // اللون الأخضر إذا كانت الحالة "مدفوع"
                        'danger' => fn ($state) => $state === 'غير مدفوع', // اللون الأحمر إذا كانت الحالة "غير مدفوع"
                ]),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->mutateFormDataUsing(function (array $data, $livewire) {
                        // تحديد الرقم التالي للدفعة بناءً على الدفعات السابقة
                        $lastPayment = $livewire->ownerRecord->payments()->max('payment_number') ?? 0;
                        $data['payment_number'] = $lastPayment + 1;

                        // حساب النسبة قبل الحفظ
                        $projectValue = $livewire->ownerRecord->contract_value ?? 0;
                        if ($projectValue > 0 && isset($data['amount'])) {
                            $data['percentage'] = round(($data['amount'] / $projectValue) * 100, 2);
                        }

                        return $data;
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }
}
