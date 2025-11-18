<x-filament-widgets::widget>
    <x-filament::section>
        <div class="flex items-center gap-x-4">
            <div class="flex h-16 w-16 items-center justify-center rounded-full bg-primary-500 text-primary-50 dark:bg-primary-400">
                <svg class="h-8 w-8" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.182 15.182a4.5 4.5 0 01-6.364 0M21 12a9 9 0 11-18 0 9 9 0 0118 0zM9.75 9.75c0 .414-.168.75-.375.75S9 10.164 9 9.75 9.168 9 9.375 9s.375.336.375.75zm-.375 0h.008v.015h-.008V9.75zm5.625 0c0 .414-.168.75-.375.75s-.375-.336-.375-.75.168-.75.375-.75.375.336.375.75zm-.375 0h.008v.015h-.008V9.75z" />
                </svg>
            </div>
            <div class="flex-1">
                <h2 class="text-2xl font-bold text-gray-950 dark:text-white">
                    Olá, {{ auth()->user()->name }}!
                </h2>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    Você está conectado à empresa: <span class="font-medium text-gray-950 dark:text-white">{{ auth()->user()->companies()->first()?->name ?? 'Nenhuma empresa' }}</span>
                </p>
                <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                    Bem-vindo ao seu painel de controle. Aqui você pode gerenciar suas atividades, projetos e muito mais.
                </p>
            </div>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>

