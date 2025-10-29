@extends('layouts.authchecked')

@section('title', 'Barèmes')


@section('content')
    <div>
        <h1>Barèmes</h1>

        <a href="{{ route('admin.baremes.create') }}">
            Créer une barème
        </a>
    </div>

    @if ($message = Session::get('success'))
        <p class="alert-success">{{ $message }}</p>
    @endif

    <br />

    @if ($baremes->isEmpty())
        <div style="text-align: center;">
            <p>--Aucune barèmes n'a été enregistré--</p><br />
            <img src="{{ asset('images/icons/trash-empty-svgrepo-com.png') }}" alt="" width="50">
        </div>
    @else
        <div>
            @foreach ($baremes as $bareme)
                <span> Sur {{ $bareme->value }}</span>
                <a href="{{ route('admin.baremes.edit', $bareme->id) }}">
                    <i class="fa-regular fa-pen-to-square"></i>
                    Modifier
                </a>
                <a href="{{ route('admin.baremes.show', $bareme->id) }}">
                    <i class="fa-solid fa-list-ul"></i>
                    Details
                </a>
                {{-- <form action="{{ route('admin.baremes.destroy', $bareme->id) }}" method="post">
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
