@extends('layouts.authchecked')

@section('title', 'Details d\'un(e) enseignat(e)')

@section('content')
    <h1>Detais de la {{ $teacher->first_name }} {{ $teacher->last_name }}</h1>

    <img src="{{ $teacher->photo_url }}" alt="Photo" class="img-fluid rounded" style="max-height: 200px;">
@endsection
