<x-filament-panels::page>
    <div class="space-y-6">
        <x-filament-widgets::widgets
            :widgets="$this->getWidgets()"
            :columns="$this->getColumns()"
        />

        <div class="grid gap-6 md:grid-cols-2">
            <div class="fi-section rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
                <div class="fi-section-header-ctn">
                    <div class="fi-section-header flex items-center gap-x-3 px-6 py-4">
                        <h3 class="text-base font-semibold text-gray-950 dark:text-white">
                            Ações Rápidas
                        </h3>
                    </div>
                </div>
                <div class="fi-section-content-ctn">
                    <div class="fi-section-content p-6 pt-0">
                        <div class="space-y-3">
                            <a href="#" class="flex items-center gap-3 rounded-lg border border-gray-300 p-3 hover:bg-gray-50 dark:border-gray-700 dark:hover:bg-gray-800">
                                <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-primary-500 text-white">
                                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div>
                                    <div class="font-medium text-gray-950 dark:text-white">Registrar Horas</div>
                                    <div class="text-sm text-gray-600 dark:text-gray-400">Apontar tempo de trabalho</div>
                                </div>
                            </a>

                            <a href="#" class="flex items-center gap-3 rounded-lg border border-gray-300 p-3 hover:bg-gray-50 dark:border-gray-700 dark:hover:bg-gray-800">
                                <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-success-500 text-white">
                                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z" />
                                    </svg>
                                </div>
                                <div>
                                    <div class="font-medium text-gray-950 dark:text-white">Ver Projetos</div>
                                    <div class="text-sm text-gray-600 dark:text-gray-400">Acessar projetos ativos</div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="fi-section rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
                <div class="fi-section-header-ctn">
                    <div class="fi-section-header flex items-center gap-x-3 px-6 py-4">
                        <h3 class="text-base font-semibold text-gray-950 dark:text-white">
                            Atividades Recentes
                        </h3>
                    </div>
                </div>
                <div class="fi-section-content-ctn">
                    <div class="fi-section-content p-6 pt-0">
                        <div class="space-y-3 text-sm">
                            <div class="flex items-start gap-3">
                                <div class="mt-0.5 h-2 w-2 rounded-full bg-primary-500"></div>
                                <div class="flex-1">
                                    <div class="text-gray-950 dark:text-white">Bem-vindo ao sistema!</div>
                                    <div class="text-gray-600 dark:text-gray-400">Comece explorando as funcionalidades</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-filament-panels::page>

