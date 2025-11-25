<x-filament-panels::page>

    <x-filament::section>
        <x-slot name="heading">
            Selecione o Usuário
        </x-slot>

        <div class="w-full">
            <label class="fi-fo-field-wrp-label inline-flex items-center gap-x-3" for="user-select">
                <span class="text-sm font-medium leading-6 text-gray-950 dark:text-white">
                    Usuário
                </span>
            </label>
            <select
                id="user-select"
                wire:model.live="selectedUserId"
                class="fi-select-input block w-full rounded-lg border-none py-1.5 pe-8 ps-3 text-base text-gray-950 transition duration-75 focus:ring-2 disabled:text-gray-500 disabled:[-webkit-text-fill-color:theme(colors.gray.500)] dark:text-white dark:disabled:text-gray-400 dark:disabled:[-webkit-text-fill-color:theme(colors.gray.400)] sm:text-sm sm:leading-6 bg-white shadow-sm ring-1 ring-gray-950/10 hover:bg-gray-50 focus:ring-primary-600 dark:bg-white/5 dark:ring-white/20 dark:hover:bg-white/10 dark:focus:ring-primary-500"
            >
                <option value="">Selecione um usuário</option>
                @foreach($users as $id => $name)
                    <option value="{{ $id }}">{{ $name }}</option>
                @endforeach
            </select>
        </div>
    </x-filament::section>

    @if($selectedUserId && $weekStart)

        <x-filament::section>
            <x-slot name="heading">
                <div class="flex flex-wrap md:flex-nowrap items-center justify-between gap-4 w-full">
                    <div class="flex flex-col flex-wrap items-start justify-between gap-4 w-full md:w-auto">
                        <span>Timesheet</span>
                        <div class="flex flex-wrap md:flex-nowrap items-center justify-between gap-4 w-full md:w-auto">
                            <x-filament::button
                                class="w-full"
                                wire:click="saveAllChanges"
                                color="success"
                                icon="heroicon-o-check"
                                size="sm"
                            >
                                Salvar
                            </x-filament::button>

                            @if(count($this->availableProjects) > 0)
                                <select
                                    wire:model.live="selectedProjectToAdd"
                                    wire:change="addProjectToTable"
                                    class="fi-select-input rounded-lg border-none py-1.5 pe-8 ps-3 text-sm text-gray-950 transition duration-75 focus:ring-2 bg-white shadow-sm ring-1 ring-gray-950/10 hover:bg-gray-50 focus:ring-primary-600 dark:bg-white/5 dark:ring-white/20 dark:hover:bg-white/10 dark:focus:ring-primary-500 dark:text-white"
                                >
                                    <option value="">+ Adicionar Projeto</option>
                                    @foreach($this->availableProjects as $projectId => $projectName)
                                        <option value="{{ $projectId }}">{{ $projectName }}</option>
                                    @endforeach
                                </select>
                            @endif
                        </div>
                    </div>

                    <div class="flex md:flex-nowrap flex-wrap items-center justify-center gap-6 text-center order-last md:order-none w-full">

                        <x-filament::button
                            class="w-full md:w-auto"
                            wire:click="previousWeek"
                            color="gray"
                            icon="heroicon-o-chevron-left"
                            size="sm"
                        />

                        <span class="text-base font-semibold text-gray-950 dark:text-white px-6">
                            {{ \Carbon\Carbon::parse($weekStart)->locale('pt_BR')->isoFormat('DD/MM') }} -
                            {{ \Carbon\Carbon::parse($weekStart)->endOfWeek()->locale('pt_BR')->isoFormat('DD/MM/YYYY') }}
                        </span>

                        <x-filament::button
                            class="w-full md:w-auto"
                            wire:click="nextWeek"
                            color="gray"
                            icon="heroicon-o-chevron-right"
                            size="sm"
                        />
                    </div>

                    <div class="flex flex-wrap md:flex-nowrap items-center justify-between gap-4 w-full md:w-auto">
                        <x-filament::button
                            class="w-full"
                            wire:click="currentWeek"
                            outlined
                            size="sm"
                        >
                            Semana Atual
                        </x-filament::button>
                    </div>
                </div>
            </x-slot>

            <div class="fi-ta overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
                <div class="overflow-x-auto">
                    <table class="fi-ta-table w-full table-auto divide-y divide-gray-200 text-start dark:divide-white/5">
                        <thead class="divide-y divide-gray-200 dark:divide-white/5">
                            <tr class="bg-gray-50 dark:bg-white/5">
                                <th class="fi-ta-header-cell px-3 py-3.5 sm:first-of-type:ps-6 sm:last-of-type:pe-6 min-w-[200px]">
                                    <span class="group flex w-full items-center gap-x-1 whitespace-nowrap justify-start">
                                        <span class="fi-ta-header-cell-label text-sm font-semibold text-gray-950 dark:text-white">
                                            Projeto
                                        </span>
                                    </span>
                                </th>
                                @foreach($weekDays as $day)
                                    <th class="fi-ta-header-cell px-3 py-3.5 min-w-[100px]">
                                        <div class="flex flex-col items-center">
                                            <span class="text-xs font-medium text-gray-700 dark:text-gray-300 uppercase">
                                                {{ $day['label'] }}
                                            </span>
                                            <span class="text-lg font-bold text-gray-950 dark:text-white">
                                                {{ $day['day_number'] }}
                                            </span>
                                        </div>
                                    </th>
                                @endforeach
                                <th class="fi-ta-header-cell px-3 py-3.5 min-w-[80px]">
                                    <span class="group flex w-full items-center gap-x-1 whitespace-nowrap justify-center">
                                        <span class="fi-ta-header-cell-label text-sm font-semibold text-gray-950 dark:text-white">
                                            Total
                                        </span>
                                    </span>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 whitespace-nowrap dark:divide-white/5">
                            @forelse($projects as $project)
                                <tr class="fi-ta-row  transition duration-75 hover:bg-gray-50 dark:hover:bg-white/5" wire:key="project-row-{{ $project->id }}">
                                    <td class="fi-ta-cell p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                                        <div class="fi-ta-col-wrp px-3" style="padding-top:1.75rem;padding-bottom:1.75rem;">
                                            <span class="fi-ta-text-item-label text-sm leading-6 text-gray-950 dark:text-white font-medium">
                                                {{ $project->name }}
                                            </span>
                                        </div>
                                    </td>
                                    @php
                                        $weekTotal = 0;
                                    @endphp
                                    @foreach($weekDays as $day)
                                        @php
                                            $key = $project->id . '_' . $day['date'];
                                            $cellData = $timeEntries[$key] ?? ['entries' => [], 'total_minutes' => 0];
                                            $weekTotal += $cellData['total_minutes'];
                                        @endphp
                                        <td class="fi-ta-cell p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                                            <div class="fi-ta-col-wrp px-3" style="padding-top:1.75rem;padding-bottom:1.75rem;">
                                                @php
                                                    $currentHours = 0;
                                                    $statusColor = 'gray';
                                                    if(count($cellData['entries']) > 0) {
                                                        $currentHours = $cellData['total_minutes'] / 60;
                                                        $statusColor = match($cellData['entries'][0]->status) {
                                                            0 => 'warning',
                                                            1 => 'success',
                                                            2 => 'danger',
                                                            default => 'gray',
                                                        };
                                                    }
                                                    $inputKey = $project->id . '_' . $day['date'];
                                                @endphp
                                                <div class="flex items-center justify-center">
                                                    <input
                                                        wire:key="hours-input-{{ $project->id }}-{{ $day['date'] }}"
                                                        type="number"
                                                        step="0.5"
                                                        min="0"
                                                        max="24"
                                                        wire:model.defer="editableHours.{{ $inputKey }}"
                                                        placeholder="0"
                                                        class="w-20 text-center rounded-lg border-0 py-2 px-3 text-sm font-medium shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-blue-600 dark:bg-gray-800 dark:text-white dark:ring-gray-700 dark:focus:ring-blue-500"
                                                        @style([
                                                            'background-color: rgb(254 243 199); color: rgb(120 53 15);' => $statusColor === 'warning',
                                                            'background-color: rgb(220 252 231); color: rgb(22 101 52);' => $statusColor === 'success',
                                                            'background-color: rgb(254 226 226); color: rgb(153 27 27);' => $statusColor === 'danger',
                                                        ])
                                                    />
                                                </div>
                                            </div>
                                        </td>
                                    @endforeach
                                    <td class="fi-ta-cell p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                                        <div class="fi-ta-col-wrp px-3" style="padding-top:1.75rem;padding-bottom:1.75rem;">
                                            <div class="text-center">
                                                <span class="text-sm font-semibold text-gray-950 dark:text-white">
                                                    {{ number_format($weekTotal / 60, 1) }}h
                                                </span>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="{{ count($weekDays) + 2 }}" class="fi-ta-cell p-0">
                                        <div class="fi-ta-empty-state px-6 py-12">
                                            <div class="fi-ta-empty-state-content mx-auto grid max-w-lg justify-items-center text-center">
                                                <div class="fi-ta-empty-state-icon-ctn mb-4 rounded-full bg-gray-100 p-3 dark:bg-gray-500/20">
                                                    <x-filament::icon
                                                        icon="heroicon-o-inbox"
                                                        class="fi-ta-empty-state-icon h-6 w-6 text-gray-500 dark:text-gray-400"
                                                    />
                                                </div>
                                                <h4 class="fi-ta-empty-state-heading text-base font-semibold leading-6 text-gray-950 dark:text-white">
                                                    Nenhum projeto com horas
                                                </h4>
                                                <p class="fi-ta-empty-state-description text-sm text-gray-500 dark:text-gray-400">
                                                    Use o select "Adicionar Projeto" para começar a apontar horas.
                                                </p>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                        @if(count($projects) > 0)
                            <tfoot class="divide-y divide-gray-200 dark:divide-white/5">
                                <tr class="bg-gray-50 dark:bg-white/5">
                                    <td class="fi-ta-cell p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                                        <div class="fi-ta-col-wrp px-3 py-4">
                                            <span class="text-sm font-semibold text-gray-950 dark:text-white">
                                                Total Diário
                                            </span>
                                        </div>
                                    </td>
                                    @foreach($weekDays as $day)
                                        @php
                                            $dayTotal = 0;
                                            foreach($projects as $project) {
                                                $key = $project->id . '_' . $day['date'];
                                                $dayTotal += ($timeEntries[$key] ?? ['total_minutes' => 0])['total_minutes'];
                                            }
                                        @endphp
                                        <td class="fi-ta-cell p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                                            <div class="fi-ta-col-wrp px-3 py-4">
                                                <div class="text-center">
                                                    <span class="text-sm font-semibold text-gray-950 dark:text-white">
                                                        {{ number_format($dayTotal / 60, 1) }}h
                                                    </span>
                                                </div>
                                            </div>
                                        </td>
                                    @endforeach
                                    <td class="fi-ta-cell p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                                        <div class="fi-ta-col-wrp px-3 py-4">
                                            <div class="text-center">
                                                @php
                                                    $grandTotal = 0;
                                                    foreach($projects as $project) {
                                                        foreach($weekDays as $day) {
                                                            $key = $project->id . '_' . $day['date'];
                                                            $grandTotal += ($timeEntries[$key] ?? ['total_minutes' => 0])['total_minutes'];
                                                        }
                                                    }
                                                @endphp
                                                <span class="text-sm font-bold text-primary-600 dark:text-primary-400">
                                                    {{ number_format($grandTotal / 60, 1) }}h
                                                </span>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </tfoot>
                        @endif
                    </table>
                </div>
            </div>
        </x-filament::section>
    @else
        <x-filament::section>
            <div class="fi-ta-empty-state px-6 py-12">
                <div class="fi-ta-empty-state-content mx-auto grid max-w-lg justify-items-center text-center">
                    <div class="fi-ta-empty-state-icon-ctn mb-4 rounded-full bg-gray-100 p-3 dark:bg-gray-500/20">
                        <x-filament::icon
                            icon="heroicon-o-calendar"
                            class="fi-ta-empty-state-icon h-6 w-6 text-gray-500 dark:text-gray-400"
                        />
                    </div>
                    <h4 class="fi-ta-empty-state-heading text-base font-semibold leading-6 text-gray-950 dark:text-white">
                        Selecione os filtros
                    </h4>
                    <p class="fi-ta-empty-state-description text-sm text-gray-500 dark:text-gray-400">
                        Escolha um usuário e uma semana para visualizar o timesheet.
                    </p>
                </div>
            </div>
        </x-filament::section>
    @endif
</x-filament-panels::page>
