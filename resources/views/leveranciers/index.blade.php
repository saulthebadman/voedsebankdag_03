@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h2 style="color:#1a7f37; font-size:2rem; text-decoration:underline;">Overzicht Leveranciers</h2>
    <form method="GET" action="" class="d-flex align-items-center mb-3" style="gap:10px;">
        <select name="leverancier_type" class="form-select" style="max-width:260px;">
            <option value="">Selecteer Leverancierstype</option>
            @foreach($types as $t)
                <option value="{{ $t }}" @if(isset($type) && $type == $t) selected @endif>{{ $t }}</option>
            @endforeach
        </select>
        <button type="submit" class="btn btn-secondary">Toon Leveranciers</button>
        <a href="{{ route('leveranciers.create') }}" class="btn btn-success ms-2">Leverancier toevoegen</a>
    </form>
    @if(session('success'))
        <div class="alert alert-success fadeout" id="alert-success">{{ session('success') }}</div>
    @endif
    @if(session('deleted'))
        <div class="alert alert-danger fadeout" id="alert-deleted">{{ session('deleted') }}</div>
    @endif
    @if(session('updated'))
        <div class="alert alert-primary fadeout" id="alert-updated">{{ session('updated') }}</div>
    @endif
    <table class="table table-bordered align-middle">
        <thead style="background:#f8f8f8;">
            <tr>
                <th>Naam</th>
                <th>Contactpersoon</th>
                <th>Email</th>
                <th>Mobiel</th>
                <th>Leveranciernummer</th>
                <th>LeverancierType</th>
                <th>Product Details</th>
                <th>Verwijderen</th>
            </tr>
        </thead>
        <tbody>
            @forelse($leveranciers as $l)
                <tr>
                    <td>{{ $l->naam }}</td>
                    <td>{{ $l->contactpersoon }}</td>
                    <td>{{ $l->email }}</td>
                    <td>{{ $l->mobiel }}</td>
                    <td>{{ $l->leveranciernummer }}</td>
                    <td>{{ $l->leverancier_type }}</td>
                    <td class="text-center">
                        <a href="{{ route('leveranciers.show', $l) }}" class="btn btn-outline-info btn-sm" title="Product details">
                            <span style="font-size:1.3rem; color:#1a7f37; cursor:pointer;">&#128196;</span>
                        </a>
                    </td>
                    <td class="text-center">
                        <form method="POST" action="{{ route('leveranciers.destroy', $l) }}" style="display:inline;" onsubmit="return confirm('Weet je zeker dat je deze leverancier wilt verwijderen?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Verwijderen</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center bg-warning-subtle">Er zijn geen leveranciers bekend van het geselecteerde leverancierstype</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    <div class="d-flex justify-content-end mt-2" style="gap:10px;">
        <a href="/" class="btn btn-primary">Home</a>
    </div>
</div>
@endsection
