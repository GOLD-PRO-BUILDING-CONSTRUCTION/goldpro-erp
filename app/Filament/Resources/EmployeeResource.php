<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EmployeeResource\Pages;
use App\Models\Employee;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;

class EmployeeResource extends Resource
{
    protected static ?string $model = Employee::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    protected static ?string $navigationLabel = 'طاقم العمل';
    protected static ?string $navigationGroup = 'الموارد البشرية';
    protected static ?int $navigationSort = 999;

    public static function getModelLabel(): string
    {
        return 'موظف';
    }

    public static function getPluralModelLabel(): string
    {
        return 'طاقم العمل';
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            TextInput::make('name')->label('الاسم')->required(),
            TextInput::make('civil_id')->label('الرقم المدني')->required()->maxLength(12),
            TextInput::make('passport_number')->label('رقم الجواز')->nullable(),
            Select::make('gender')->label('الجنس')->options([
                'ذكر' => 'ذكر',
                'أنثى' => 'أنثى',
            ])->required(),
            Select::make('marital_status')->label('الحالة الاجتماعية')->options([
                'أعزب' => 'أعزب',
                'متزوج' => 'متزوج',
                'مطلق' => 'مطلق',
                'أرمل' => 'أرمل',
            ])->required(),
            TextInput::make('phone')->label('رقم الموبايل')->tel()->maxLength(20),
            FileUpload::make('civil_id_front')->label('صورة البطاقة المدنية - الأمام')->image()->directory('employees/civil_id_front'),
            FileUpload::make('civil_id_back')->label('صورة البطاقة المدنية - الخلف')->image()->directory('employees/civil_id_back'),
            DatePicker::make('residency_start')->label('تاريخ بداية الإقامة')->required(),
            DatePicker::make('residency_end')->label('تاريخ انتهاء الإقامة')->required(),
            TextInput::make('job_title')->label('الوظيفة')->required(),
            Select::make('type')->label('النوع')->options([
                'مقاول' => 'مقاول',
                'مهندس' => 'مهندس',
                'موظف' => 'موظف',
            ])->required(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('name')->label('الاسم')->searchable(),
            TextColumn::make('civil_id')->label('الرقم المدني'),
            TextColumn::make('phone')->label('رقم الموبايل'),
            TextColumn::make('job_title')->label('الوظيفة'),
            TextColumn::make('type')->label('النوع'),
        ])
        ->actions([
            Tables\Actions\EditAction::make(),
            Tables\Actions\DeleteAction::make(),
        ])
        ->bulkActions([
            Tables\Actions\DeleteBulkAction::make(),
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEmployees::route('/'),
            'create' => Pages\CreateEmployee::route('/create'),
            'edit' => Pages\EditEmployee::route('/{record}/edit'),
        ];
    }
}
