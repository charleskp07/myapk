@extends('layouts.authlayout')

@section('content')
    <div class="full-banner">
        <div class="text-center">
            
            <div class="h1-title">
                Logiciel de gestion d'administration scolaire.
            </div>
            <a href="{{ route('login') }}" class="button button-primary m-auto">
                Se connecter à son compte
                <i class="fa-solid fa-arrow-right"></i>
            </a>
        </div>
    </div>
@endsection