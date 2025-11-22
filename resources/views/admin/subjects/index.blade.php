@extends('layouts.authchecked')

@section('title', 'Gestion des Matières')

@section('css')
    <style>
        .detele-btn {
            border: none;
            background: none;
            color: red;
            text-decoration: underline;
            cursor: pointer;
        }
    </style>
@endsection

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
            <img src="{{ asset('images/icons/trash-empty-svgrepo-com.png') }}" alt="" width="50" class="empty-icon">
        </div>
    @else
        <div>
            <table>
                <tbody>
                    @foreach ($subjects as $subject)
                        <tr>
                            <td>
                                <span>{{ $subject->name }}</span>
                            </td>
    
                            <td>
                                <div style="display: flex">
    
                                    <a href="{{ route('subjects.edit', $subject->id) }}">
                                        <i class="fa-regular fa-pen-to-square"></i>
                                        Modifier
                                    </a>
                                    
                                    <form action="{{ route('subjects.destroy', $subject->id) }}" method="post">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="detele-btn"
                                            onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette Matière ?')">
                                            <i class="bi bi-trash3"></i></i>Supprimer
                                        </button>
    
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>



        </div>
    @endif


@endsection
