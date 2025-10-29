@extends('layouts.authchecked')

@section('title', 'Details d\'une classe')

@section('content')
    <h1>Detais de la {{$classroom->name}} {{$classroom->section}}</h1>
@endsection