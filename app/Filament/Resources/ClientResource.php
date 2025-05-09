<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ClientResource\Pages;
use App\Filament\Resources\ClientResource\RelationManagers\ProjectsRelationManager;
use App\Models\Client;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;

class ClientResource extends Resource
{
    protected static ?string $model = Client::class;

    protected static ?string $navigationLabel = 'العملاء';

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationGroup = 'إدارة المشاريع';

    protected static ?int $navigationSort = 2;

    public static function getModelLabel(): string
    {
        return 'عميل';
    }

    public static function getPluralModelLabel(): string
    {
        return 'العملاء';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('المعلومات الشخصية')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('الاسم')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('email')
                            ->label('البريد الإلكتروني')
                            ->email()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('phone')
                            ->label('رقم الهاتف')
                            ->nullable()
                            ->required()
                            ->maxLength(15),
                        Forms\Components\TextInput::make('civil_id')
                            ->label('الرقم المدني')
                            ->nullable()
                            ->maxLength(12),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('رقم العميل')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('name')
                    ->label('الاسم')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('phone')
                    ->label('رقم الهاتف')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('email')
                    ->label('البريد الإلكتروني')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('civil_id')
                    ->label('الرقم المدني')
                    ->sortable()
                    ->searchable(),
            ])
            ->filters([])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->requiresConfirmation()
                        ->action(fn ($records) => $records->each->delete())
                        ->deselectRecordsAfterCompletion()
                        ->color('danger'),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            ProjectsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListClients::route('/'),
            'create' => Pages\CreateClient::route('/create'),
            'edit' => Pages\EditClient::route('/{record}/edit'),
        ];
    }
}
