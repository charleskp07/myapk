<div class="sidebar">
    <ul>

        <li>
            <a href="{{ route('dashboard') }}">
                <div>
                    <i class="fa fa-gauge"></i>
                    Tableau de bord
                </div>
            </a>
        </li>

        <li>
            <a href="{{ route('classrooms.index') }}">
                <div>
                    <i class="bi bi-door-open"></i>
                    Gestion des Salle de classes
                </div>
            </a>
        </li>
        
        <li>
            <a href="{{route('students.index')}}">
                <div>
                    <i class="bi bi-mortarboard-fill"></i>
                    Gestion des Apprenants
                </div>
            </a>
        </li>

        <li>
            <a href="{{ route('teachers.index') }}">
                <div>
                    <i class="fa-solid fa-person-chalkboard"></i>
                    Gestion des Enseignants
                </div>
            </a>
        </li>

        <li>
            <a href="{{ route('subjects.index') }}">
                <div>
                    <i class="fa-solid fa-book"></i>
                    Gestion des Matières
                </div>
            </a>
        </li>

        <li>
            <a href="{{ route('assignations.index') }}">
                <div>
                    <i class="fa-solid fa-scroll"></i>
                    Gestion des Assignations
                </div>
            </a>
        </li>


        <li>
            <a href="{{ route('evaluations.index') }}">
                <div>
                    <i class="fa-solid fa-layer-group"></i>
                    Gestion des Evaluations
                </div>
            </a>
        </li>

        <li>
            <a href="{{route('admin.settings.index')}}">
                <div>
                    <i class="fa-solid fa-gear"></i>
                    Parametres
                </div>
            </a>          
        </li>
        <div>
            <li>
                <a href="{{ route('profile.show') }}">
                    <div>
                        <i class="fa-solid fa-user"></i>
                        Mon Profil
                    </div>
                </a>
            </li>
        </div>
    </ul>

</div>
