<?php

namespace App\Filament\Admin\Pages;

use App\Enums\NavigationGroupEnum;
use App\Models\User;
use Carbon\Carbon;
use Filament\Pages\Page;
use Illuminate\Support\Facades\DB;

class WeeklyTimesheet extends Page
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-calendar';

    protected string $view = 'filament.admin.pages.weekly-timesheet';

    protected static ?string $navigationLabel = 'Timesheet Semanal';

    protected static ?string $title = 'Timesheet Semanal';
    protected static ?int $navigationSort = 5;

    public ?int $selectedUserId = null;
    public ?string $weekStart = null;
    public array $timeEntries = [];
    public array $projects = [];
    public array $weekDays = [];
    public array $users = [];

    public array $tempRows = [];

    public array $editableHours = [];

    public array $pendingChanges = [];

    public ?int $selectedProjectToAdd = null;

    public function mount(): void
    {
        $this->weekStart = now()->startOfWeek()->format('Y-m-d');
        $this->users = User::query()->pluck('name', 'id')->toArray();
    }

    public function updatedSelectedUserId(): void
    {
        $this->tempRows = [];
        $this->editableHours = [];
        $this->loadData();
    }

    public function updatedSelectedProjectToAdd(): void
    {
        if ($this->selectedProjectToAdd) {
            $this->addProjectToTable();
        }
    }

    public function addProjectToTable(): void
    {
        if (!$this->selectedProjectToAdd) {
            return;
        }

        $project = DB::table('projects')
            ->where('id', $this->selectedProjectToAdd)
            ->select('id', 'name', 'code')
            ->first();

        if ($project) {
            $projectIds = array_map(fn($p) => $p->id, $this->projects);
            if (!in_array($project->id, $projectIds)) {
                $this->projects[] = $project;

                foreach ($this->weekDays as $day) {
                    $key = $project->id . '_' . $day['date'];
                    $this->timeEntries[$key] = [
                        'entries' => [],
                        'total_minutes' => 0,
                    ];
                }
            }
        }

        $this->selectedProjectToAdd = null;
    }

    public function addNewProjectRow(): void
    {
        $this->tempRows[] = [
            'id' => 'temp_' . uniqid(),
            'project_id' => null,
        ];
    }

    public function removeTempRow(string $tempId): void
    {
        $this->tempRows = array_filter($this->tempRows, fn($row) => $row['id'] !== $tempId);
        $this->tempRows = array_values($this->tempRows);
    }

    public function saveAllChanges(): void
    {
        $user = User::find($this->selectedUserId);
        $companyUser = $user?->companyUsers()->first();

        if (!$companyUser) {
            \Filament\Notifications\Notification::make()
                ->title('Erro')
                ->body('Usuário não possui vínculo com empresa.')
                ->danger()
                ->send();
            return;
        }

        $saved = 0;
        $errors = 0;

        foreach ($this->editableHours as $key => $hours) {
            [$projectId, $date] = explode('_', $key);

            try {
                if ($hours === null || $hours === '' || $hours <= 0) {
                    DB::table('time_entries')
                        ->join('company_user', 'time_entries.created_by_company_user_id', '=', 'company_user.id')
                        ->where('company_user.user_id', $this->selectedUserId)
                        ->where('time_entries.project_id', $projectId)
                        ->where('time_entries.date', $date)
                        ->delete();
                } else {
                    $existing = DB::table('time_entries')
                        ->join('company_user', 'time_entries.created_by_company_user_id', '=', 'company_user.id')
                        ->where('company_user.user_id', $this->selectedUserId)
                        ->where('time_entries.project_id', $projectId)
                        ->where('time_entries.date', $date)
                        ->select('time_entries.id')
                        ->first();

                    if ($existing) {
                        DB::table('time_entries')
                            ->where('id', $existing->id)
                            ->update([
                                'duration_minutes' => $hours * 60,
                                'updated_at' => now(),
                            ]);
                    } else {
                        DB::table('time_entries')->insert([
                            'project_id' => $projectId,
                            'date' => $date,
                            'duration_minutes' => $hours * 60,
                            'created_by_company_user_id' => $companyUser->id,
                            'origin' => 0, // Manual
                            'status' => 0, // Pending
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }
                }
                $saved++;
            } catch (\Exception $e) {
                $errors++;
            }
        }

        $this->tempRows = [];

        \Filament\Notifications\Notification::make()
            ->title('Sucesso')
            ->body("$saved lançamento(s) salvo(s) com sucesso!")
            ->success()
            ->send();

        $this->loadData();
    }


    public function loadData(): void
    {
        if (!$this->selectedUserId || !$this->weekStart) {
            $this->timeEntries = [];
            $this->projects = [];
            $this->weekDays = [];
            return;
        }

        $weekStart = Carbon::parse($this->weekStart);
        $weekEnd = $weekStart->copy()->endOfWeek();

        $this->weekDays = [];
        for ($i = 0; $i < 7; $i++) {
            $day = $weekStart->copy()->addDays($i);
            $this->weekDays[] = [
                'date' => $day->format('Y-m-d'),
                'label' => $day->locale('pt_BR')->isoFormat('ddd'),
                'day_number' => $day->format('d'),
            ];
        }

        $user = User::find($this->selectedUserId);
        if (!$user) {
            return;
        }

        $companyUserIds = $user->companyUsers()->pluck('id');

        $entries = DB::table('time_entries')
            ->join('company_user', 'time_entries.created_by_company_user_id', '=', 'company_user.id')
            ->where('company_user.user_id', $this->selectedUserId)
            ->whereBetween('time_entries.date', [$weekStart->format('Y-m-d'), $weekEnd->format('Y-m-d')])
            ->select(
                'time_entries.id',
                'time_entries.project_id',
                'time_entries.date',
                'time_entries.duration_minutes',
                'time_entries.status',
                'time_entries.notes'
            )
            ->get();

        $projectIdsWithEntries = $entries->pluck('project_id')->unique();

        $this->projects = DB::table('projects')
            ->whereIn('id', $projectIdsWithEntries)
            ->select('id', 'name', 'code')
            ->get()
            ->toArray();

        $this->timeEntries = [];
        $this->editableHours = [];
        foreach ($this->projects as $project) {
            foreach ($this->weekDays as $day) {
                $key = $project->id . '_' . $day['date'];
                $dayEntries = $entries->where('project_id', $project->id)
                    ->where('date', $day['date'])
                    ->values();

                $totalMinutes = $dayEntries->sum('duration_minutes');

                $this->timeEntries[$key] = [
                    'entries' => $dayEntries->toArray(),
                    'total_minutes' => $totalMinutes,
                ];

                // Popular editableHours com valores atuais
                if ($totalMinutes > 0) {
                    $this->editableHours[$key] = $totalMinutes / 60;
                }
            }
        }
    }

    public function getAvailableProjectsProperty()
    {
        if (!$this->selectedUserId) {
            return [];
        }

        $user = User::find($this->selectedUserId);
        if (!$user) {
            return [];
        }

        $companyUserIds = $user->companyUsers()->pluck('id');

        $projectIdsInTable = array_map(fn($p) => $p->id, $this->projects);

        return DB::table('assignments')
            ->join('projects', 'assignments.project_id', '=', 'projects.id')
            ->whereIn('assignments.company_user_id', $companyUserIds)
            ->whereNotIn('projects.id', $projectIdsInTable) // Excluir projetos já adicionados
            ->select('projects.id', 'projects.name', 'projects.code')
            ->distinct()
            ->get()
            ->mapWithKeys(fn($p) => [$p->id => "{$p->code} - {$p->name}"])
            ->toArray();
    }

    public function previousWeek(): void
    {
        $this->weekStart = Carbon::parse($this->weekStart)->subWeek()->format('Y-m-d');
        $this->saveAllChanges();
        $this->loadData();
    }

    public function nextWeek(): void
    {
        $this->weekStart = Carbon::parse($this->weekStart)->addWeek()->format('Y-m-d');
        $this->saveAllChanges();
        $this->loadData();
    }

    public function currentWeek(): void
    {
        $this->weekStart = now()->startOfWeek()->format('Y-m-d');
        $this->saveAllChanges();
        $this->loadData();
    }
}

