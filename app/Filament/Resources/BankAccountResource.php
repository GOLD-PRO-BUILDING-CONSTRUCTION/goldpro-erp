<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BankAccountResource\Pages;
use App\Filament\Resources\BankAccountResource\RelationManagers\TransactionRelationManager;
use App\Models\BankAccount;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class BankAccountResource extends Resource
{
    protected static ?string $model = BankAccount::class;

    protected static ?string $navigationIcon = 'heroicon-o-banknotes';
    protected static ?string $navigationLabel = 'الحسابات البنكية';
    protected static ?string $navigationGroup = 'الإدارة المالية';
    protected static ?int $navigationSort = 888;

    public static function getModelLabel(): string
    {
        return 'حساب بنكي';
    }

    public static function getPluralModelLabel(): string
    {
        return 'الحسابات البنكية';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('account_number')
                    ->label('رقم الحساب')
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('iban')
                    ->label('رقم الآيبان')
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('bank_name')
                    ->label('اسم البنك')
                    ->required()
                    ->maxLength(255),

                Forms\Components\Placeholder::make('transactions_sum')
                    ->label('الرصيد')
                    ->content(function ($record) {
                        if (!$record) return '0.000 د.ك';
                        $in = $record->transactions()->where('type', 'in')->sum('amount');
                        $out = $record->transactions()->where('type', 'out')->sum('amount');
                        return number_format($in - $out, 3) . ' د.ك';
                    }),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('account_number')->label('رقم الحساب')->searchable(),
                Tables\Columns\TextColumn::make('iban')->label('رقم الآيبان')->searchable(),
                Tables\Columns\TextColumn::make('bank_name')->label('اسم البنك')->searchable(),
                Tables\Columns\TextColumn::make('transactions_sum')
                    ->label('الرصيد')
                    ->getStateUsing(function ($record) {
                        $in = $record->transactions()->where('type', 'in')->sum('amount');
                        $out = $record->transactions()->where('type', 'out')->sum('amount');
                        return $in - $out;
                    })
                    ->money('KWD', true),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            TransactionRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBankAccounts::route('/'),
            'create' => Pages\CreateBankAccount::route('/create'),
            'edit' => Pages\EditBankAccount::route('/{record}/edit'),
        ];
    }
}
