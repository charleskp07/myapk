@extends('layouts.authchecked')

@section('title', 'Paramètres de l\'etablissement')


@section('content')
    <h1>
        Paramètres géneraux de l'etablissement
    </h1>

    <br />

    @if ($schoolsettings->isEmpty() && $schoolsettings->count() <= 1)
        <div>
            <a href="{{ route('admin.schoolsetting.create') }}">
                Mettre en place des paramètres
            </a>
        </div>
    @else
        @foreach ($schoolsettings as $schoolsetting)
            <div>
                <p>
                    <b>Nom de l'etablissement :</b> {{ $schoolsetting->name }}
                </p>

                <p>
                    <b>Adresse :</b> {{ $schoolsetting->address }}
                </p>

                <p>
                    <b>Email :</b> {{ $schoolsetting->email }}
                </p>

                <p>
                    <b>Telephone :</b> {{ $schoolsetting->phone }}
                </p>

                <p>
                    <b>Proviseur :</b> {{ $schoolsetting->principal }}
                </p>

                <p>
                    <b>Année academique :</b> {{ $schoolsetting->academic_year }}
                </p>

                <div>
                    <a href="{{ route('admin.schoolsetting.create', $schoolsetting->id) }}">
                        <i class="fa-solid fa-pen-to-square"></i>
                        Modifier
                    </a>
                </div>
            </div>
        @endforeach
    @endif

@endsection
