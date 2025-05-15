<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AppointmentResource\Pages;
use App\Models\Appointment;
use App\Models\Employee;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;  // << صححت هنا
use Filament\Tables;

class AppointmentResource extends Resource
{
    protected static ?string $model = Appointment::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar';

    protected static ?string $navigationLabel = 'المواعيد';

    public static function getModelLabel(): string
    {
        return 'موعد';
    }

    public static function getPluralModelLabel(): string
    {
        return 'المواعيد';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->label('عنوان الموعد')
                    ->required()
                    ->maxLength(255),

                Forms\Components\Textarea::make('description')
                    ->label('الوصف')
                    ->maxLength(65535),

                Forms\Components\DateTimePicker::make('start_time')
                    ->label('وقت البدء')
                    ->required(),

                Forms\Components\DateTimePicker::make('end_time')
                    ->label('وقت الانتهاء')
                    ->required(),

                Forms\Components\Select::make('employee_id')
                    ->label('الموظف')
                    ->relationship('employee', 'name')
                    ->searchable()
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('عنوان الموعد')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('employee.name')
                    ->label('الموظف')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('start_time')
                    ->label('وقت البدء')
                    ->dateTime('d/m/Y H:i'),

                Tables\Columns\TextColumn::make('end_time')
                    ->label('وقت الانتهاء')
                    ->dateTime('d/m/Y H:i'),
            ])
            ->filters([
                //
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
            'index' => Pages\ListAppointments::route('/'),
            'create' => Pages\CreateAppointment::route('/create'),
            'edit' => Pages\EditAppointment::route('/{record}/edit'),
        ];
    }
}
