<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                ✏️ {{ __('Wijzig Product Houdbaarheidsdatum') }}
            </h2>
            <a href="{{ route('leveranciers.show', $leverancier) }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                ← Terug naar producten
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    <!-- Leverancier info -->
                    <div class="bg-blue-50 p-4 rounded-lg mb-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-2">🏢 Leverancier Informatie</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                            <div><strong>Naam:</strong> {{ $leverancier->naam }}</div>
                            <div><strong>Nummer:</strong> {{ $leverancier->leverancier_nummer ?? '-' }}</div>
                            <div><strong>Type:</strong> {{ $leverancier->leverancier_type ?? '-' }}</div>
                        </div>
                    </div>

                    <!-- Product info -->
                    <div class="bg-gray-50 p-4 rounded-lg mb-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-2">📦 Product Informatie</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                            <div><strong>Naam:</strong> {{ $product->naam }}</div>
                            <div><strong>Barcode:</strong> {{ $product->barcode ?? 'Geen barcode' }}</div>
                            <div><strong>Allergie:</strong> {{ $product->soort_allergie ?? 'Geen allergieën' }}</div>
                            <div><strong>Huidige Houdbaarheidsdatum:</strong> 
                                <span class="font-medium text-blue-600">
                                    {{ $product->houdbaarheidsdatum ? \Carbon\Carbon::parse($product->houdbaarheidsdatum)->format('d-m-Y') : 'Niet ingesteld' }}
                                </span>
                            </div>
                        </div>
                    </div>

                    @if ($errors->any())
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                            <strong class="font-bold">⚠️ Let op:</strong>
                            <ul class="mt-2 list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('products.update', [$leverancier, $product]) }}" class="space-y-6">
                        @csrf
                        @method('PUT')
                        
                        <div class="bg-yellow-50 p-4 rounded-lg border-l-4 border-yellow-400">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-yellow-800">Belangrijke informatie</h3>
                                    <p class="mt-1 text-sm text-yellow-700">
                                        De houdbaarheidsdatum mag maximaal 7 dagen worden verlengd ten opzichte van de huidige datum.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div>
                            <label for="houdbaarheidsdatum" class="block text-sm font-medium text-gray-700 mb-2">
                                📅 Nieuwe Houdbaarheidsdatum <span class="text-red-500">*</span>
                            </label>
                            <input 
                                type="date" 
                                id="houdbaarheidsdatum"
                                name="houdbaarheidsdatum" 
                                value="{{ old('houdbaarheidsdatum', $product->houdbaarheidsdatum ? \Carbon\Carbon::parse($product->houdbaarheidsdatum)->format('Y-m-d') : '') }}"
                                min="{{ \Carbon\Carbon::today()->format('Y-m-d') }}"
                                max="{{ $product->houdbaarheidsdatum ? \Carbon\Carbon::parse($product->houdbaarheidsdatum)->addDays(7)->format('Y-m-d') : \Carbon\Carbon::today()->addDays(7)->format('Y-m-d') }}"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('houdbaarheidsdatum') border-red-300 @enderror"
                                required
                                autofocus
                            >
                            @error('houdbaarheidsdatum')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-gray-500">
                                📌 Maximaal tot: {{ $product->houdbaarheidsdatum ? \Carbon\Carbon::parse($product->houdbaarheidsdatum)->addDays(7)->format('d-m-Y') : \Carbon\Carbon::today()->addDays(7)->format('d-m-Y') }}
                            </p>
                        </div>

                        <!-- Submit buttons -->
                        <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200">
                            <a 
                                href="{{ route('leveranciers.show', $leverancier) }}" 
                                class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-6 rounded transition duration-200"
                            >
                                ✕ Annuleren
                            </a>
                            <button 
                                type="submit" 
                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded transition duration-200"
                            >
                                ✓ Wijzig Houdbaarheidsdatum
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
