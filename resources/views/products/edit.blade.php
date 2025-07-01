@extends('layouts.app')

@section('content')
<div class="container mt-5" style="max-width:500px;">
    <h2 style="color:#1a7f37; font-size:2rem; text-decoration:underline;">Wijzig Product</h2>
    @if(session('success'))
        <div class="alert alert-success fadeout" id="alert-success">{{ session('success') }}</div>
    @endif
    @if(session('deleted'))
        <div class="alert alert-danger fadeout" id="alert-deleted">{{ session('deleted') }}</div>
    @endif
    @if(session('updated'))
        <div class="alert alert-primary fadeout" id="alert-updated">{{ session('updated') }}</div>
    @endif
    <form method="POST" action="{{ route('products.update', [$leverancier, $product]) }}" class="mt-4">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label class="form-label fw-bold">Houdbaarheidsdatum:</label>
            <input type="date" name="houdbaarheidsdatum" class="form-control" value="{{ old('houdbaarheidsdatum', $product->houdbaarheidsdatum) }}">
        </div>
        <div class="d-flex justify-content-end" style="gap:10px;">
            <a href="{{ route('leveranciers.show', $leverancier) }}" class="btn btn-primary">Terug</a>
            <button type="submit" class="btn btn-secondary">Wijzig Houdbaarheidsdatum</button>
        </div>
    </form>
</div>
@endsection
