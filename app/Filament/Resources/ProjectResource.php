<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Project;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use App\Tables\Columns\ProgressBar;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\Layout\Panel;
use Filament\Tables\Columns\Layout\Split;
use Filament\Tables\Columns\Layout\Stack;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\ProjectResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\ProjectResource\RelationManagers;

class ProjectResource extends Resource
{
    protected static ?string $model = Project::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Project Management';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Stack::make([
                    TextColumn::make('name')
                        ->weight('bold')
                        ->size('lg')
                        ->icon('heroicon-o-rectangle-stack')
                        ->iconPosition('before')
                        ->sortable()
                        ->searchable(),
                
                    TextColumn::make('client.name')
                        ->label('Client')
                        ->color('gray')
                        ->size('sm')
                        ->searchable()
                        ->sortable(),
                
                    Split::make([
                        TextColumn::make('start_date')
                            ->state(fn ($record) => 'Mulai: ' . ($record->start_date ? $record->start_date->format('d M Y') : '-'))
                            ->size('xs')
                            ->color('primary'),

                        TextColumn::make('end_date')
                            ->state(fn ($record) => 'Target: ' . ($record->end_date ? $record->end_date->format('d M Y') : '-'))
                            ->size('xs')
                            ->color('success'),

                            TextColumn::make('estimated_end_date')
                                ->state(fn ($record) => 'Estimasi: ' . ($record->estimatedEndDate() ? $record->estimatedEndDate()->format('d M Y') : '-'))
                                ->size('xs')
                                ->color(function ($record) {
                                    // Pastikan kedua tanggal (estimasi dan target) ada
                                    if ($record->estimatedEndDate() && $record->end_date) {
                                        // Jika estimasi > target, warnai merah
                                        return $record->estimatedEndDate()->gt($record->end_date) ? 'danger' : 'success';
                                    }
                                    return 'gray'; // Default jika data tidak lengkap
                                }),
                    ]),
                
                    ProgressBar::make('progress')
                        ->percentage(fn ($record) => $record->totalTasks() > 0
                            ? round(($record->successTasks() / $record->totalTasks()) * 100)
                            : 0),
                
                    TextColumn::make('status')
                        ->label('Status')
                        ->state(fn ($record) => $record->projectStatus() ?? 'Belum Ditentukan')
                        ->badge()
                        ->color(fn ($state) => match ($state) {
                            'Terlambat' => 'danger',
                            'Tepat Waktu' => 'success',
                            default => 'gray',
                        })
                        ->size('sm'),
                ]),
                
            ])
            ->heading('Daftar Project')
            ->contentGrid([
                'md' => 2,
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            //
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