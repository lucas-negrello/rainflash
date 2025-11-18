<x-filament-panels::page>
    <div class="space-y-6">
        <div class="fi-section rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
            <div class="fi-section-content-ctn">
                <div class="fi-section-content p-6">
                    <div class="flex items-center gap-x-3">
                        <div class="flex-1">
                            <h2 class="text-xl font-semibold text-gray-950 dark:text-white">
                                Painel Administrativo
                            </h2>
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                Bem-vindo ao painel de administração do sistema. Aqui você pode gerenciar empresas, usuários e configurações globais.
                            </p>
                        </div>
                        <div class="fi-avatar flex h-12 w-12 items-center justify-center rounded-full bg-primary-500 text-primary-50 dark:bg-primary-400">
                            <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <x-filament-widgets::widgets
            :widgets="$this->getHeaderWidgets()"
            :columns="$this->getColumns()"
        />
    </div>
</x-filament-panels::page>

