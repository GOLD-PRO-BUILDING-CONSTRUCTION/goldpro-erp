<?php

namespace App\Filament\Resources\ProjectResource\RelationManagers;

use App\Models\Expense;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\BelongsToSelect;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Textarea;
use Illuminate\Support\Facades\Auth;

class HasManyRelationManager extends RelationManager
{
    protected static string $relationship = 'expenses'; // اسم العلاقة مع المصروفات
    protected static ?string $title = 'المصروفات';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                DatePicker::make('date')
                    ->label('التاريخ')
                    ->required(),

                TextInput::make('amount')
                    ->label('المبلغ')
                    ->required()
                    ->numeric()
                    ->minValue(0),

                Textarea::make('description')
                    ->label('البيان')
                    ->required(),

                TextInput::make('receiver_name')
                    ->label('اسم المستلم')
                    ->required(),

                // عرض اسم المحاسب غير قابل للتعديل
                BelongsToSelect::make('accountant_id')
                    ->label('المحاسب')
                    ->relationship('user', 'name')
                    ->default(Auth::id())
                    ->disabled(),

                // حقل مخفي لإرسال قيمة accountant_id
                Forms\Components\Hidden::make('accountant_id')
                    ->default(Auth::id())
                    ->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('date')
                    ->label('التاريخ')
                    ->sortable(),

                TextColumn::make('amount')
                    ->label('المبلغ')
                    ->sortable()
                    ->money('KWD', true),

                TextColumn::make('description')
                    ->label('البيان'),

                TextColumn::make('receiver_name')
                    ->label('اسم المستلم'),

                TextColumn::make('user.name') // تأكد أن العلاقة اسمها employee
                    ->label('المحاسب'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
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
