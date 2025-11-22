<?php

namespace App\Enums;

enum TimePreference: string
{
    case MATIN = 'matin';            // Avant 12h
    case APRES_MIDI = 'apres_midi'; // 15h-17h
    case SOIR = 'soir';             // Soirée
    case AVANT_PAUSE = 'avant_pause'; // 07h-09h45
    case FLEXIBLE = 'flexible'; 
}
