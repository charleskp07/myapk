@extends('layouts.authchecked')

@section('title', 'Modifier les paramètres généraux de l’établissement')

@section('content')
    <div class="dashboard-cover">
        <a href="javascript:history.back()">Retour</a>
        <div>
            <h2>Modifier les paramètres généraux de l'établissement</h2>
            <br />
            <p>
                Modifiez les informations ci-dessous puis cliquez sur <b>Enregistrer les modifications</b>.
            </p>
            <br />

       
            @if ($errors->any())
                <ul class="form-errors-list">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <br />
            @endif

     
            @if ($message = Session::get('success'))
                <p class="alert-success">{{ $message }}</p>
                <br />
            @endif

          
            <form action="{{ route('admin.schoolsetting.update', $schoolsetting->id) }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="input-cover">
                    <label for="name">Nom de l'établissement *</label>
                    <input type="text" id="name" name="name" value="{{ old('name', $schoolsetting->name) }}"
                        placeholder="Ex : Collège La Réussite">
                </div>

                <div class="input-cover">
                    <label for="address">Adresse </label>
                    <input type="text" id="address" name="address"
                        value="{{ old('address', $schoolsetting->address) }}" placeholder="Ex : Lomé, quartier Tokoin...">
                </div>

                <div class="input-cover">
                    <label for="email">Email de contact </label>
                    <input type="email" id="email" name="email" value="{{ old('email', $schoolsetting->email) }}"
                        placeholder="Ex : contact@ecole.tg">
                </div>

                <div class="input-cover">
                    <label for="phone">Téléphone </label>
                    <input type="tel" id="phone" name="phone" value="{{ old('phone', $schoolsetting->phone) }}"
                        placeholder="Ex : +228 90 00 00 00">
                </div>

                <div class="input-cover">
                    <label for="principal">Nom du Directeur/Proviseur </label>
                    <input type="text" id="principal" name="principal"
                        value="{{ old('principal', $schoolsetting->principal) }}" placeholder="Ex : M. Agbeko Kossi">
                </div>

                <div class="input-cover">
                    <label for="academic_year">Année académique </label>
                    <input type="text" id="academic_year" name="academic_year"
                        value="{{ old('academic_year', $schoolsetting->academic_year) }}" placeholder="Ex : 2025-2026">
                </div>

                <div class="input-cover">
                    <label for="logo">Logo de l'établissement </label>
                    <input type="file" id="logo" name="logo" accept="image/png,image/jpg,image/jpeg">
                    @if ($schoolsetting->logo)
                        <div style="margin-top: 10px;">
                            <img src="{{ asset('storage/' . $schoolsetting->logo) }}" alt="Logo actuel" width="100"
                                style="border-radius: 10px;">
                            <p style="font-size: 14px; color: gray;">Logo actuel</p>
                        </div>
                    @endif
                </div>

                <div style="display:flex; gap:10px; margin-top:20px;">
                    <button type="submit" class="">Enregistrer les modifications</button>
                    <a href="javascript:history.back()" title="Retourner">Annuler</a>
                </div>
            </form>
        </div>
    </div>
@endsection
