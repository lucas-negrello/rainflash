<?php

namespace App\Enums;

enum NavigationGroupEnum: string
{
    case ADMINISTRATION = 'Administração';
    case PROJECTS = 'Projetos';
    case FINANCE = 'Financeiro';
    case REPORTS = 'Relatórios';
    case SETTINGS = 'Configurações';
}

