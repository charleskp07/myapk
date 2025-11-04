@extends('layouts.authchecked')

@section('title', 'Gestion des classes')

@section('content')
    <div>
        <h1>Gestion des salles de classe</h1>

        <a href="{{ route('classrooms.create') }}">
            Créer une salle de classe
        </a>
    </div>

    @if ($message = Session::get('success'))
        <p class="alert-success">{{ $message }}</p>
    @endif

    @if ($classrooms->isEmpty())
        <div style="text-align: center;">
            <p>--Aucune salle de classe n'a été enregistré--</p><br />
            <img src="{{ asset('images/icons/trash-empty-svgrepo-com.png') }}" alt="" width="50">
        </div>
    @else
        <table>
            <tbody>
                @foreach ($classrooms as $classroom)
                    <tr>
                        <td>
                            <span>{{ $classroom->name }} {{ $classroom->section }}</span>
                        </td>

                        <td>
                            <a href="{{ route('classrooms.edit', $classroom->id) }}">
                                <i class="fa-regular fa-pen-to-square"></i>
                                Modifier
                            </a>
                        </td>

                        <td>
                            <a href="{{ route('classrooms.show', $classroom->id) }}">
                                <i class="fa-solid fa-list-ul"></i>
                                Details
                            </a>
                        </td>

                        {{-- <td>
                            <form action="{{ route('classrooms.destroy', $classroom->id) }}" method="post">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="detele-btn"
                                    onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette classe ?')">
                                    &nbsp;<i class="fas fa-trash"></i>Supprimer
                                </button>

                            </form>
                        </td> --}}
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
    
@endsection
