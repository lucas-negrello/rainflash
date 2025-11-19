<?php

namespace App\Enums;

use App\Contracts\TableEnumInterface;
use App\Traits\HasTableEnum;

enum NavigationGroupEnum: string implements TableEnumInterface
{
    use HasTableEnum;

    case ADMINISTRATION = 'Administração';
    case PROJECTS = 'Projetos';
    case FINANCE = 'Financeiro';
    case REPORTS = 'Relatórios';
    case SETTINGS = 'Configurações';

    public function label(): string
    {
        return $this->value; // value already localized
    }

    public function color(): string
    {
        return 'gray'; // Default color for all navigation groups
    }
}
