<?php

namespace App\Filament\Resources\ProjectResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use Illuminate\Support\Facades\Storage;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\Action;

class ProjectDocumentsRelationManager extends RelationManager
{
    protected static string $relationship = 'projectDocuments';

    protected static ?string $title = 'المستندات';

    protected static ?string $label = 'مستند';
    protected static ?string $pluralLabel = 'المستندات';

    protected static bool $canCreate = true;

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('title')
                    ->label('عنوان المستند')
                    ->required(),

                FileUpload::make('file_path')
                    ->label('الملف')
                    ->directory('project-documents')
                    ->required()
                    ->preserveFilenames()
                    ->downloadable()
                    ->openable()
                    ->previewable(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->label('العنوان')
                    ->searchable(),

                TextColumn::make('file_path')
                    ->label('اسم الملف')
                    ->formatStateUsing(fn ($state) => basename($state)),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
                Action::make('download')
                    ->label('تحميل')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->url(fn ($record) => Storage::url($record->file_path))
                    ->openUrlInNewTab(),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('إضافة مستند')
                    ->modalHeading('إضافة مستند جديد'),
            ]);
    }
}
