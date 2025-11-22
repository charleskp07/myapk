@extends('layouts.authchecked')

@section('title', 'Paramètres')


@section('content')
    <h1>
        Paramètres
    </h1>

    <nav>
        <ul style="list-style: none">
            <li>
                <a href="{{ route('admin.settings.notes.index') }}">
                    <div>
                        Paramétrage des notes
                    </div>
                </a>
            </li>

            <li>
                <a href="{{ route('admin.breakdowns.index') }}">
                    <div>
                        Paramétrage des decoupages annuels
                    </div>
                </a>
            </li>

            <li>
                <a href="{{ route('admin.schoolsetting.index') }}">
                    <div>
                        Paramétre généraux de l'etablissement
                    </div>
                </a>
            </li>
        </ul>

        
    </nav>
@endsection
