@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h2 style="color:#1a7f37; font-size:2rem; text-decoration:underline;">Overzicht producten</h2>
    <div class="mb-3" style="max-width:400px;">
        <table class="table table-bordered mb-0">
            <tr><th style="width:50%">Naam:</th><td>{{ $leverancier->naam }}</td></tr>
            <tr><th>Leveranciernummer:</th><td>{{ $leverancier->leveranciernummer }}</td></tr>
            <tr><th>Leverancierstype:</th><td>{{ $leverancier->leverancier_type }}</td></tr>
        </table>
    </div>
    <table class="table table-bordered align-middle mt-3">
        <thead style="background:#f8f8f8;">
            <tr>
                <th>Naam</th>
                <th>Soort Allergie</th>
                <th>Barcode</th>
                <th>Houdbaarheidsdatum</th>
                <th>Wijzig Product</th>
            </tr>
        </thead>
        <tbody>
            @if($leverancier->products->isEmpty())
                <tr>
                    <td colspan="5" class="text-center bg-warning-subtle">Er zijn geen producten bekend voor deze leverancier</td>
                </tr>
                <tr>
                    <td colspan="4"></td>
                    <td class="text-center">
                        <a href="{{ route('leveranciers.edit', $leverancier) }}" class="btn btn-outline-secondary btn-sm">Klantgegevens wijzigen</a>
                    </td>
                </tr>
            @else
                @foreach($leverancier->products as $product)
                    <tr>
                        <form method="POST" action="{{ route('products.inlineUpdate', [$leverancier, $product]) }}">
                            @csrf
                            @method('PUT')
                            <td><input type="text" name="naam" value="{{ $product->naam }}" class="form-control form-control-sm" required></td>
                            <td><input type="text" name="soort_allergie" value="{{ $product->soort_allergie }}" class="form-control form-control-sm"></td>
                            <td><input type="text" name="barcode" value="{{ $product->barcode }}" class="form-control form-control-sm"></td>
                            <td>{{ $product->houdbaarheidsdatum }}</td>
                            <td class="text-center d-flex flex-column align-items-center" style="gap:4px;">
                                <a href="{{ route('products.edit', [$leverancier, $product]) }}" class="btn btn-link p-0" title="Wijzig houdbaarheidsdatum">
                                    <span style="font-size:1.2rem; color:#1a7f37;">&#9998;</span>
                                </a>
                                <button type="submit" class="btn btn-outline-primary btn-sm mt-1">Opslaan</button>
                            </td>
                        </form>
                    </tr>
                @endforeach
                <tr>
                    <td colspan="4"></td>
                    <td class="text-center">
                        <a href="{{ route('leveranciers.edit', $leverancier) }}" class="btn btn-outline-secondary btn-sm">Klantgegevens wijzigen</a>
                    </td>
                </tr>
            @endif
        </tbody>
    </table>
    <div class="d-flex justify-content-end mt-2" style="gap:10px;">
        <a href="{{ route('leveranciers.index') }}" class="btn btn-primary">terug</a>
        <a href="/" class="btn btn-primary">home</a>
    </div>
</div>
@endsection
