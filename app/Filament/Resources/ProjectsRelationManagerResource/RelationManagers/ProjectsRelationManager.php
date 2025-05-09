<?php

namespace App\Filament\Resources\ClientResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Resources\RelationManagers\RelationManager;

class ProjectsRelationManager extends RelationManager
{
    protected static string $relationship = 'projects';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('address')
                    ->label('العنوان')
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('project_number')
                    ->label('رقم المشروع / القسيمة')
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('contract_type')
                    ->label('نوع العقد')
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('contract_value')
                    ->label('قيمة العقد')
                    ->numeric()
                    ->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('address')
                    ->label('العنوان'),

                Tables\Columns\TextColumn::make('project_number')
                    ->label('رقم المشروع / القسيمة'),

                Tables\Columns\TextColumn::make('contract_type')
                    ->label('نوع العقد'),

                Tables\Columns\TextColumn::make('contract_value')
                    ->label('قيمة العقد'),
                Tables\Columns\BadgeColumn::make('status')
                    ->label('الحالة')
                    ->colors([
                        'warning' => static fn ($state): bool => $state === 'active',   // أصفر إذا كانت الحالة "جاري العمل"
                        'success' => static fn ($state): bool => $state === 'finished', // أخضر إذا كانت الحالة "منتهي"
                        'danger' => static fn ($state): bool => $state === 'cancelled', // أحمر إذا كانت الحالة "ملغي"
                    ])
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'active' => 'جاري العمل',
                        'finished' => 'منتهي',
                        'cancelled' => 'ملغي',
                        default => 'غير محدد',
                    })
                    ->sortable(),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
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
}
