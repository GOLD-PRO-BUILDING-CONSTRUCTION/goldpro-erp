<?php

namespace App\Filament\Resources;

use App\Filament\Resources\QuotationResource\Pages;
use App\Models\Quotation;
use App\Models\Client; // ربط مع موديل العميل
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput\Mask;
use Filament\Forms\Components\BelongsToSelect;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;

class QuotationResource extends Resource
{
    protected static ?string $model = Quotation::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-duplicate';
    protected static ?string $navigationLabel = 'عروض الاسعار';
    protected static ?string $navigationGroup = 'إدارة المشاريع';
    protected static ?int $navigationSort = 3;

    public static function getModelLabel(): string
    {
        return 'عرض سعر';
    }

    public static function getPluralModelLabel(): string
    {
        return 'عروض الاسعار';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // حقل اختيار العميل
                BelongsToSelect::make('client_id')
                    ->label('العميل')
                    ->relationship('client', 'name')
                    ->searchable()
                    ->required(),
                
                // حقل التاريخ
                DatePicker::make('date')
                    ->label('التاريخ')
                    ->required(),
                
                // حقل رقم القسيمة / المشروع
                TextInput::make('plote_number')
                    ->label('رقم القسيمة / المشروع')
                    ->required(),
                
                // حقل قيمة العرض حسب الوحدة
                TextInput::make('unit_value')
                    ->label('قيمة العرض حسب الوحدة')
                    ->numeric()
                    ->required(),
                
                // حقل وحدة القياس
                Select::make('unit_of_measurement')
                    ->label('وحدة القياس')
                    ->options([
                        'meter_linear' => 'متر طولي',
                        'meter_square' => 'متر مربع',
                        'meter_cubic' => 'متر مكعب',
                        'total' => 'إجمالي',
                    ])
                    ->required(),
                
                // حقل نوع الخدمة
                TextInput::make('service_type')
                    ->label('نوع الخدمة')
                    ->required(),
                
                // حقل تحميل الملف
                FileUpload::make('file')
                    ->label('الملف')
                    ->disk('public')
                    ->directory('quotations'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // عرض اسم العميل
                Tables\Columns\TextColumn::make('quotation_number')->label('رقم عرض السعر'),
                Tables\Columns\TextColumn::make('client.name')->label('العميل'),
                Tables\Columns\TextColumn::make('date')->label('التاريخ'),
                Tables\Columns\TextColumn::make('plote_number')->label('رقم القسيمة / المشروع'),
                Tables\Columns\TextColumn::make('service_type')->label('نوع الخدمة'),
                Tables\Columns\TextColumn::make('unit_value')
                    ->label('قيمة العرض')
                    ->formatStateUsing(function ($state, $record) {
                        $unit = match ($record->unit_of_measurement) {
                            'meter_linear' => '/م.طولي',
                            'meter_square' => '/م2',
                            'meter_cubic' => '/م3',
                            default => '',
                        };

                        return number_format($state, 2) . ' د.ك' . $unit;
                }),

            ])
            ->filters([
                // أضف الفلاتر إذا لزم الأمر
            ])
            ->actions([
                // إضافة زر تعديل
                EditAction::make(),
            ])
            ->bulkActions([
                // إضافة عمليات جماعية مثل الحذف
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            // يمكن إضافة العلاقات إذا لزم الأمر
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListQuotations::route('/'),
            'create' => Pages\CreateQuotation::route('/create'),
            'edit' => Pages\EditQuotation::route('/{record}/edit'),
        ];
    }
}
