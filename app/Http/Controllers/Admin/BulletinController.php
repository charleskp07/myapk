<?php

namespace App\Http\Controllers\Admin;

use App\Enums\BreakdownNameEnums;
use App\Enums\ClassroomLevelEnums;
use App\Enums\EvaluationTypeEnums;
use App\Http\Controllers\Controller;
use App\Models\Breakdown;
use App\Models\Student;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class BulletinController extends Controller
{

    public function exportPDF(Request $request)
    {
        // Récupération de l'élève et du découpage
        $student = Student::with([
            'notes.evaluation.assignation.subject',
            'notes.evaluation.assignation.teacher',
            'notes.evaluation.bareme',
            'notes.appreciation',
        ])->findOrFail($request->student_id);

        $breakdown = Breakdown::findOrFail($request->breakdown_id);

        $classroom = $student->classroom;
        $students = $classroom->students;


        $classAverages = [];

        foreach ($students as $stud) {

            $notes = $stud->notes()
                ->whereHas('evaluation', fn($q) => $q->where('breakdown_id', $breakdown->id))
                ->with(['evaluation.assignation', 'evaluation.bareme'])
                ->get();

            $notesBySubject = $notes->groupBy(fn($note) => $note->evaluation->assignation->subject->id);
            $totalWeighted = 0;
            $totalCoeff = 0;

            foreach ($notesBySubject as $subjectNotes) {

                $coeff = $subjectNotes->first()->evaluation->assignation->coefficient ?? 1;

                $interro = $subjectNotes->firstWhere('evaluation.type', EvaluationTypeEnums::INTERROGATION->value);
                $devoir = $subjectNotes->firstWhere('evaluation.type', EvaluationTypeEnums::DEVOIR->value);
                $composition = $subjectNotes->firstWhere('evaluation.type', EvaluationTypeEnums::COMPOSITION->value);

                $noteInterro = $interro ? ($interro->value / $interro->evaluation->bareme->value) * 20 : null;
                $noteDevoir = $devoir ? ($devoir->value / $devoir->evaluation->bareme->value) * 20 : null;
                $noteComp = $composition ? ($composition->value / $composition->evaluation->bareme->value) * 20 : null;

                if ($noteInterro !== null && $noteDevoir !== null)
                    $noteClasse = ($noteInterro + $noteDevoir) / 2;
                elseif ($noteInterro !== null)
                    $noteClasse = $noteInterro;
                elseif ($noteDevoir !== null)
                    $noteClasse = $noteDevoir;
                else
                    $noteClasse = null;

                $noteFinale = $noteClasse !== null && $noteComp !== null
                    ? ($noteClasse + $noteComp) / 2
                    : ($noteClasse ?? $noteComp ?? 0);

                $totalWeighted += $noteFinale * $coeff;
                $totalCoeff += $coeff;
            }

            $avg = $totalCoeff > 0 ? round($totalWeighted / $totalCoeff, 2) : 0;
            $classAverages[$stud->id] = $avg;
        }

        // Tri décroissant des moyennes
        arsort($classAverages);

        // Rang de l’élève courant
        $rank = array_search($student->id, array_keys($classAverages)) + 1;

        // Moyenne max et min de la classe
        $maxAverage = max($classAverages);
        $minAverage = min($classAverages);


        $notes = $student->notes()
            ->whereHas('evaluation', fn($q) => $q->where('breakdown_id', $breakdown->id))
            ->with([
                'evaluation.assignation.subject',
                'evaluation.assignation.teacher',
                'evaluation.bareme',
                'appreciation'
            ])
            ->get();

        $notesBySubject = $notes->groupBy(fn($note) => $note->evaluation->assignation->subject->id);

        $results = [];
        $totalNote = 0;
        $noteDef = 0;
        $totalCoeff = 0;

        foreach ($notesBySubject as $subjectNotes) {

            $subjectName = $subjectNotes->first()->evaluation->assignation->subject->name;
            $teacher = $subjectNotes->first()->evaluation->assignation->teacher;
            $coefficient = $subjectNotes->first()->evaluation->assignation->coefficient ?? 1;

            $interro = $subjectNotes->firstWhere('evaluation.type', EvaluationTypeEnums::INTERROGATION->value);
            $devoir = $subjectNotes->firstWhere('evaluation.type', EvaluationTypeEnums::DEVOIR->value);
            $composition = $subjectNotes->firstWhere('evaluation.type', EvaluationTypeEnums::COMPOSITION->value);

            $noteInterro = $interro ? ($interro->value / $interro->evaluation->bareme->value) * 20 : null;
            $noteDevoir = $devoir ? ($devoir->value / $devoir->evaluation->bareme->value) * 20 : null;
            $noteComposition = $composition ? ($composition->value / $composition->evaluation->bareme->value) * 20 : null;

            if ($noteInterro !== null && $noteDevoir !== null) {
                $noteClasse = ($noteInterro + $noteDevoir) / 2;
            } elseif ($noteInterro !== null) {
                $noteClasse = $noteInterro;
            } elseif ($noteDevoir !== null) {
                $noteClasse = $noteDevoir;
            } else {
                $noteClasse = null;
            }

            $noteFinale = $noteClasse !== null && $noteComposition !== null
                ? ($noteClasse + $noteComposition) / 2
                : ($noteClasse ?? $noteComposition ?? 0);

            $noteDef = $noteFinale * $coefficient;

            $totalNote += $noteFinale * $coefficient;
            $totalCoeff += $coefficient;

            $results[] = [
                'subject' => $subjectName,
                'teacher' => $teacher,
                'coefficient' => $coefficient,
                'note_interro' => $noteInterro,
                'note_devoir' => $noteDevoir,
                'note_classe' => $noteClasse,
                'note_composition' => $noteComposition,
                'note_finale' => $noteFinale,
                'note_def' => $noteDef,
            ];
        }

        $moyenneGenerale = $totalCoeff > 0 ? $totalNote / $totalCoeff : 0;

        $moyenneAnnuel = null;

        $passage = null;

        $moyenneSem1 = 0;

        if ($student->classroom->level === ClassroomLevelEnums::LYCEE->value && $breakdown->type === BreakdownNameEnums::SEMESTRE->value && $breakdown->value === 2) {


            $breakdownSem1 = Breakdown::where('type', BreakdownNameEnums::SEMESTRE->value)->where('value', 1)->first();


            if ($breakdownSem1) {

                $notesSem1 = $student->notes()
                    ->whereHas('evaluation', fn($q) => $q->where('breakdown_id', $breakdownSem1->id))
                    ->get();

                $totalNoteSem1 = 0;

                $totalCoeffSem1 = 0;

                $notesBySubjectSem1 = $notesSem1->groupBy(fn($note) => $note->evaluation->assignation->subject->id);

                foreach ($notesBySubjectSem1 as $subjectNotes) {

                    $coeff = $subjectNotes->first()->evaluation->assignation->coefficient ?? 1;
                    $interro = $subjectNotes->firstWhere('evaluation.type', EvaluationTypeEnums::INTERROGATION->value);
                    $devoir = $subjectNotes->firstWhere('evaluation.type', EvaluationTypeEnums::DEVOIR->value);
                    $composition = $subjectNotes->firstWhere('evaluation.type', EvaluationTypeEnums::COMPOSITION->value);

                    $noteInterro = $interro ? ($interro->value / $interro->evaluation->bareme->value) * 20 : null;
                    $noteDevoir = $devoir ? ($devoir->value / $devoir->evaluation->bareme->value) * 20 : null;
                    $noteComposition = $composition ? ($composition->value / $composition->evaluation->bareme->value) * 20 : null;

                    if ($noteInterro !== null && $noteDevoir !== null) $noteClasse = ($noteInterro + $noteDevoir) / 2;
                    elseif ($noteInterro !== null) $noteClasse = $noteInterro;
                    elseif ($noteDevoir !== null) $noteClasse = $noteDevoir;
                    else $noteClasse = null;

                    $noteFinale = $noteClasse !== null && $noteComposition !== null
                        ? ($noteClasse + $noteComposition) / 2
                        : ($noteClasse ?? $noteComposition ?? 0);

                    $totalNoteSem1 += $noteFinale * $coeff;
                    $totalCoeffSem1 += $coeff;
                }

                $moyenneSem1 = $totalCoeffSem1 > 0 ? $totalNoteSem1 / $totalCoeffSem1 : 0;
            }

            $moyenneAnnuel = ($moyenneSem1 + $moyenneGenerale) / 2;
        }




        // COLLEGE -> bulletin du 3ème trimestre
        elseif ($student->classroom->level === ClassroomLevelEnums::COLLEGE->value && $breakdown->type === BreakdownNameEnums::TRIMESTRE->value && $breakdown->value === 3) {

            $moyennesTrimestres = [];

            for ($i = 1; $i <= 3; $i++) {

                $break = Breakdown::where('type', BreakdownNameEnums::TRIMESTRE->value . $i)->first();

                if ($break) {

                    $notesTrim = $student->notes()->whereHas('evaluation', fn($q) => $q->where('breakdown_id', $break->id))->get();
                    $totalNoteTrim = 0;
                    $totalCoeffTrim = 0;
                    $notesBySubTrim = $notesTrim->groupBy(fn($note) => $note->evaluation->assignation->subject->id);

                    foreach ($notesBySubTrim as $subjectNotes) {
                        $coeff = $subjectNotes->first()->evaluation->assignation->coefficient ?? 1;
                        $interro = $subjectNotes->firstWhere('evaluation.type', EvaluationTypeEnums::INTERROGATION->value);
                        $devoir = $subjectNotes->firstWhere('evaluation.type', EvaluationTypeEnums::DEVOIR->value);
                        $composition = $subjectNotes->firstWhere('evaluation.type', EvaluationTypeEnums::COMPOSITION->value);

                        $noteInterro = $interro ? ($interro->value / $interro->evaluation->bareme->value) * 20 : null;
                        $noteDevoir = $devoir ? ($devoir->value / $devoir->evaluation->bareme->value) * 20 : null;
                        $noteComposition = $composition ? ($composition->value / $composition->evaluation->bareme->value) * 20 : null;

                        if ($noteInterro !== null && $noteDevoir !== null) $noteClasse = ($noteInterro + $noteDevoir) / 2;
                        elseif ($noteInterro !== null) $noteClasse = $noteInterro;
                        elseif ($noteDevoir !== null) $noteClasse = $noteDevoir;
                        else $noteClasse = null;

                        $noteFinale = $noteClasse !== null && $noteComposition !== null
                            ? ($noteClasse + $noteComposition) / 2
                            : ($noteClasse ?? $noteComposition ?? 0);

                        $totalNoteTrim += $noteFinale * $coeff;
                        $totalCoeffTrim += $coeff;
                    }

                    $moyennesTrimestres[] = $totalCoeffTrim > 0 ? $totalNoteTrim / $totalCoeffTrim : 0;
                }
            }

            $moyenneAnnuel = count($moyennesTrimestres) > 0 ? array_sum($moyennesTrimestres) / count($moyennesTrimestres) : 0;
        }

        $passage = $moyenneAnnuel !== null && $moyenneAnnuel >= 10 ? 'Passe en classe superieur' : 'Ajourné';



        $pdf = Pdf::loadView('admin.pdf.students.bulletin', [
            'student' => $student,
            'breakdown' => $breakdown,
            'results' => $results,
            'totalCoeff' => $totalCoeff,
            'totalNote' => $totalNote,
            'moyenneGenerale' => $moyenneGenerale,
            'rank' => $rank,
            'maxAverage' => $maxAverage,
            'minAverage' => $minAverage,
            'moyenneAnnuel' => $moyenneAnnuel,
            'passage' => $passage,
            'moyenneSem1' => $moyenneSem1,
        ]);

        // return $pdf->download("Bulletin_{$student->last_name}_{$student->first_name}_{$breakdown->type} {$breakdown->value}.pdf");
        return $pdf->stream("Bulletin_{$student->last_name}_{$student->first_name}_{$breakdown->type} {$breakdown->value}.pdf");
    }


    public function bulletinView(Request $request)
    {
        $student = Student::with([
            'notes.evaluation.assignation.subject',
            'notes.evaluation.assignation.teacher',
            'notes.evaluation.bareme',
            'notes.appreciation',
        ])->findOrFail($request->student_id);

        $breakdown = Breakdown::findOrFail($request->breakdown_id);

        $classroom = $student->classroom;
        $students = $classroom->students;

        // ===============================
        // 🔹 CALCUL DES MOYENNES DE TOUTE LA CLASSE
        // ===============================
        $classAverages = [];

        foreach ($students as $stud) {
            $notes = $stud->notes()
                ->whereHas('evaluation', fn($q) => $q->where('breakdown_id', $breakdown->id))
                ->with([
                    'evaluation.assignation.subject',
                    'evaluation.bareme',
                ])->get();

            $notesBySubject = $notes->groupBy(fn($note) => $note->evaluation->assignation->subject->id);
            $totalWeighted = 0;
            $totalCoeff = 0;

            foreach ($notesBySubject as $subjectNotes) {
                $coeff = $subjectNotes->first()->evaluation->assignation->coefficient ?? 1;

                $interro = $subjectNotes->firstWhere('evaluation.type', EvaluationTypeEnums::INTERROGATION->value);
                $devoir = $subjectNotes->firstWhere('evaluation.type', EvaluationTypeEnums::DEVOIR->value);
                $composition = $subjectNotes->firstWhere('evaluation.type', EvaluationTypeEnums::COMPOSITION->value);

                $noteInterro = $interro ? ($interro->value / $interro->evaluation->bareme->value) * 20 : null;
                $noteDevoir = $devoir ? ($devoir->value / $devoir->evaluation->bareme->value) * 20 : null;
                $noteComp = $composition ? ($composition->value / $composition->evaluation->bareme->value) * 20 : null;

                if ($noteInterro !== null && $noteDevoir !== null)
                    $noteClasse = ($noteInterro + $noteDevoir) / 2;
                elseif ($noteInterro !== null)
                    $noteClasse = $noteInterro;
                elseif ($noteDevoir !== null)
                    $noteClasse = $noteDevoir;
                else
                    $noteClasse = null;

                $noteFinale = $noteClasse !== null && $noteComp !== null
                    ? ($noteClasse + $noteComp) / 2
                    : ($noteClasse ?? $noteComp ?? 0);

                $totalWeighted += $noteFinale * $coeff;
                $totalCoeff += $coeff;
            }

            $avg = $totalCoeff > 0 ? round($totalWeighted / $totalCoeff, 2) : 0;
            $classAverages[$stud->id] = $avg;
        }

        // Tri décroissant des moyennes
        arsort($classAverages);

        // Rang de l’élève courant
        $rank = array_search($student->id, array_keys($classAverages)) + 1;

        // Moyenne max et min
        $maxAverage = max($classAverages);
        $minAverage = min($classAverages);

        // ===============================
        // 🔹 CALCUL DES NOTES DU STUDENT ACTUEL
        // ===============================

        $notes = $student->notes()
            ->whereHas('evaluation', fn($q) => $q->where('breakdown_id', $breakdown->id))
            ->with([
                'evaluation.assignation.subject',
                'evaluation.assignation.teacher',
                'evaluation.bareme',
                'appreciation'
            ])
            ->get();

        $notesBySubject = $notes->groupBy(fn($note) => $note->evaluation->assignation->subject->id);

        $results = [];
        $totalNote = 0;
        $noteDef = 0;
        $totalCoeff = 0;

        foreach ($notesBySubject as $subject_id => $subjectNotes) {
            $subjectName = $subjectNotes->first()->evaluation->assignation->subject->name;
            $teacher = $subjectNotes->first()->evaluation->assignation->teacher;
            $coefficient = $subjectNotes->first()->evaluation->assignation->coefficient ?? 1;

            $bareme = $subjectNotes->first()->evaluation->bareme->value ?? 20;

            $interro = $subjectNotes->firstWhere('evaluation.type', EvaluationTypeEnums::INTERROGATION->value);
            $devoir = $subjectNotes->firstWhere('evaluation.type', EvaluationTypeEnums::DEVOIR->value);
            $composition = $subjectNotes->firstWhere('evaluation.type', EvaluationTypeEnums::COMPOSITION->value);

            $noteInterro = $interro ? ($interro->value / $interro->evaluation->bareme->value) * 20 : null;
            $noteDevoir = $devoir ? ($devoir->value / $devoir->evaluation->bareme->value) * 20 : null;
            $noteComposition = $composition ? ($composition->value / $composition->evaluation->bareme->value) * 20 : null;

            if ($noteInterro !== null && $noteDevoir !== null) {
                $noteClasse = ($noteInterro + $noteDevoir) / 2;
            } elseif ($noteInterro !== null) {
                $noteClasse = $noteInterro;
            } elseif ($noteDevoir !== null) {
                $noteClasse = $noteDevoir;
            } else {
                $noteClasse = null;
            }

            $noteFinale = $noteClasse !== null && $noteComposition !== null
                ? ($noteClasse + $noteComposition) / 2
                : ($noteClasse ?? $noteComposition ?? 0);

            $noteDef = $noteFinale * $coefficient;

            $totalNote += $noteFinale * $coefficient;
            $totalCoeff += $coefficient;

            $results[] = [
                'subject' => $subjectName,
                'teacher' => $teacher,
                'coefficient' => $coefficient,
                'note_interro' => $noteInterro,
                'note_devoir' => $noteDevoir,
                'note_classe' => $noteClasse,
                'note_composition' => $noteComposition,
                'note_finale' => $noteFinale,
                'note_def' => $noteDef,
            ];
        }

        $moyenneGenerale = $totalCoeff > 0 ? $totalNote / $totalCoeff : 0;

        return view('admin.students.bulletin', compact(
            'student',
            'breakdown',
            'results',
            'totalCoeff',
            'totalNote',
            'moyenneGenerale',
            'rank',
            'maxAverage',
            'minAverage'
        ));
    }
}
