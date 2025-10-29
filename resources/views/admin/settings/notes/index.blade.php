@extends('layouts.authchecked')

@section('title', 'Paramètres des notes')


@section('content')
    <h1>
        Paramètres des notes
    </h1>

    <nav>
        <ul>
            <li>
                <a href="{{ route('admin.baremes.index') }}">
                    Paramètre des barèmes
                </a>
            </li>
        </ul>
    </nav>
@endsection
