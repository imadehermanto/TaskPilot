<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Carbon\Carbon;
use App\Models\Project;
use App\Models\Task;

class ProjectTaskSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create('id_ID');

        $projectNames = [
            'Pengembangan Fitur Baru',
            'Optimasi Sistem Backend',
            'Migrasi Basis Data',
            'Pembuatan Modul Laporan',
            'Perbaikan Bug Kritikal',
            'Upgrade Infrastruktur',
            'Implementasi API Eksternal',
            'Redesign Antarmuka Admin'
        ];

        $taskTypes = [
            'Analisis Kebutuhan',
            'Desain Sistem',
            'Pengembangan Inti',
            'Penulisan Test Case',
            'Testing Integrasi',
            'Persiapan Deployment',
            'Dokumentasi Teknis',
            'Training Tim'
        ];

        $currentDate = Carbon::now();
        $projectStartDate = $currentDate->copy()->subMonths(3);

        foreach ($projectNames as $index => $projectName) {
            // Generate durasi project 2-6 minggu
            $projectDuration = $faker->numberBetween(14, 42); // dalam hari
            $startDate = $projectStartDate->copy()->addWeeks($index * 1.5);
            
            // Pastikan start date < end date
            $endDate = $startDate->copy()->addDays($projectDuration);

            $project = Project::create([
                'name' => $projectName,
                'description' => $faker->paragraph(3),
                'client_id' => 1,
                'status' => $this->determineProjectStatus($startDate, $endDate, $faker),
                'start_date' => $startDate,
                'end_date' => $endDate,
            ]);

            // Generate 5-8 task per project
            $taskCount = $faker->numberBetween(5, 8);
            $taskStart = $startDate->copy();
            
            for ($j = 1; $j <= $taskCount; $j++) {
                // Durasi task 2-7 hari
                $taskDuration = $faker->numberBetween(2, 7);
                $taskDue = $taskStart->copy()->addDays($taskDuration);
                
                // Jika melebihi end date project
                if ($taskDue > $endDate) {
                    $taskDue = $endDate->copy();
                    $taskStart = $endDate->copy()->subDays($taskDuration);
                }

                $status = $this->determineTaskStatus($taskDue, $faker);
                
                Task::create([
                    'name' => $taskTypes[array_rand($taskTypes)] . ' - ' . $faker->words(2, true),
                    'description' => $faker->sentence(6),
                    'project_id' => $project->id,
                    'status' => $status,
                    'start_date' => $taskStart,
                    'due_date' => $taskDue,
                    'completed_at' => $status === 'success' ? $faker->dateTimeBetween($taskStart, $taskDue) : null,
                    'priority' => $this->getTaskPriority($j),
                    'assigned_to' => 1,
                ]);

                // Update task start berikutnya
                $taskStart = $taskDue->copy()->addDays($faker->numberBetween(1, 2));
                
                if ($taskStart > $endDate) {
                    break;
                }
            }
        }
    }

    private function determineProjectStatus($start, $end, $faker): string
    {
        $now = Carbon::now();
        
        if ($now > $end) {
            return 'completed';
        }
        
        if ($now->between($start, $end)) {
            return $faker->randomElement(['active', 'on_hold']);
        }
        
        return 'active'; // Default untuk project yang belum mulai
    }

    private function determineTaskStatus($dueDate, $faker): string
    {
        $now = Carbon::now();
        // Removed unused $statuses variable
        
        if ($now > $dueDate) {
            return $faker->randomElement(['success', 'revision', 'canceled']);
        }
        
        return $faker->randomElement(['pending', 'in_progress']);
    }

    private function getTaskPriority($taskNumber): string
    {
        $priorities = [
            1 => 'high',
            2 => 'high',
            3 => 'medium',
            4 => 'medium',
            5 => 'low',
            6 => 'low',
            7 => 'low',
            8 => 'low'
        ];
        
        return $priorities[$taskNumber] ?? 'medium';
    }
}