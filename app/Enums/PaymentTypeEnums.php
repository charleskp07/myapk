<?php

namespace App\Enums;

enum PaymentTypeEnums: string
{
    case ESPECES = 'Espèces';
    case DEPOT = 'Dépôt';
    case AUTRE = 'Autre';
}
