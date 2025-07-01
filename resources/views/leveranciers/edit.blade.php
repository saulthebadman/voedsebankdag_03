@extends('layouts.app')

@section('content')
<div class="container mt-5" style="max-width:600px;">
    <h2 style="color:#1a7f37; font-size:2rem; text-decoration:underline;">Klantgegevens wijzigen</h2>
    @if(session('success'))
        <div class="alert alert-success fadeout" id="alert-success">{{ session('success') }}</div>
    @endif
    @if(session('deleted'))
        <div class="alert alert-danger fadeout" id="alert-deleted">{{ session('deleted') }}</div>
    @endif
    @if(session('updated'))
        <div class="alert alert-primary fadeout" id="alert-updated">{{ session('updated') }}</div>
    @endif
    <form method="POST" action="{{ route('leveranciers.update', $leverancier) }}" class="mt-4">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label class="form-label">Naam *</label>
            <input type="text" name="naam" class="form-control" value="{{ old('naam', $leverancier->naam) }}">
        </div>
        <div class="mb-3">
            <label class="form-label">Contactpersoon</label>
            <input type="text" name="contactpersoon" class="form-control" value="{{ old('contactpersoon', $leverancier->contactpersoon) }}">
        </div>
        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" value="{{ old('email', $leverancier->email) }}">
        </div>
        <div class="mb-3">
            <label class="form-label">Mobiel</label>
            <input type="text" name="mobiel" class="form-control" value="{{ old('mobiel', $leverancier->mobiel) }}">
        </div>
        <div class="mb-3">
            <label class="form-label">Leveranciernummer *</label>
            <input type="text" name="leveranciernummer" class="form-control" value="{{ old('leveranciernummer', $leverancier->leveranciernummer) }}">
        </div>
        <div class="mb-3">
            <label class="form-label">LeverancierType *</label>
            <select name="leverancier_type" class="form-select">
                <option value="">Selecteer Leverancierstype</option>
                @foreach($types as $t)
                    <option value="{{ $t }}" @if(old('leverancier_type', $leverancier->leverancier_type) == $t) selected @endif>{{ $t }}</option>
                @endforeach
            </select>
        </div>
        <div class="d-flex justify-content-end" style="gap:10px;">
            <a href="{{ route('leveranciers.show', $leverancier) }}" class="btn btn-secondary">Terug</a>
            <button type="submit" class="btn btn-success">Opslaan</button>
        </div>
    </form>
</div>
@endsection
