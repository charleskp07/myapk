@extends('layouts.base')

@section('base')
    @include('includes.sidebar')
    <div class="wrap-content">
        {{-- @include('includes.appbar') --}}
        <br /><br /><br /><br />
        @yield('content')
    </div>
@endsection

@section('js')
    @yield('js')
@endsection