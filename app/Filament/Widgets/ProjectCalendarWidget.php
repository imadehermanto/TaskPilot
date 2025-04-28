<?php

namespace App\Filament\Widgets;

use Saade\FilamentFullCalendar\Widgets\FullCalendarWidget;
use App\Models\Project;
use Saade\FilamentFullCalendar\Data\EventData;
use Illuminate\Database\Eloquent\Model;

class ProjectCalendarWidget extends FullCalendarWidget
{
    public Model|string|null $model = Project::class;

    public function fetchEvents(array $fetchInfo): array
    {
        return Project::with('client')
            ->where('start_date', '<=', $fetchInfo['end'])
            ->where(function($query) use ($fetchInfo) {
                $query->where('end_date', '>=', $fetchInfo['start'])
                        ->orWhereHas('tasks', function($q) use ($fetchInfo) {
                            $q->where('due_date', '>=', $fetchInfo['start']);
                        });
            })
            ->get()
            ->map(function (Project $project) {
                $estimatedEnd = $project->estimatedEndDate();
                
                // Event untuk tanggal rencana project
                $events = [
                    EventData::make()
                        ->id($project->id.'-planned')
                        ->title($project->name.' (Rencana)')
                        ->start($project->start_date)
                        ->end($project->end_date)
                        ->extendedProps([
                            'color' => '#3b82f6',
                            'textColor' => '#ffffff',
                            'borderColor' => '#1d4ed8'
                        ])
                ];
                
                // Event untuk estimasi berdasarkan task
                // if ($estimatedEnd) {
                //     $isLate = $estimatedEnd > $project->end_date;
                //     $events[] = EventData::make()
                //         ->id($project->id.'-estimated')
                //         ->title($project->name.' (Estimasi)')
                //         ->start($project->start_date)
                //         ->end($estimatedEnd)
                //         ->extendedProps([
                //             'color' => $isLate ? '#ef4444' : '#10b981',
                //             'textColor' => '#ffffff',
                //             'borderColor' => $isLate ? '#dc2626' : '#059669',
                //             'isLate' => $isLate
                //         ]);
                // }
                
                return $events;
            })
            ->flatten()
            ->toArray();
    }
    public function eventDidMount(): string
    {
        return <<<JS
            function({ event, el }) {
                // Tambahkan tooltip
                el.setAttribute("x-tooltip", "tooltip");
                el.setAttribute("x-data", "{ tooltip: event.title }");
                
                // Jika terlambat, tambahkan border putus-putus
                if (event.extendedProps.isLate) {
                    el.style.borderStyle = 'dashed';
                }
            }
        JS;
    }
}