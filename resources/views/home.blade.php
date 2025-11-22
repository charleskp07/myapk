@php
    $school = \App\Models\SchoolSetting::first();
@endphp

@extends('layouts.authlayout')

@section('content')
    <div class="full-banner">
        <div class="text-center">
            
           <h1>Logiciel de gestion d'administration scolaire.</h1>

            <div style="max-width: 300px;">
                <img src="{{ $school?->logo ? URL::asset('storage/' . $school->logo) : "" }}" alt="">
            </div>

            <a href="{{ route('login') }}" class="button button-primary m-auto">
                Se connecter à son compte
                <i class="fa-solid fa-arrow-right"></i>
            </a>
        </div>
    </div>
@endsection