<style>

    .sidebar {
        position: fixed;
        top: 0;
        left: 0;
        width: 240px;
        height: 100vh;
        background: #252627;
        color: #fff;
        display: flex;
        flex-direction: column;
        transition: width 0.3s ease;
        overflow-x: hidden;
        z-index: 999;
        border-radius: 0 20px 20px 0;
    }

    .sidebar.collapsed {
        width: 70px;
    }

    .sidebar ul {
        list-style: none;
        padding: 0;
        margin: 0;
        flex: 1;
    }

    .sidebar li {
        margin: 5px 0;
    }

    .sidebar a {
        text-decoration: none;
        color: #c7c7d2;
        display: block;
        padding: 12px 18px;
        font-size: 15px;
        transition: all 0.2s ease;
        border-radius: 20px;
    }

    .sidebar a div {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .sidebar a:hover {
        background: #292940;
        color: #fff;
    }

    .sidebar a.active {
        background: #0078ff;
        color: #fff;
    }


    #sidebarToggle {
        position: absolute;
        top: 15px;
        right: -18px;
        width: 35px;
        height: 35px;
        border-radius: 50%;
        background: #0078ff;
        color: #fff;
        border: none;
        cursor: pointer;
        transition: transform 0.3s ease;
    }

    .sidebar.collapsed #sidebarToggle {
        transform: rotate(180deg);
    }


    .sidebar.collapsed a div span {
        display: none;
    }

    / @media (max-width: 768px) {
        .sidebar {
            position: fixed;
            width: 60px;
        }

        .sidebar:not(.collapsed) {
            width: 230px;
        }
    }
</style>

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
