@extends('layouts.authchecked')

@section('title', 'Paramètres de l\'etablissement')


@section('content')
    <a href="javascript:history.back()">Retour</a>
    <h1>
        Paramètres géneraux de l'etablissement
    </h1>

    <br />

    @if ($message = Session::get('success'))
        <p class="alert-success">{{ $message }}</p>
        <br />
    @endif

    @if ($schoolsettings->isEmpty() && $schoolsettings->count() <= 1)
        <div>
            <a href="{{ route('admin.schoolsetting.create') }}">
                Mettre en place des paramètres
            </a>
        </div>
    @else
        @foreach ($schoolsettings as $schoolsetting)
            <div>
                @if ($schoolsetting->logo)
                    <div style="margin: 20px 0;">
                        <p><b>Logo de l'etablissement :</b></p><br />
                        <img src="{{ asset('storage/' . $schoolsetting->logo) }}" alt="Logo de l'établissement" width="150"
                            style="border-radius: 10px; border: 1px solid #ddd; padding: 5px;">
                    </div>
                @else
                    <p>Aucun logo n'a encore été défini.</p>
                @endif
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
                    <a href="{{ route('admin.schoolsetting.edit', $schoolsetting->id) }}">
                        <i class="fa-solid fa-pen-to-square"></i>
                        Modifier
                    </a>
                </div>
            </div>
        @endforeach
    @endif

@endsection
