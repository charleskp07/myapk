@extends('layouts.authchecked')

@section('title', 'Details d\'un(e) apprenant(e)')

@section('content')
    <h1>Detais de la {{$student->first_name}} {{$student->last_name}}</h1>
@endsection