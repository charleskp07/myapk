@extends('layouts.authchecked')

@section('title', 'Emploi du Temps Automatique')

@section('css')
    <!-- FullCalendar CSS -->
    <link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.9/index.global.min.css' rel='stylesheet' />
    <style>
        #calendar {
            font-family: Arial, sans-serif;
        }
        .fc-event {
            cursor: pointer;
            border-radius: 4px;
        }
        .fc-event:hover {
            opacity: 0.8;
        }
        .btn-generate {
            border-radius: 25px;
        }
        .message-zone {
            min-height: 50px;
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid">
        <!-- Header -->
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">
                    <i class="fas fa-calendar-alt me-2"></i>Générateur d'Emploi du Temps Automatique
                </h4>
                <p class="mb-0 mt-2 opacity-75">Système intelligent de planification scolaire</p>
            </div>
        </div>

        <!-- Panel de Contrôle -->
        <div class="card mb-4">
            <div class="card-body">
                <div class="row align-items-end">
                    <div class="col-md-6">
                        <label for="classroomSelect" class="form-label fw-bold">
                            <i class="fas fa-school me-1"></i>Sélectionner une Classe
                        </label>
                        <select id="classroomSelect" class="form-select form-select-lg">
                            <option value="">-- Choisissez une classe --</option>
                            @foreach ($classrooms as $classroom)
                                <option value="{{ $classroom->id }}" 
                                        data-level="{{ $classroom->level }}"
                                        {{ $selectedClassroom == $classroom->id ? 'selected' : '' }}>
                                    {{ $classroom->name }} {{ ucfirst($classroom->section) }} 
                                    ({{ $classroom->level }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <div class="d-flex gap-2">
                            <button id="btnGenerate" class="btn btn-primary btn-generate flex-grow-1">
                                <i class="fas fa-magic me-2"></i>Générer l'Emploi du Temps
                            </button>
                            <button id="btnDelete" class="btn btn-danger flex-grow-1" style="border-radius: 25px;">
                                <i class="fas fa-trash me-2"></i>Supprimer
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Zone de messages -->
                <div id="messageZone" class="mt-3 message-zone"></div>
            </div>
        </div>

        <!-- Calendrier -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-calendar-week me-2"></i>Emploi du Temps Hebdomadaire
                </h5>
            </div>
            <div class="card-body">
                <div id="calendar"></div>
            </div>
        </div>
    </div>

    <!-- Modal pour les détails d'un événement -->
    <div class="modal fade" id="eventModal" tabindex="-1" aria-labelledby="eventModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="eventModalLabel">Détails du Cours</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="eventModalBody">
                    <!-- Contenu dynamique -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <!-- Bootstrap JS (pour les modals) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- FullCalendar JS -->
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.9/index.global.min.js'></script>
    <script src='https://cdn.jsdelivr.net/npm/@fullcalendar/core@6.1.9/locales/fr.global.min.js'></script>
    
    <script>
        // Configuration CSRF pour AJAX
        const csrfToken = $('meta[name="csrf-token"]').attr('content');
        
        if (!csrfToken) {
            console.error('CSRF token not found!');
        }
        
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': csrfToken || '{{ csrf_token() }}'
            }
        });
        
        // Fonction helper pour obtenir le token CSRF
        function getCsrfToken() {
            return $('meta[name="csrf-token"]').attr('content') || '{{ csrf_token() }}';
        }

        let calendar;
        let eventModal;

        // Initialisation du calendrier
        $(document).ready(function() {
            const calendarEl = document.getElementById('calendar');
            
            // Initialiser le modal Bootstrap si disponible
            const modalElement = document.getElementById('eventModal');
            if (modalElement && typeof bootstrap !== 'undefined') {
                eventModal = new bootstrap.Modal(modalElement);
            }

            calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'timeGridWeek',
                locale: 'fr',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'timeGridWeek,timeGridDay'
                },
                slotMinTime: '07:00:00',
                slotMaxTime: '19:00:00',
                allDaySlot: false,
                slotDuration: '00:30:00', // Granularité de 30 minutes
                height: 'auto',
                hiddenDays: [0, 6], // Masquer dimanche et samedi
                firstDay: 1, // Commencer par lundi
                businessHours: [
                    {
                        daysOfWeek: [1, 2, 3, 4, 5],
                        startTime: '07:00',
                        endTime: '09:45'
                    },
                    {
                        daysOfWeek: [1, 2, 3, 4, 5],
                        startTime: '10:00',
                        endTime: '12:00'
                    },
                    {
                        daysOfWeek: [1, 3], // Lundi et jeudi seulement (pas mercredi/vendredi après-midi)
                        startTime: '15:00',
                        endTime: '17:00'
                    },
                    {
                        daysOfWeek: [1, 3], // Lundi et jeudi soirée
                        startTime: '17:00',
                        endTime: '19:00'
                    }
                ],
                slotLabelInterval: '01:00:00',
                slotLabelFormat: {
                    hour: '2-digit',
                    minute: '2-digit',
                    hour12: false
                },
                events: function(info, successCallback, failureCallback) {
                    loadEvents(successCallback, failureCallback);
                },
                eventClick: function(info) {
                    showEventDetails(info.event);
                },
                eventContent: function(arg) {
                    return {
                        html: `
                            <div style="padding: 4px; font-size: 0.85em;">
                                <strong>${arg.event.title}</strong><br>
                                <small style="opacity: 0.9;">${arg.event.extendedProps.teacher || ''}</small>
                            </div>
                        `
                    };
                },
                eventDisplay: 'block',
                eventTimeFormat: {
                    hour: '2-digit',
                    minute: '2-digit',
                    hour12: false
                }
            });

            calendar.render();

            // Charger les événements si une classe est sélectionnée
            const selectedClassroom = $('#classroomSelect').val();
            if (selectedClassroom && calendar) {
                calendar.refetchEvents();
            }
        });

        // Charger les événements
        function loadEvents(successCallback, failureCallback) {
            const classroomId = $('#classroomSelect').val();

            if (!classroomId) {
                successCallback([]);
                return;
            }

            $.ajax({
                url: '{{ route('timetable.events') }}',
                method: 'GET',
                data: {
                    classroom_id: classroomId
                },
                success: function(events) {
                    successCallback(events);
                },
                error: function(xhr) {
                    console.error('Erreur chargement événements:', xhr);
                    showMessage('Erreur lors du chargement des événements', 'danger');
                    if (failureCallback) failureCallback();
                }
            });
        }

        // Générer l'emploi du temps
        $('#btnGenerate').click(function() {
            const classroomId = $('#classroomSelect').val();

            if (!classroomId) {
                showMessage('Veuillez sélectionner une classe', 'warning');
                return;
            }

            const $btn = $(this);
            const originalHtml = $btn.html();
            $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-2"></i>Génération en cours...');

            // Désactiver aussi le bouton supprimer
            $('#btnDelete').prop('disabled', true);

            $.ajax({
                url: '{{ route('timetable.generate') }}',
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': getCsrfToken()
                },
                data: {
                    classroom_id: classroomId,
                    _token: getCsrfToken()
                },
                timeout: 60000, // 60 secondes timeout
                success: function(response) {
                    if (response.success) {
                        const message = response.message + 
                            (response.schedules_count ? ` (${response.schedules_count} créneaux placés)` : '');
                        showMessage(message, 'success');
                        if (calendar) {
                            calendar.refetchEvents();
                        }
                    } else {
                        showMessage(response.message || 'Erreur lors de la génération', 'danger');
                    }
                },
                error: function(xhr) {
                    let message = 'Erreur lors de la génération';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        message = xhr.responseJSON.message;
                    } else if (xhr.status === 0) {
                        message = 'Timeout: La génération prend trop de temps. Vérifiez les contraintes.';
                    }
                    showMessage(message, 'danger');
                },
                complete: function() {
                    $btn.prop('disabled', false).html(originalHtml);
                    $('#btnDelete').prop('disabled', false);
                }
            });
        });

        // Supprimer l'emploi du temps
        $('#btnDelete').click(function() {
            const classroomId = $('#classroomSelect').val();

            if (!classroomId) {
                showMessage('Veuillez sélectionner une classe', 'warning');
                return;
            }

            if (!confirm('Êtes-vous sûr de vouloir supprimer l\'emploi du temps de cette classe ?')) {
                return;
            }

            const $btn = $(this);
            $btn.prop('disabled', true);

            $.ajax({
                url: `/timetable/${classroomId}`,
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': getCsrfToken()
                },
                data: {
                    _token: getCsrfToken()
                },
                success: function(response) {
                    if (response.success) {
                        showMessage(response.message, 'success');
                        if (calendar) {
                            calendar.refetchEvents();
                        }
                    } else {
                        showMessage(response.message || 'Erreur lors de la suppression', 'danger');
                    }
                },
                error: function(xhr) {
                    showMessage('Erreur lors de la suppression', 'danger');
                },
                complete: function() {
                    $btn.prop('disabled', false);
                }
            });
        });

        // Recharger le calendrier lors du changement de classe
        $('#classroomSelect').change(function() {
            if (calendar) {
                calendar.refetchEvents();
            }
        });

        // Afficher un message
        function showMessage(message, type) {
            const icons = {
                'success': 'check-circle',
                'danger': 'exclamation-circle',
                'warning': 'exclamation-triangle',
                'info': 'info-circle'
            };

            const icon = icons[type] || 'info-circle';
            
            // Convertir les retours à la ligne en <br> pour l'affichage HTML
            const formattedMessage = message.replace(/\n/g, '<br>');
            
            const alertHtml = `
                <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                    <i class="fas fa-${icon} me-2"></i>
                    <div style="white-space: pre-line;">${formattedMessage}</div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            `;

            $('#messageZone').html(alertHtml);

            // Auto-hide après 5 secondes (sauf pour les erreurs)
            if (type !== 'danger') {
                setTimeout(function() {
                    $('#messageZone .alert').fadeOut(function() {
                        $(this).remove();
                    });
                }, 5000);
            }
        }

        // Afficher les détails d'un événement
        function showEventDetails(event) {
            const props = event.extendedProps;
            const startTime = new Date(event.start).toLocaleTimeString('fr-FR', {
                hour: '2-digit',
                minute: '2-digit'
            });
            const endTime = new Date(event.end).toLocaleTimeString('fr-FR', {
                hour: '2-digit',
                minute: '2-digit'
            });

            const detailsHtml = `
                <div class="mb-3">
                    <h6 class="text-primary"><i class="fas fa-book me-2"></i>Matière</h6>
                    <p class="mb-0">${props.subject || 'N/A'}</p>
                </div>
                <div class="mb-3">
                    <h6 class="text-primary"><i class="fas fa-chalkboard-teacher me-2"></i>Enseignant</h6>
                    <p class="mb-0">${props.teacher || 'N/A'}</p>
                </div>
                <div class="mb-3">
                    <h6 class="text-primary"><i class="fas fa-school me-2"></i>Classe</h6>
                    <p class="mb-0">${props.classroom || 'N/A'}</p>
                </div>
                <div class="mb-3">
                    <h6 class="text-primary"><i class="fas fa-clock me-2"></i>Horaire</h6>
                    <p class="mb-0">${startTime} - ${endTime} (${props.duration || 'N/A'})</p>
                </div>
                ${props.room && props.room !== 'N/A' ? `
                <div class="mb-3">
                    <h6 class="text-primary"><i class="fas fa-door-open me-2"></i>Salle</h6>
                    <p class="mb-0">${props.room}</p>
                </div>
                ` : ''}
            `;

            $('#eventModalBody').html(detailsHtml);
            if (eventModal) {
                eventModal.show();
            } else {
                // Fallback si Bootstrap n'est pas disponible
                alert(`Matière: ${props.subject || 'N/A'}\nEnseignant: ${props.teacher || 'N/A'}\nHoraire: ${startTime} - ${endTime}`);
            }
        }
    </script>
@endsection
