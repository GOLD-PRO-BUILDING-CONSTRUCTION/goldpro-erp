<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProjectResource\Pages;
use App\Filament\Resources\ProjectResource\RelationManagers\ProjectPaymentsRelationManager;
use App\Filament\Resources\ProjectResource\RelationManagers\HasManyRelationManager;
use App\Filament\Resources\ProjectResource\RelationManagers\ProjectDocumentsRelationManager;
use App\Models\Project;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\BelongsToSelect;
use Filament\Forms\Components\Radio;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;

class ProjectResource extends Resource
{
    protected static ?string $model = Project::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-office-2';
    protected static ?string $navigationLabel = 'المشاريع';
    protected static ?string $navigationGroup = 'إدارة المشاريع';
    protected static ?int $navigationSort = 3;

    public static function getModelLabel(): string
    {
        return 'مشروع';
    }

    public static function getPluralModelLabel(): string
    {
        return 'المشاريع';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('بيانات المشروع')
                    ->schema([
                        BelongsToSelect::make('client_id')
                            ->label('العميل')
                            ->relationship('client', 'name')
                            ->preload()                    
                            ->searchable()
                            ->required(),

                        TextInput::make('address')
                            ->label('العنوان')
                            ->required()
                            ->maxLength(255),

                        TextInput::make('project_number')
                            ->label('رقم المشروع / القسيمة')
                            ->required()
                            ->maxLength(255),

                        TextInput::make('contract_type')
                            ->label('نوع العقد')
                            ->required()
                            ->maxLength(255),

                        TextInput::make('contract_value')
                            ->label('قيمة العقد')
                            ->numeric()
                            ->required(),

                        Radio::make('status')
                            ->label('حالة المشروع')
                            ->options([
                                'active' => 'جاري العمل',
                                'finished' => 'منتهي',
                                'cancelled' => 'ملغي',
                            ])
                            ->inline()
                            ->required(),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('contract_number')
                    ->label('رقم العقد')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('client.name')
                    ->label('العميل')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('project_number')
                    ->label('رقم المشروع / القسيمة')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('contract_type')
                    ->label('نوع العقد')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('contract_value')
                    ->label('قيمة العقد')
                    ->sortable()
                    ->money('KWD', true),

                BadgeColumn::make('status')
                    ->label('الحالة')
                    ->colors([
                        'warning' => fn ($state): bool => $state === 'active',
                        'success' => fn ($state): bool => $state === 'finished',
                        'danger' => fn ($state): bool => $state === 'cancelled',
                    ])
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'active' => 'جاري العمل',
                        'finished' => 'منتهي',
                        'cancelled' => 'ملغي',
                        default => 'غير محدد',
                    })
                    ->sortable(),
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
            ProjectPaymentsRelationManager::class,
            HasManyRelationManager::class,
            ProjectDocumentsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProjects::route('/'),
            'create' => Pages\CreateProject::route('/create'),
            'edit' => Pages\EditProject::route('/{record}/edit'),
        ];
    }
}
