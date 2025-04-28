<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Filament\Widgets\ProjectCalendarWidget;

class ProjectCalendar extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.project-calendar';

    protected function getHeaderWidgets(): array
    {
        return [
            ProjectCalendarWidget::class,
        ];
    }
}
