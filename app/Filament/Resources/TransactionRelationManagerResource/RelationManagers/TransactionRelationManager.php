<?php

namespace App\Filament\Resources\BankAccountResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use Filament\Resources\RelationManagers\RelationManager;

class TransactionRelationManager extends RelationManager
{
    protected static string $relationship = 'transactions';
    protected static ?string $title = 'المعاملات';

    public function form(Forms\Form $form): Forms\Form
    {
        return $form->schema([
            Forms\Components\DatePicker::make('date')->label('تاريخ المعاملة')->required(),
            Forms\Components\Select::make('type')
                ->label('النوع')
                ->options([
                    'in' => 'إيداع',
                    'out' => 'سحب',
                ])
                ->required(),
            Forms\Components\TextInput::make('amount')->label('المبلغ')->numeric()->required(),
            Forms\Components\TextInput::make('description')->label('البيان')->maxLength(255),
        ]);
    }

    public function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('date')->label('التاريخ'),
                
                Tables\Columns\BadgeColumn::make('type')
                    ->label('النوع')
                    ->colors([
                        'success' => fn ($state) => $state === 'in', // اللون الأخضر للإيداع
                        'danger' => fn ($state) => $state === 'out', // اللون الأحمر للسحب
                    ])
                    ->formatStateUsing(fn ($state) => $state === 'in' ? 'إيداع' : 'سحب'),

                Tables\Columns\TextColumn::make('amount')->label('المبلغ')->money('KWD', true),
                Tables\Columns\TextColumn::make('description')->label('البيان'),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->after(function ($record, $livewire) {
                        $livewire->redirect(route('filament.admin.resources.bank-accounts.edit', [
                            'record' => $livewire->ownerRecord->id,
                        ]));
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->after(function ($record, $livewire) {
                        $livewire->redirect(route('filament.admin.resources.bank-accounts.edit', [
                            'record' => $livewire->ownerRecord->id,
                        ]));
                    }),
                Tables\Actions\DeleteAction::make()
                    ->after(function ($record, $livewire) {
                        $livewire->redirect(route('filament.admin.resources.bank-accounts.edit', [
                            'record' => $livewire->ownerRecord->id,
                        ]));
                    }),
            ]);
    }
}
