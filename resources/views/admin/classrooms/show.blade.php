@extends('layouts.authchecked')

@section('title', 'Details d\'une classe')

@section('content')

    <div>
        <div class="back-btn">
            <a href="{{ route('classrooms.index') }}">
                <i class="bi bi-arrow-left"></i>
                Retour
            </a>
        </div>

        <h1>Detais de la {{ $classroom->name }} {{ $classroom->section }}</h1>

        <br />

        <div>
            <p>
                <strong>Niveau:</strong>
                {{ $classroom->level }} <br>
            </p>

            <p>
                <strong>Nom:</strong>
                {{ $classroom->name }} <br>
            </p>

            <p>
                <strong>Section:</strong>
                {{ $classroom->section }}<br>
            </p>

            <p>
                <strong>Enseignant titulaire:</strong>
                {{ $classroom->teacher ? $classroom->teacher->last_name . ' ' . $classroom->teacher->first_name : 'Pas defini' }}<br>
            </p>


            <p>
                <strong>Nombre d'apprenant:</strong>
                {{ $classroom->students->count() }}<br>
            </p>
        </div>


        <br />
        <br />

        <div>
            <div>
                <h2>Listes des apprenants</h2>

                <a href="{{ route('students.create', ['classroom_id' => $classroom->id]) }}">
                    Ajouter un apprenant
                </a>
            </div>

            @if ($classroom->students->isEmpty())
                <div style="text-align: center">
                    <p>Aucun(e) apprenant(e) n'est associé(e) à la {{ $classroom->name }} {{ $classroom->section }}</p>
                    <br />
                    <img src="{{ asset('images/icons/trash-empty-svgrepo-com.png') }}" alt="" width="50">
                </div>
            @else
                <div class="datatables-cover">
                    <table id="datatables">
                        <thead>
                            <tr>
                                <th>
                                    Nom de famille
                                </th>

                                <th>
                                    Prénoms
                                </th>

                                <th>
                                    Date de naissance
                                </th>

                                <th width="40">

                                </th>
                            </tr>

                        </thead>
                        <tbody>
                            @foreach ($classroom->students as $student)
                                <tr>
                                    <td onclick='onStudentClick("{{ $student->id }}")'>
                                        {{ $student->last_name }}
                                    </td>

                                    <td onclick='onStudentClick("{{ $student->id }}")'>
                                        {{ $student->first_name }}
                                    </td>

                                    <td onclick='onStudentClick("{{ $student->id }}")'>
                                        {{ \Carbon\Carbon::parse($student->date_of_birth)->format('d/m/Y') }}
                                    </td>

                                    <td>
                                        <div class="dropdown-cover">
                                            <button class="more-icon" data-target="dropdown-parent-{{ $student->id }}">
                                                <i class="fa-solid fa-ellipsis-vertical"></i>
                                            </button>
                                            <div class="dropdown-items" id="dropdown-parent-{{ $student->id }}">
                                                <ul>
                                                    <li>
                                                        <a href="{{ route('students.show', $student->id) }}">
                                                            <div>
                                                                <i class="fa-solid fa-list-ul"></i>
                                                                Détails
                                                            </div>
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="{{ route('students.edit', $student->id) }}">
                                                            <div>
                                                                <i class="fa-solid fa-pen-to-square"></i>
                                                                Modifier
                                                            </div>
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <form action="{{ route('students.destroy', $student->id) }}"
                                                            method="post"
                                                            onclick="return confirm ('Êtes-vous sûr(e) de vouloir supprimer cet enregistrement ? Cette action sera irréversible !')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button>
                                                                <i class="fa-solid fa-trash"></i>
                                                                Supprimer
                                                            </button>
                                                        </form>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        <br />
        <br />

        <div>
            <div>
                <h2>Listes des Enseignants et leur matière</h2>

                <a href="{{ route('assignations.create', ['classroom_id' => $classroom->id]) }}">
                    Ajouter une assignation
                </a>
            </div>

            @if ($classroom->assignations->isEmpty())
                <div style="text-align: center">
                    <p>Aucun(e) assignation n'a été enregistrée pour la {{ $classroom->name }} {{ $classroom->section }}
                    </p><br />
                    <img src="{{ asset('images/icons/trash-empty-svgrepo-com.png') }}" alt="" width="50">
                </div>
            @else
                <div class="datatable-cover">
                    <table id="assignationDatatable">
                        <thead>
                            <tr>
                                <th>Enseignant</th>
                                <th>Matière</th>
                                <th class="label-first no-sort">Coefficient</th>
                                <th width="40"></th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($classroom->assignations as $assignation)
                                <tr>
                                    <td onclick='onTeacherClick("{{ $assignation->teacher->id }}")'>
                                        {{ $assignation->teacher->last_name }} {{ $assignation->teacher->first_name }}
                                    </td>


                                    <td onclick='onTeacherClick("{{ $assignation->teacher->id }}")'>
                                        {{ $assignation->subject->name }}
                                    </td>

                                    <td onclick='onTeacherClick("{{ $assignation->teacher->id }}")'>
                                        {{ $assignation->coefficient }}
                                    </td>


                                    <td>
                                        <div class="dropdown-cover">
                                            <button class="more-icon"
                                                data-target="dropdown-parent-bis-{{ $assignation->id }}">
                                                <i class="fa-solid fa-ellipsis-vertical"></i>
                                            </button>
                                            <div class="dropdown-items" id="dropdown-parent-bis-{{ $assignation->id }}">
                                                <ul>
                                                    <li>
                                                        <a href="{{ route('assignations.edit', $assignation->id) }}">
                                                            <div>
                                                                <i class="fa-solid fa-pen-to-square"></i>
                                                                Modifier
                                                            </div>
                                                        </a>
                                                    </li>

                                                    <li>
                                                        <form
                                                            action="{{ route('assignations.destroy', $assignation->id) }}"
                                                            method="post"
                                                            onclick="return confirm('Êtes-vous sûr(e) de vouloir supprimer cette assignation ? Cette action sera irréversible !')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button>
                                                                <i class="fa-solid fa-trash"></i>
                                                                Supprimer
                                                            </button>
                                                        </form>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        <br />
        <br />

        <div>
            <div>
                <h2>Listes des evaluations</h2>

                <a href="{{ route('evaluations.create', ['classroom_id' => $classroom->id]) }}">
                    Créer une évaluation
                </a>
            </div>

            @if ($classroom->evaluations->isEmpty())
                <div style="text-align: center">
                    <p>Aucun(e) évaluation n'a été enregistrée pour la {{ $classroom->name }} {{ $classroom->section }}
                    </p><br />
                    <img src="{{ asset('images/icons/trash-empty-svgrepo-com.png') }}" alt="" width="50">
                </div>
            @else
                <div class="datatable-cover">
                    <table id="evaluationDatatables">
                        <thead>
                            <tr>
                                {{-- <th>Statut</th> --}}
                                <th>
                                    Decoupage
                                </th>
                                <th>Type</th>
                                <th>Date</th>
                                <th>Matière</th>
                                <th width="40"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($classroom->evaluations as $evaluation)
                                <tr>
                                    {{-- <td onclick='onEvaluationClick("{{ $evaluation->id }}")'>

                                        @php
                                            $today = \Carbon\Carbon::today();
                                            $evalDate = \Carbon\Carbon::parse($evaluation->date);

                                            if ($evalDate->isFuture()) {
                                                $status = 'À venir';
                                            } elseif ($evalDate->isToday()) {
                                                $status = 'En cours';
                                            } else {
                                                $status = 'Passé';
                                            }
                                        @endphp


                                        <span style="margin-left: 10px;">
                                            {{ $status }}
                                        </span>

                                    </td> --}}

                                    <td onclick='onEvaluationClick("{{ $evaluation->id }}")'>
                                        {{ $evaluation->breakdown->name }}
                                    </td>

                                    <td onclick='onEvaluationClick("{{ $evaluation->id }}")'>
                                        {{ ucfirst(strtolower($evaluation->type)) }}
                                    </td>

                                    <td onclick='onEvaluationClick("{{ $evaluation->id }}")'>
                                        {{ \Carbon\Carbon::parse($evaluation->date)->format('d/m/Y') }}
                                    </td>

                                    <td onclick='onEvaluationClick("{{ $evaluation->id }}")'>
                                        {{ $evaluation->assignation->subject->name }}
                                    </td>

                                    <td>
                                        <div class="dropdown-cover">
                                            <button class="more-icon"
                                                data-target="dropdown-parent-triple-{{ $evaluation->id }}">
                                                <i class="fa-solid fa-ellipsis-vertical"></i>
                                            </button>
                                            <div class="dropdown-items" id="dropdown-parent-triple-{{ $evaluation->id }}">
                                                <ul>
                                                    <li>
                                                        <a href="{{ route('evaluations.show', $evaluation->id) }}">
                                                            <div>
                                                                <i class="fa-solid fa-list-ul"></i>
                                                                Détails
                                                            </div>
                                                        </a>
                                                    </li>

                                                    <li>
                                                        <a href="{{ route('evaluations.edit', $evaluation->id) }}">
                                                            <div>
                                                                <i class="fa-solid fa-pen-to-square"></i>
                                                                Modifier
                                                            </div>
                                                        </a>
                                                    </li>

                                                    <li>
                                                        <form action="{{ route('evaluations.destroy', $evaluation->id) }}"
                                                            method="post"
                                                            onclick="return confirm('Êtes-vous sûr(e) de vouloir supprimer cette evaluation ? Cette action sera irréversible !')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button>
                                                                <i class="fa-solid fa-trash"></i>
                                                                Supprimer
                                                            </button>
                                                        </form>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>


        <br />
        <br />

        <div>
            <h2>Statistiques des mentions par découpage</h2>
            <br />
            <label for="breakdown">Choisir le découpage :</label>
            <select id="breakdown">
                <option value="">-- Sélectionner --</option>
                @foreach ($breakdowns as $breakdown)
                    <option value="{{ $breakdown->id }}">{{ $breakdown->name }}</option>
                @endforeach
            </select>

           <div style="max-width: 450px">
                 <canvas id="classChart"></canvas>
           </div>
        </div>

    </div>
