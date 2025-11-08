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

        <div class="chart">
            <canvas id="paymentsChart"></canvas>
        </div>

        <div class="chart-legend">
            <h4>Rapport des paiements :</h4>
            <ul>
                <li>
                    <span
                        style="display: inline-block; width: 15px; height: 15px; background-color: #4CAF50; border-radius: 50%; margin-right: 10px; flex-shrink: 0;">
                    </span>
                    <span><strong>Collecté :</strong> {{ number_format($totalCollected, 0, ',', ' ') }} XOF</span>
                </li>
                <li>
                    <span
                        style="display: inline-block; width: 15px; height: 15px; background-color: #FF5722; border-radius: 50%; margin-right: 10px; flex-shrink: 0;">
                    </span>
                    <span><strong>Restant :</strong> {{ number_format($totalExpected - $totalCollected, 0, ',', ' ') }}
                        XOF</span>
                </li>
            </ul>
            <p>
                <strong>Total attendu :</strong> {{ number_format($totalExpected, 0, ',', ' ') }} XOF
            </p>
        </div>
    </div>




@endsection

@section('js')

    <script>
        // Graphique genre
        const genderCtx = document.getElementById('genderChart').getContext('2d');
        const genderChart = new Chart(genderCtx, {
            type: 'doughnut',
            data: {
                labels: ['Masculin', 'Féminin'],
                datasets: [{
                    label: 'Répartition des apprenants (%)',
                    data: [{{ $malePercentage }}, {{ $femalePercentage }}],
                    backgroundColor: [
                        'rgb(54, 162, 235)', // masculin
                        'rgb(255, 99, 132)', // féminin
                    ],
                    hoverOffset: 10,
                    borderWidth: 1,
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.label + ': ' + context.formattedValue + '%';
                            }
                        }
                    },
                    legend: {
                        position: 'bottom'
                    }
                },
                scales: {
                    x: {
                        display: false,
                        grid: {
                            display: false
                        }
                    },
                    y: {
                        display: true,
                        grid: {
                            display: true
                        },
                        min: 0,
                        max: 1
                    }
                },
            }
        });

        // Graphique paiements
        const paymentsCtx = document.getElementById('paymentsChart').getContext('2d');
        const paymentsChart = new Chart(paymentsCtx, {
            type: 'doughnut',
            data: {
                labels: ['Collecté', 'Restant'],
                datasets: [{
                    label: 'Paiements',
                    data: [{{ $totalCollected }}, {{ $totalExpected - $totalCollected }}],
                    backgroundColor: ['#4CAF50', '#FF5722'],
                    hoverOffset: 10,
                    borderWidth: 1,
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                },
                scales: {
                    x: {
                        display: false,
                        grid: {
                            display: false
                        }
                    },
                    y: {
                        display: true,
                        grid: {
                            display: true
                        },
                        min: 0,
                        max: 1
                    }
                },
                animation: {
                    animateRotate: true,
                    animateScale: true
                },

            }
        });
    </script>

@endsection
