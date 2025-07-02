<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-bold text-green-600 border-b-2 border-green-600 pb-1">
            Overzicht producten
        </h2>
    </x-slot>

    <div class="py-6 sm:py-12">
        <div class="container-responsive">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-responsive text-gray-900">
                    
                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                            {{ session('success') }}
                        </div>
                    @endif

                    <!-- Leverancier informatie sectie -->
                    <div class="mb-8 space-y-4">
                        <div class="flex items-center gap-8">
                            <div class="flex items-center gap-4">
                                <span class="text-sm font-medium text-gray-700 w-32">Naam:</span>
                                <span class="text-sm text-gray-900">{{ $leverancier->naam ?? '~~~~' }}</span>
                            </div>
                        </div>
                        
                        <div class="flex items-center gap-8">
                            <div class="flex items-center gap-4">
                                <span class="text-sm font-medium text-gray-700 w-32">Leveranciernummer:</span>
                                <span class="text-sm text-gray-900">{{ $leverancier->leverancier_nummer ?? '~~~~' }}</span>
                            </div>
                        </div>
                        
                        <div class="flex items-center gap-8">
                            <div class="flex items-center gap-4">
                                <span class="text-sm font-medium text-gray-700 w-32">Leveranciertype:</span>
                                <span class="text-sm text-gray-900">{{ $leverancier->leverancier_type ?? '~~~~' }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Producten tabel -->
                    <div>
                        @if($leverancier->products->isEmpty())
                            <!-- Desktop Table View met melding -->
                            <div class="table-responsive desktop-only">
                                <table class="min-w-full bg-white border border-gray-200 rounded-lg">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b">Naam</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b">Soort Allergie</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b">Barcode</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b">Houdbaarheidsdatum</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b">Wijzig Product</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td colspan="5" class="px-6 py-8 text-center">
                                                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6">
                                                    <div class="flex flex-col items-center justify-center space-y-4">
                                                        <div class="text-yellow-600">
                                                            <p class="text-lg font-medium">Er zijn nog geen producten geregistreerd voor deze leverancier</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <!-- Mobile melding -->
                            <div class="mobile-only">
                                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6 text-center">
                                    <div class="text-yellow-600">
                                        <p class="text-lg font-medium">Er zijn nog geen producten geregistreerd voor deze leverancier</p>
                                    </div>
                                </div>
                            </div>
                        @else
                            <!-- Desktop Table View -->
                            <div class="table-responsive desktop-only">
                                <table class="min-w-full bg-white border border-gray-200 rounded-lg">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b">Naam</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b">Soort Allergie</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b">Barcode</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b">Houdbaarheidsdatum</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b">Wijzig Product</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($leverancier->products as $product)
                                            <tr class="hover:bg-gray-50">
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                    {{ $product->naam ?? '~~~~' }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                    {{ $product->soort_allergie ?? '~~~~' }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                    {{ $product->barcode ?? '~~~~' }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                    @if($product->houdbaarheidsdatum)
                                                        {{ \Carbon\Carbon::parse($product->houdbaarheidsdatum)->format('d-m-Y') }}
                                                    @else
                                                        ~~~~
                                                    @endif
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                                    <a href="{{ route('products.edit', [$leverancier, $product]) }}" class="text-blue-600 hover:text-blue-900">
                                                        <svg class="w-5 h-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                        </svg>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <!-- Mobile Card View -->
                            <div class="mobile-only space-y-4">
                                @foreach($leverancier->products as $product)
                                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                                        <div class="space-y-2 text-sm">
                                            <div><strong>Naam:</strong> {{ $product->naam ?? 'Niet beschikbaar' }}</div>
                                            <div><strong>Allergie:</strong> {{ $product->soort_allergie ?? 'Niet beschikbaar' }}</div>
                                            <div><strong>Barcode:</strong> {{ $product->barcode ?? 'Niet beschikbaar' }}</div>
                                            <div><strong>Houdbaarheidsdatum:</strong> 
                                                @if($product->houdbaarheidsdatum)
                                                    {{ \Carbon\Carbon::parse($product->houdbaarheidsdatum)->format('d-m-Y') }}
                                                @else
                                                    Niet beschikbaar
                                                @endif
                                            </div>
                                        </div>
                                        
                                        <div class="mt-4 pt-3 border-t text-center">
                                            <a href="{{ route('products.edit', [$leverancier, $product]) }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                                Wijzig Product
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        <!-- Bottom buttons -->
                        <div class="flex justify-end gap-3 mt-8 pt-6 border-t">
                            <a href="{{ route('leveranciers.index') }}" 
                               class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                                terug
                            </a>
                            <a href="{{ route('dashboard') }}" 
                               class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                                home
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
