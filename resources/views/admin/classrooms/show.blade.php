@extends('layouts.authchecked')

@section('title', 'Details d\'une classe')

@section('content')

    <div>
        <div class="back-btn">
            <a href="{{ url()->previous() }}">
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
                {{ $classroom->teacher ? strtoupper($classroom->teacher->last_name) . ' ' . $classroom->teacher->first_name : 'Pas defini' }}<br>
            </p>

            <p>
                <strong>Nombre d'apprenant:</strong>
                {{ $classroom->students->count() }}<br>
            </p>

            <p>
                <a href="{{ route('admin.list.students.pdf', ['classroom_id' => $classroom->id]) }}" target="_blank">
                    Telecharger la liste des apprenants (Version PDF)
                </a>

                <br />

                <a href="{{ route('timetable.index', $classroom->id) }}" target="_blank">
                   Emploie du temps
                </a>
            </p>
        </div>

        <br />
        <br />

        <div>
            <div>
                <h2>Listes des frais associés</h2>

                <a href="{{ route('fees.create', ['classroom_id' => $classroom->id]) }}">
                    Ajouter des frais
                </a>
            </div>

            @if ($classroom->fees->isEmpty())
                <div style="text-align: center">
                    <p>Aucun Frais n'est associé(e) à la {{ $classroom->name }} {{ $classroom->section }}</p>
                    <br />
                    <img src="{{ asset('images/icons/trash-empty-svgrepo-com.png') }}" alt="" width="50" class="empty-icon">
                </div>
            @else
                <table class="fees-table" style="width: 100%; border-collapse: collapse; margin-top: 10px;">
                    <thead>
                        <tr style="background: #f3f4f6; text-align: left;">
                            <th style="padding: 10px;"></th>
                            <th style="padding: 10px;">Nom du frais</th>
                            <th style="padding: 10px;">Montant</th>
                            <th style="padding: 10px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($classroom->fees as $index => $fee)
                            <tr style="border-bottom: 1px solid #e5e7eb;">
                                <td style="padding: 8px;">{{ $index + 1 }}</td>
                                <td style="padding: 8px;">{{ $fee->name }}</td>
                                <td style="padding: 8px;">{{ number_format($fee->amount, 0, ',', ' ') }} XOF</td>
                                <td style="padding: 8px;">
                                    <a href="{{ route('fees.edit', $fee->id) }}"
                                        style="color: #2563eb; text-decoration: none;">
                                        <i class="fa-regular fa-pen-to-square"></i>Modifier</a>
                                    {{-- <form action="{{ route('fees.destroy', $fee->id) }}" method="POST"
                                        style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            style="background: none; border: none; color: #dc2626; cursor: pointer;"
                                            onclick="return confirm('Voulez-vous vraiment supprimer ce frais ?')">
                                            Supprimer
                                        </button>
                                    </form> --}}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr style="background: #f3f4f6; font-weight: bold;">
                            <td colspan="2" style="padding: 8px;">Total des frais :</td>
                            <td style="padding: 8px;">
                                {{ number_format($classroom->fees->sum('amount'), 0, ',', ' ') }} XOF
                            </td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            @endif
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
                    <img src="{{ asset('images/icons/trash-empty-svgrepo-com.png') }}" alt="" width="50" class="empty-icon">
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
                                        {{ strtoupper($student->last_name) }}
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
                    <img src="{{ asset('images/icons/trash-empty-svgrepo-com.png') }}" alt="" width="50" class="empty-icon">
                </div>
            @else
                <div class="datatable-cover">
                    <table id="assignationDatatable">
                        <thead>
                            <tr>
                                <th>Enseignant</th>
                                <th>Matière</th>
                                <th class="label-first no-sort">Coefficient</th>
                                <th>Nombre d'heure de cours</th>
                                <th width="40"></th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($classroom->assignations as $assignation)
                                <tr>
                                    <td onclick='onTeacherClick("{{ $assignation->teacher->id }}")'>
                                        {{ strtoupper($assignation->teacher->last_name) }}
                                        {{ $assignation->teacher->first_name }}
                                    </td>


                                    <td onclick='onTeacherClick("{{ $assignation->teacher->id }}")'>
                                        {{ $assignation->subject->name }}
                                    </td>

                                    <td onclick='onTeacherClick("{{ $assignation->teacher->id }}")'>
                                        {{ $assignation->coefficient }}
                                    </td>

                                    <td onclick='onTeacherClick("{{ $assignation->teacher->id }}")'>
                                        {{ $assignation->weekly_hours }} Heures
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
                    <img src="{{ asset('images/icons/trash-empty-svgrepo-com.png') }}" alt="" width="50" class="empty-icon">
                </div>
            @else
                <div class="datatable-cover">
                    <table id="evaluationDatatables">
                        <thead>
                            <tr>
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
                                    <td onclick='onEvaluationClick("{{ $evaluation->id }}")'>
                                        {{ $evaluation->breakdown->type }}
                                        {{ $evaluation->breakdown->value }}
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
                    <option value="{{ $breakdown->id }}">{{ $breakdown->type }} {{ $breakdown->value }}</option>
                @endforeach
            </select>

            <div style="max-width: 450px">
                <canvas id="classChart"></canvas>
            </div>
        </div>

        <br />
        <br />
        <br />
        <div>
            <div>
                <h2>Plannings des cours</h2>
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
                targets: [4],
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
