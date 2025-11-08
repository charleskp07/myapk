<div class="sidebar" id="sidebar">
    <button id="sidebarToggle">☰</button>

    <br />
    <br />
    <br />

    <ul>
        <li>
            <a href="{{ route('dashboard') }}">
                <div>
                    <i class="fa fa-gauge"></i>
                    <span>Tableau de bord</span>
                </div>
            </a>
        </li>

        <li>
            <a href="{{ route('classrooms.index') }}">
                <div>
                    <i class="bi bi-door-open"></i>
                    <span>Gestion des Salles</span>
                </div>
            </a>
        </li>

        <li>
            <a href="{{ route('students.index') }}">
                <div>
                    <i class="bi bi-mortarboard-fill"></i>
                    <span>Gestion des Apprenants</span>
                </div>
            </a>
        </li>

        <li>
            <a href="{{ route('teachers.index') }}">
                <div>
                    <i class="fa-solid fa-person-chalkboard"></i>
                    <span>Gestion des Enseignants</span>
                </div>
            </a>
        </li>

        <li>
            <a href="{{ route('subjects.index') }}">
                <div>
                    <i class="fa-solid fa-book"></i>
                    <span>Gestion des Matières</span>
                </div>
            </a>
        </li>

        <li>
            <a href="{{ route('assignations.index') }}">
                <div>
                    <i class="fa-solid fa-scroll"></i>
                    <span>Gestion des Assignations</span>
                </div>
            </a>
        </li>

        <li>
            <a href="{{ route('evaluations.index') }}">
                <div>
                    <i class="fa-solid fa-layer-group"></i>
                    <span>Gestion des Évaluations</span>
                </div>
            </a>
        </li>

        <li>
            <a href="{{ route('payments.index') }}">
                <div>
                    <i class="fa-solid fa-dollar-sign"></i>
                    <span>Payements</span>
                </div>
            </a>
        </li>

        <li>
            <a href="{{ route('admin.settings.index') }}">
                <div>
                    <i class="fa-solid fa-gear"></i>
                    <span>Paramètres</span>
                </div>
            </a>
        </li>
        <div style="height: 70px">

        </div>

        <li>
            <a href="{{ route('profile.show') }}">
                <div>
                    <i class="fa-solid fa-user"></i>
                    <span>Mon Profil</span>
                </div>
            </a>
        </li>
    </ul>
</div>

<script>
    const sidebar = document.getElementById('sidebar');
    const toggleBtn = document.getElementById('sidebarToggle');
    const links = sidebar.querySelectorAll('a');

    toggleBtn.addEventListener('click', () => {
        sidebar.classList.toggle('collapsed');
    });


    links.forEach(link => {
        if (link.href === window.location.href) {
            link.classList.add('active');
        }
    });
</script>
