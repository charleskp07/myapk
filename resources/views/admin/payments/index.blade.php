@extends('layouts.authchecked')

@section('title', 'Payements')

@section('content')
    <div>
        <h1>Payements</h1>

        <a href="{{ route('payments.create') }}">
           Enregistrer un payement
        </a>
    </div>

    @if ($message = Session::get('success'))
        <p class="alert-success">{{ $message }}</p>
    @endif

    @if ($payments->isEmpty())
        <div style="text-align: center;">
            <p>--Aucun payement n'a été enregistré--</p><br />
            <img src="{{ asset('images/icons/trash-empty-svgrepo-com.png') }}" alt="" width="50">
        </div>
    @else

        <p>Liste des payements reçu</p>
    @endif


@endsection