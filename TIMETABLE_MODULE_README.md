# Module d'Emploi du Temps Automatique - Documentation

## 📋 Vue d'ensemble

Ce module permet de générer automatiquement un emploi du temps pour une classe donnée en respectant toutes les contraintes spécifiées.

## 🏗️ Architecture

### Fichiers principaux

1. **Service** : `app/Services/TimetableGeneratorService.php`
   - Algorithme de backtracking amélioré avec heuristiques
   - Gère toutes les contraintes et optimise le placement

2. **Repository** : `app/Repositories/TimetableGeneratorRepository.php`
   - Interface entre le contrôleur et le service

3. **Helper** : `app/Helpers/TimeSlot.php`
   - Gère les créneaux horaires et leurs validations

4. **Contrôleur** : `app/Http/Controllers/Admin/TimetableController.php`
   - Endpoints REST pour la génération et l'affichage

5. **Vue** : `resources/views/timetable/calendar.blade.php`
   - Interface utilisateur avec FullCalendar

## 🎯 Contraintes implémentées

### 1. Niveaux (Collège/Lycée)
- **Collège** : Maximum 1 heure consécutive par cours
- **Lycée** : Maximum 2 heures consécutives par cours

### 2. Plages horaires
- **Matin** : 07h00 → 09h45
- **Pause** : 09h45 → 10h00 (interdite)
- **Fin matinée** : 10h00 → 12h00
- **Après-midi** : 15h00 → 17h00
- **Soirée** : 17h00+ (optionnelle, sauf mercredi/vendredi)

### 3. Contraintes sur les matières
- `time_preference = 'morning'` → Avant 12h
- `time_preference = 'afternoon'` → Entre 15h et 17h
- `time_preference = 'evening'` → Après 17h
- `time_preference = 'no_after_break'` → Avant 09h45 uniquement

### 4. Contraintes enseignants
- Respect de la disponibilité (`availability` JSON)
- Pas de double cours en même temps
- Optimisation pour éviter les trous

### 5. Contraintes additionnelles
- Mercredi soir et vendredi soir interdits
- Pas de cours pendant la pause (09h45-10h00)
- Pas de conflit classe/enseignant

## 🚀 Utilisation

### Générer un emploi du temps

```php
// Via le contrôleur
POST /timetable/generate
{
    "classroom_id": 1
}
```

### Obtenir les événements (pour FullCalendar)

```php
GET /timetable/events?classroom_id=1
```

### Supprimer un emploi du temps

```php
DELETE /timetable/{classroom_id}
```

## 📊 Structure des données

### Assignation (déjà existante)
- `teacher_id` : ID de l'enseignant
- `classroom_id` : ID de la classe
- `subject_id` : ID de la matière
- `coefficient` : Coefficient de la matière
- `weekly_hours` : Nombre d'heures par semaine

### Schedule (créneaux générés)
- `assignation_id` : Référence à l'assignation
- `teacher_id` : ID de l'enseignant
- `classroom_id` : ID de la classe
- `subject_id` : ID de la matière
- `day_of_week` : Jour de la semaine (monday, tuesday, etc.)
- `start_time` : Heure de début (format H:i)
- `end_time` : Heure de fin (format H:i)
- `duration_minutes` : Durée en minutes
- `room` : Salle (optionnel)
- `is_active` : Statut actif

## 🔧 Configuration

### Disponibilité des enseignants

Format JSON dans la table `teachers` :

```json
{
    "monday": ["07:00-12:00", "15:00-17:00"],
    "tuesday": ["07:00-12:00", "15:00-17:00"],
    "wednesday": ["07:00-12:00"],
    "thursday": ["07:00-12:00", "15:00-17:00"],
    "friday": ["07:00-12:00"]
}
```

### Préférences horaires des matières

Dans la table `subjects`, le champ `time_preference` peut avoir les valeurs :
- `matin` : Avant 12h
- `apres_midi` : Entre 15h et 17h
- `soir` : Après 17h
- `avant_pause` : Avant 09h45 uniquement
- `flexible` : Aucune contrainte

## 🎨 Interface utilisateur

L'interface utilise FullCalendar avec :
- Vue hebdomadaire par défaut
- Vue journalière disponible
- Affichage des cours avec couleur par matière
- Modal de détails au clic sur un cours
- Génération en temps réel avec feedback

## ⚙️ Algorithme

### Backtracking amélioré

1. **Tri par priorité** : Les assignations avec contraintes strictes sont traitées en premier
2. **Heuristiques** : Les créneaux sont triés par priorité (meilleurs en premier)
3. **Backtracking** : Si un placement échoue, retour en arrière et essai d'une autre combinaison
4. **Optimisation** : Évite les trous et optimise la répartition

### Limites de sécurité

- Maximum 5000 tentatives pour éviter les boucles infinies
- Transaction database pour garantir la cohérence
- Gestion d'erreurs complète avec logs

## 🐛 Dépannage

### Erreur : "Impossible de générer un emploi du temps"

**Causes possibles** :
1. Contraintes trop strictes (vérifier les disponibilités enseignants)
2. Pas assez de créneaux disponibles
3. Conflits entre contraintes

**Solutions** :
- Vérifier les disponibilités des enseignants
- Assouplir les contraintes des matières
- Vérifier que les assignations sont correctes

### Erreur : Timeout

Si la génération prend trop de temps :
- Réduire le nombre d'assignations
- Simplifier les contraintes
- Augmenter le timeout dans le contrôleur

## 📝 Notes importantes

1. **Suppression automatique** : L'ancien emploi du temps est supprimé avant génération
2. **Transactions** : Toutes les opérations sont dans une transaction
3. **Logs** : Les erreurs sont loggées dans `storage/logs/laravel.log`
4. **Performance** : Pour de grandes quantités de données, considérer l'optimisation

## 🔄 Améliorations futures

- Export PDF de l'emploi du temps
- Génération par batch (plusieurs classes)
- Ajustement manuel des créneaux
- Statistiques de répartition
- Optimisation avancée avec algorithmes génétiques

