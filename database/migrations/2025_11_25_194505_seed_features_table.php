<?php

use App\Enums\FeatureTypeEnum;
use App\Models\Feature;
use Illuminate\Database\Migrations\Migration;


return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $booleanFeatures = [
            [
                'name'          => 'Projetos',
                'key'           => 'bool.projects',
            ],
            [
                'name'          => 'Tarefas',
                'key'           => 'bool.tasks',
            ],
            [
                'name'          => 'Relatórios',
                'key'           => 'bool.reports',
            ],
            [
                'name'          => 'Empresas',
                'key'           => 'bool.companies',
            ],
            [
                'name'          => 'Webhooks',
                'key'           => 'bool.webhooks',
            ],
            [
                'name'          => 'Calendários',
                'key'           => 'bool.calendars',
            ],
            [
                'name'          => 'Times',
                'key'           => 'bool.teams',
            ],
            [
                'name'          => 'PTOs',
                'key'           => 'bool.ptos',
            ]
        ];

        $tierFeatures = [
            [
                'name'          => 'Gerenciador de Projetos',
                'key'           => 'tier.project',
            ],
            [
                'name'          => 'Gerenciador de Tarefas',
                'key'           => 'tier.task',
            ],
            [
                'name'          => 'Gerenciador de Relatórios',
                'key'           => 'tier.reports',
            ],
        ];

        $limitFeatures = [
            [
                'name'          => 'Número de Usuários',
                'key'           => 'limit.users',
            ],
            [
                'name'          => 'Número de Empresas',
                'key'           => 'limit.companies',
            ],
            [
                'name'          => 'Número de Projetos',
                'key'           => 'limit.projects',
            ],
            [
                'name'          => 'Número de Tarefas',
                'key'           => 'limit.tasks',
            ],
            [
                'name'          => 'Número de Webhooks',
                'key'           => 'limit.webhooks',
            ],
            [
                'name'          => 'Número de Times',
                'key'           => 'limit.teams',
            ],
            [
                'name'          => 'Número de Associados por Projeto',
                'key'           => 'limit.assignees_per_project',
            ],
            [
                'name'          => 'Número de Membros por Time',
                'key'           => 'limit.members_per_team',
            ],
            [
                'name'          => 'Número de Relatórios Avançados',
                'key'           => 'limit.advanced_reports',
            ],
        ];

        foreach ($booleanFeatures as $feature) {
            Feature::query()->firstOrCreate([
                'key'   => $feature['key'],
            ], [
                'name'  => $feature['name'],
                'type'  => FeatureTypeEnum::BOOLEAN,
            ]);
        }

        foreach ($tierFeatures as $feature) {
            Feature::query()->firstOrCreate([
                'key'   => $feature['key'],
            ], [
                'name'  => $feature['name'],
                'type'  => FeatureTypeEnum::TIER,
            ]);
        }

        foreach ($limitFeatures as $feature) {
            Feature::query()->firstOrCreate([
                'key'   => $feature['key'],
            ], [
                'name'  => $feature['name'],
                'type'  => FeatureTypeEnum::LIMIT,
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // This migration is not reversible.
    }
};
