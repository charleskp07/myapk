@extends('layouts.authchecked')

@section('title', 'Gestion des Matières')

@section('content')
    <div>
        <h1>Gestion des Matières</h1>

        <a href="{{ route('subjects.create') }}">
            Créer une matière
        </a>
    </div>

    @if ($message = Session::get('success'))
        <p class="alert-success">{{ $message }}</p>
    @endif

    <br />


    @if ($subjects->isEmpty())
        <div style="text-align: center;">
            <p>--Aucune matière n'a été enregistré--</p><br />
            <img src="{{ asset('images/icons/trash-empty-svgrepo-com.png') }}" alt="" width="50">
        </div>
    @else
        <div>
            @foreach ($subjects as $subject)
                <span>{{ $subject->name }}</span>
                <a href="{{route('subjects.edit', $subject->id)}}">
                    <i class="fa-regular fa-pen-to-square"></i>
                    Modifier
                </a>
                <a href="{{route('subjects.show', $subject->id)}}">
                    <i class="fa-solid fa-list-ul"></i>
                    Details
                </a>
                {{-- <form action="{{ route('subjects.destroy', $subject->id) }}" method="post">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="detele-btn"
                        onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette Matière ?')">
                        &nbsp;<i class="fas fa-trash"></i>Supprimer
                    </button>

                </form> --}}
                <br />
            @endforeach
        </div>
    @endif


@endsection
