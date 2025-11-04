@extends('layouts.authchecked')

@section('title', 'Tableau de bord')


@section('content')
    <h1>Tableau de bord</h1>

    <div class="cards-cover">
        <div class="card">
            <h3>Salles de classe</h3>
            <p>{{ $classrooms->count() }}</p>
            <div>
                <a href="{{ route('classrooms.index') }}">
                    Voir tous
                    <i class="bi bi-arrow-right"></i>
                </a>
            </div>
        </div>


        <div class="card">
            <h3>Enseignants</h3>
            <p>{{ $teachers->count() }}</p>
            <div>
                <a href="{{ route('teachers.index') }}">
                    Voir tous
                    <i class="bi bi-arrow-right"></i>
                </a>
            </div>
        </div>


        <div class="card">
            <h3>Apprenants</h3>
            <p>{{ $students->count() }}</p>
            <div>
                <a href="{{ route('students.index') }}">
                    Voir tous
                    <i class="bi bi-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>


    <div class="chart-cover">

        <div class="chart">
            <canvas id="genderChart"></canvas>
        </div>

        <div class="chart-legend">
            <h4>Répartition des apprenants :</h4>
            <ul>
                <li>
                    <span
                        style="display: inline-block; width: 15px; height: 15px; background-color: rgb(54, 162, 235); border-radius: 50%; margin-right: 10px; flex-shrink: 0;">
                    </span>
                    <span><strong>Masculin :</strong> {{ $maleCount }} apprenants ({{ $malePercentage }}%)</span>
                </li>
                <li>
                    <span
                        style="display: inline-block; width: 15px; height: 15px; background-color: rgb(255, 99, 132); border-radius: 50%; margin-right: 10px; flex-shrink: 0;">
                    </span>
                    <span><strong>Féminin :</strong> {{ $femaleCount }} apprenantes ({{ $femalePercentage }}%)</span>
                </li>
            </ul>
            <p>
                <strong>Total :</strong> {{ $maleCount + $femaleCount }} apprenants
            </p>
        </div>

    </div>


@endsection


@section('js')

    <script>
        const ctx = document.getElementById('genderChart');

        const data = {
            labels: ['Masculin', 'Féminin'],
            datasets: [{
                label: 'Répartition des apprenants (%)',
                data: [{{ $malePercentage }}, {{ $femalePercentage }}],
                backgroundColor: [
                    'rgb(54, 162, 235)', // Pour les masculin
                    'rgb(255, 99, 132)', // rose les feminin
                ],
                hoverOffset: 10,
                borderWidth: 1,
            }]
        };

        new Chart(ctx, {
            type: 'doughnut',
            data: data,
            options: {
                plugins: {
                    title: {
                        display: true,
                        text: 'Répartition des apprenants par genre',
                        font: {
                            size: 16
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.label + ': ' + context.formattedValue + '%';
                            }
                        }
                    }
                }
            }
        });
    </script>

@endsection
