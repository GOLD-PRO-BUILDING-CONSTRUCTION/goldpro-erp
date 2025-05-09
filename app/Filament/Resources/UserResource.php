<?php

namespace App\Filament\Resources;

use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use App\Filament\Resources\UserResource\Pages;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-user';
    protected static ?string $navigationLabel = 'المستخدمين';
    protected static ?string $navigationGroup = 'الموارد البشرية';
    protected static ?int $navigationSort = 999;

    public static function getModelLabel(): string
    {
        return 'مستخدم';
    }

    public static function getPluralModelLabel(): string
    {
        return 'المستخدمين';
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            TextInput::make('name')
                ->label('الاسم')
                ->required()
                ->maxLength(255),

            TextInput::make('email')
                ->label('البريد الإلكتروني')
                ->email()
                ->required()
                ->unique(ignoreRecord: true),

            TextInput::make('password')
                ->label('كلمة المرور')
                ->password()
                ->required(fn(string $context) => $context === 'create')
                ->dehydrateStateUsing(fn($state) => !empty($state) ? bcrypt($state) : null)
                ->dehydrated(fn($state) => filled($state))
                ->maxLength(255),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->label('الاسم')->searchable()->sortable(),
                TextColumn::make('email')->label('البريد الإلكتروني')->searchable()->sortable(),
                TextColumn::make('created_at')->label('تاريخ الإنشاء')->dateTime(),
            ])
            ->filters([])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
