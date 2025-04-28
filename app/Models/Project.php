<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $fillable = [
        'name',
        'description',
        'client_id',
        'status',
        'budget',
        'start_date',
        'end_date',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];
    

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public function successTasks()
    {
        return $this->tasks()->where('status', 'success')->count();
    }

    public function totalTasks()
    {
        return $this->tasks()->count();
    }

    public function estimatedEndDate()
    {
        $latestTask = $this->tasks()->orderByDesc('due_date')->first();
    
        return $latestTask?->due_date; // ini otomatis Carbon kalau kamu sudah set casts
    }
    

    public function projectStatus()
    {
        $estimated = $this->estimatedEndDate();
        $endDate = $this->end_date;

        if (! $endDate || ! $estimated) {
            return null; // Tidak cukup data
        }

        return $estimated > $endDate ? 'Terlambat' : 'Tepat Waktu';
    }

}
