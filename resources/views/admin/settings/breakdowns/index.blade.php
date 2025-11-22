@extends('layouts.authchecked')

@section('title', 'Découpages annuels')


@section('content')
    <a href="javascript:history.back()">Retour</a>
    <div>
        <h1>Découpages annuels</h1>

        <a href="{{ route('admin.breakdowns.create') }}">
            Créer un découpage annuel
        </a>
    </div>

    @if ($message = Session::get('success'))
        <p class="alert-success">{{ $message }}</p>
    @endif

    <br />

    @if ($breakdowns->isEmpty())
        <div style="text-align: center;">
            <p>--Aucun découpage annuel n'a été enregistré--</p><br />
            <img src="{{ asset('images/icons/trash-empty-svgrepo-com.png') }}" alt="" width="50">
        </div>
    @else
        <div>
            @foreach ($breakdowns as $breakdown)
                <span>{{ $breakdown->type }}</span> &nbsp;
                <span>{{ $breakdown->value }}</span> &nbsp;&nbsp;
                <a href="{{ route('admin.breakdowns.edit', $breakdown->id) }}">
                    <i class="fa-regular fa-pen-to-square"></i>
                    Modifier
                </a>
                {{-- <a href="{{ route('admin.breakdowns.show', $breakdown->id) }}">
                    <i class="fa-solid fa-list-ul"></i>
                    Details
                </a> --}}
                {{-- <form action="{{ route('admin.breakdowns.destroy', $breakdown->id) }}" method="post">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="detele-btn"
                        onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet barème ?')">
                        &nbsp;<i class="fas fa-trash"></i>Supprimer
                    </button>

                </form> --}}
                <br />
            @endforeach
        </div>
    @endif


@endsection
