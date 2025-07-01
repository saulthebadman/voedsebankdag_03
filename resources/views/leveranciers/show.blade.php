<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                📦 {{ __('Overzicht Producten') }}
            </h2>
            <a href="{{ route('leveranciers.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                ← Terug naar leveranciers
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                    ✅ {{ session('success') }}
                </div>
            @endif

            <!-- Leverancier informatie -->
            <div class="bg-white rounded-lg shadow-sm border p-6 mb-8">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">🏢 Leverancier Informatie</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="bg-blue-50 p-3 rounded">
                        <div class="text-sm text-gray-600">Naam</div>
                        <div class="font-medium text-gray-900">{{ $leverancier->naam }}</div>
                    </div>
                    <div class="bg-green-50 p-3 rounded">
                        <div class="text-sm text-gray-600">Leveranciernummer</div>
                        <div class="font-medium text-gray-900">{{ $leverancier->leverancier_nummer ?? 'Niet ingesteld' }}</div>
                    </div>
                    <div class="bg-purple-50 p-3 rounded">
                        <div class="text-sm text-gray-600">Type</div>
                        <div class="font-medium text-gray-900">{{ $leverancier->leverancier_type ?? 'Niet ingesteld' }}</div>
                    </div>
                </div>
            </div>

            <!-- Producten tabel -->
            <div class="bg-white rounded-lg shadow overflow-hidden">
                @if($leverancier->products->isEmpty())
                    <div class="p-12 text-center">
                        <div class="text-4xl mb-4">📦</div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Geen producten gevonden</h3>
                        <p class="text-gray-600 mb-4">Er zijn nog geen producten geregistreerd voor deze leverancier.</p>
                        <a href="{{ route('leveranciers.edit', $leverancier) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            ⚙️ Leverancier Wijzigen
                        </a>
                    </div>
                @else
                    <!-- Desktop view -->
                    <div class="hidden md:block">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">NAAM</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">SOORT ALLERGIE</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">BARCODE</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">HOUDBAARHEIDSDATUM</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">WIJZIG PRODUCT</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($leverancier->products as $product)
                                    <tr class="hover:bg-gray-50">
                                        <form method="POST" action="{{ route('products.inlineUpdate', [$leverancier, $product]) }}" class="contents">
                                            @csrf
                                            @method('PUT')
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <input type="text" name="naam" value="{{ $product->naam }}" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm" required>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <input type="text" name="soort_allergie" value="{{ $product->soort_allergie }}" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm" placeholder="Geen allergieën">
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <input type="text" name="barcode" value="{{ $product->barcode }}" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm" placeholder="Geen barcode">
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900">
                                                    @if($product->houdbaarheidsdatum)
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                                            @if(\Carbon\Carbon::parse($product->houdbaarheidsdatum)->isPast()) 
                                                                bg-red-100 text-red-800
                                                            @elseif(\Carbon\Carbon::parse($product->houdbaarheidsdatum)->diffInDays(now()) <= 7)
                                                                bg-yellow-100 text-yellow-800
                                                            @else
                                                                bg-green-100 text-green-800
                                                            @endif">
                                                            📅 {{ \Carbon\Carbon::parse($product->houdbaarheidsdatum)->format('d-m-Y') }}
                                                        </span>
                                                    @else
                                                        <span class="text-gray-500">Niet ingesteld</span>
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex space-x-2">
                                                    <a href="{{ route('products.edit', [$leverancier, $product]) }}" 
                                                       class="text-blue-600 hover:text-blue-900 bg-blue-100 hover:bg-blue-200 px-2 py-1 rounded text-sm" 
                                                       title="Wijzig houdbaarheidsdatum">
                                                        📅 Houdbaarheid
                                                    </a>
                                                    <button type="submit" class="bg-green-500 hover:bg-green-700 text-white px-3 py-1 rounded text-sm">
                                                        💾 Opslaan
                                                    </button>
                                                </div>
                                            </td>
                                        </form>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Mobile view -->
                    <div class="md:hidden space-y-4 p-4">
                        @foreach($leverancier->products as $product)
                            <div class="bg-gray-50 rounded-lg p-4 border">
                                <form method="POST" action="{{ route('products.inlineUpdate', [$leverancier, $product]) }}" class="space-y-3">
                                    @csrf
                                    @method('PUT')
                                    
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Naam</label>
                                        <input type="text" name="naam" value="{{ $product->naam }}" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm" required>
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Allergie</label>
                                        <input type="text" name="soort_allergie" value="{{ $product->soort_allergie }}" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm" placeholder="Geen allergieën">
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Barcode</label>
                                        <input type="text" name="barcode" value="{{ $product->barcode }}" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm" placeholder="Geen barcode">
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Houdbaarheidsdatum</label>
                                        @if($product->houdbaarheidsdatum)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                                @if(\Carbon\Carbon::parse($product->houdbaarheidsdatum)->isPast()) 
                                                    bg-red-100 text-red-800
                                                @elseif(\Carbon\Carbon::parse($product->houdbaarheidsdatum)->diffInDays(now()) <= 7)
                                                    bg-yellow-100 text-yellow-800
                                                @else
                                                    bg-green-100 text-green-800
                                                @endif">
                                                📅 {{ \Carbon\Carbon::parse($product->houdbaarheidsdatum)->format('d-m-Y') }}
                                            </span>
                                        @else
                                            <span class="text-gray-500">Niet ingesteld</span>
                                        @endif
                                    </div>
                                    
                                    <div class="flex space-x-2 pt-2">
                                        <a href="{{ route('products.edit', [$leverancier, $product]) }}" class="flex-1 text-center bg-blue-500 hover:bg-blue-700 text-white px-3 py-2 rounded text-sm">
                                            📅 Wijzig Houdbaarheid
                                        </a>
                                        <button type="submit" class="flex-1 bg-green-500 hover:bg-green-700 text-white px-3 py-2 rounded text-sm">
                                            💾 Opslaan Wijzigingen
                                        </button>
                                    </div>
                                </form>
                            </div>
                        @endforeach
                    </div>

                    <!-- Leverancier wijzigen knop onderaan -->
                    <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
                        <div class="flex justify-center">
                            <a href="{{ route('leveranciers.edit', $leverancier) }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-6 rounded">
                                ⚙️ Leverancier Gegevens Wijzigen
                            </a>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Terug naar dashboard -->
            <div class="mt-8 text-center">
                <a href="{{ route('dashboard') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    🏠 Terug naar Dashboard
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