@endsection

@section('js')

    <script>
        new DataTable('#datatables', {
            responsive: true,
            columnDefs: [{
                orderable: false,
                targets: [3]
            }],
            info: false,
        })

        new DataTable('#assignationDatatable', {
            responsive: true,
            // searching: false,
            paging: false,
            info: false,
            columnDefs: [{
                orderable: false,
                targets: [3],
            }]

        })

        new DataTable('#evaluationDatatables', {
            responsive: true,
            // searching: false,
            // paging: false,
            info: false,
            columnDefs: [{
                orderable: false,
                targets: [4],
            }]

        })

        function onStudentClick(id) {
            window.location.href = `/students/${id}`
        }

        function onTeacherClick(id) {
            window.location.href = `/teachers/${id}`
        }

        function onEvaluationClick(id) {
            window.location.href = `/evaluations/${id}`
        }

        let ctx = document.getElementById('classChart').getContext('2d');
        let classChart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Excellent', 'Très bien', 'Bien', 'Assez-bien', 'Passable', 'Insuffisant', 'Médiocre'],
                datasets: [{
                    label: 'Nombre d\'élèves',
                    data: [0, 0, 0, 0, 0, 0, 0],
                    backgroundColor: ['#4caf50', '#2196f3', '#00bcd4', '#ffc107', '#ff9800', '#f44336',
                        '#9e9e9e'
                    ],
                    borderWidth: 1,
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        stepSize: 1
                    }
                }
            }
        });

        document.getElementById('breakdown').addEventListener('change', function() {
            let breakdownId = this.value;
            if (!breakdownId) return;

            fetch(`{{ route('classroom.stats.data', ['id' => $classroom->id]) }}?breakdown_id=${breakdownId}`)
                .then(res => res.json())
                .then(res => {
                    classChart.data.datasets[0].data = res.data;
                    classChart.update();
                });
        });
    </script>

@endsection
