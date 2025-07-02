<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <h2 class="text-2xl font-bold text-green-600 border-b-2 border-green-600 pb-1">
                Overzicht Leveranciers
            </h2>
            
            <!-- Compacte filter rechts -->
            <div class="flex items-center gap-3">
                <form method="GET" action="{{ route('leveranciers.index') }}" class="flex items-center gap-3" id="filterForm">
                    <select name="leverancier_type" id="leverancier_type" class="px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 min-w-48">
                        <option value="">Selecteer Leveranciertype</option>
                        <option value="Bedrijf" {{ request('leverancier_type') == 'Bedrijf' ? 'selected' : '' }}>Bedrijf</option>
                        <option value="Instelling" {{ request('leverancier_type') == 'Instelling' ? 'selected' : '' }}>Instelling</option>
                        <option value="Overheid" {{ request('leverancier_type') == 'Overheid' ? 'selected' : '' }}>Overheid</option>
                        <option value="Particulier" {{ request('leverancier_type') == 'Particulier' ? 'selected' : '' }}>Particulier</option>
                        <option value="Donor" {{ request('leverancier_type') == 'Donor' ? 'selected' : '' }}>Donor</option>
                    </select>
                    <button type="submit" id="filterBtn" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 transition-colors">
                        Toon Leveranciers
                    </button>
                </form>
            </div>
        </div>
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

                    <!-- Overzicht van alle leveranciers -->
                    <div>
                        @if(request('leverancier_type'))
                            <h3 class="subheading-responsive mb-4">Leveranciers van type "{{ request('leverancier_type') }}"</h3>
                        @else
                            <h3 class="subheading-responsive mb-4">Alle leveranciers</h3>
                        @endif
                        
                        @if($leveranciers->isEmpty())
                            <!-- Desktop Table View met melding -->
                            <div class="table-responsive desktop-only">
                                <table class="min-w-full bg-white border border-gray-200 rounded-lg">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b">Naam</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b">Contactpersoon</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b">Email</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b">Mobiel</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b">Leveranciernummer</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b">LeverancierType</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b">Product Details</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td colspan="7" class="px-6 py-8 text-center">
                                                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6">
                                                    <div class="flex flex-col items-center justify-center space-y-4">
                                                        <div class="text-yellow-600">
                                                            @if(request('leverancier_type'))
                                                                <p class="text-lg font-medium">Er zijn geen leveranciers bekend van het geselecteerde leveranciertype</p>
                                                            @else
                                                                <p class="text-lg font-medium">Er zijn nog geen leveranciers geregistreerd</p>
                                                            @endif
                                                        </div>
                                                        @if(request('leverancier_type'))
                                                            <a href="{{ route('leveranciers.index') }}" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                                                                Toon alle leveranciers
                                                            </a>
                                                        @endif
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
                                        @if(request('leverancier_type'))
                                            <p class="text-lg font-medium mb-4">Er zijn geen leveranciers bekend van het geselecteerde type</p>
                                            <a href="{{ route('leveranciers.index') }}" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                                                Toon alle leveranciers
                                            </a>
                                        @else
                                            <p class="text-lg font-medium">Er zijn nog geen leveranciers geregistreerd</p>
                                        @endif
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
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b">Contactpersoon</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b">Email</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b">Mobiel</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b">Leveranciernummer</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b">LeverancierType</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b">Product Details</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($leveranciers as $leverancier)
                                            <tr class="hover:bg-gray-50">
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="text-sm font-medium text-gray-900">{{ $leverancier->naam }}</div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                    {{ $leverancier->contact_persoon ?? '~~~~' }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                    @if($leverancier->contacts->first())
                                                        {{ $leverancier->contacts->first()->email ?? '~~~~' }}
                                                    @else
                                                        ~~~~
                                                    @endif
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                    @if($leverancier->contacts->first())
                                                        {{ $leverancier->contacts->first()->mobiel ?? '~~~~' }}
                                                    @else
                                                        ~~~~
                                                    @endif
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                    {{ $leverancier->leverancier_nummer ?? '~~~~' }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                    {{ $leverancier->leverancier_type ?? '~~~~' }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                                    <a href="{{ route('leveranciers.show', $leverancier) }}" class="text-blue-600 hover:text-blue-900">
                                                        <svg class="w-5 h-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
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
                                @foreach($leveranciers as $leverancier)
                                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                                        <div class="flex items-start justify-between mb-3">
                                            <div class="flex-1">
                                                <h3 class="text-lg font-semibold text-gray-900">{{ $leverancier->naam }}</h3>
                                                <p class="text-sm text-gray-600">{{ $leverancier->leverancier_type ?? 'Geen type' }}</p>
                                            </div>
                                        </div>
                                        
                                        <div class="space-y-2 text-sm">
                                            <div><strong>Contact:</strong> {{ $leverancier->contact_persoon ?? 'Niet beschikbaar' }}</div>
                                            <div><strong>Email:</strong> {{ $leverancier->contacts->first()->email ?? 'Niet beschikbaar' }}</div>
                                            <div><strong>Mobiel:</strong> {{ $leverancier->contacts->first()->mobiel ?? 'Niet beschikbaar' }}</div>
                                            <div><strong>Nummer:</strong> {{ $leverancier->leverancier_nummer ?? 'Niet beschikbaar' }}</div>
                                        </div>
                                        
                                        <div class="mt-4 pt-3 border-t text-center">
                                            <a href="{{ route('leveranciers.show', $leverancier) }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                                Bekijk Product Details
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
