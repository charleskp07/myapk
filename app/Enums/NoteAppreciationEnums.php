<?php

namespace App\Enums;

enum NoteAppreciationEnums: string
{
    case EXCELLENT = 'Excellent';
    case TRES_BIEN = 'Très bien';
    case BIEN = 'Bien';
    case ASSEZ_BIEN = 'Assez bien';
    case PASSABLE = 'Passable';
    case INSUFFISANT = 'Insuffisant';
    case MEDIOCRE = 'Mediocre';

}
