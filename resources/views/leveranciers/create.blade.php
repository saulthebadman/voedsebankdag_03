@extends('layouts.app')

@section('content')
<div class="container mt-5" style="max-width:600px;">
    <h2 style="color:#1a7f37; font-size:2rem; text-decoration:underline;">Leverancier toevoegen</h2>
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <form method="POST" action="{{ route('leveranciers.store') }}" class="mt-4">
        @csrf
        <div class="mb-3">
            <label class="form-label">Naam *</label>
            <input type="text" name="naam" class="form-control" value="{{ old('naam') }}">
        </div>
        <div class="mb-3">
            <label class="form-label">Contactpersoon</label>
            <input type="text" name="contactpersoon" class="form-control" value="{{ old('contactpersoon') }}">
        </div>
        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" value="{{ old('email') }}">
        </div>
        <div class="mb-3">
            <label class="form-label">Mobiel</label>
            <input type="text" name="mobiel" class="form-control" value="{{ old('mobiel') }}">
        </div>
        <div class="mb-3">
            <label class="form-label">Leveranciernummer *</label>
            <input type="text" name="leveranciernummer" class="form-control" value="{{ old('leveranciernummer') }}">
        </div>
        <div class="mb-3">
            <label class="form-label">LeverancierType *</label>
            <select name="leverancier_type" class="form-select">
                <option value="">Selecteer Leverancierstype</option>
                @foreach($types as $t)
                    <option value="{{ $t }}" @if(old('leverancier_type') == $t) selected @endif>{{ $t }}</option>
                @endforeach
            </select>
        </div>
        <div class="d-flex justify-content-end" style="gap:10px;">
            <a href="{{ route('leveranciers.index') }}" class="btn btn-secondary">Terug</a>
            <button type="submit" class="btn btn-success">Opslaan</button>
        </div>
    </form>
</div>
@endsection
