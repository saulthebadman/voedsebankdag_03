<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Gezinnen met allergie: ') . $allergie->naam }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    <!-- Navigatie terug -->
                    <div class="mb-6">
                        <a href="{{ route('allergieen.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            ← Terug naar overzicht
                        </a>
                    </div>

                    <!-- Filter sectie -->
                    <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                        <h3 class="text-lg font-medium mb-4">Filter op allergie</h3>
                        <form method="GET" action="{{ route('allergieen.filter') }}" class="flex items-end space-x-4">
                            <div class="flex-1">
                                <label for="allergie_id" class="block text-sm font-medium text-gray-700 mb-1">
                                    Selecteer allergie:
                                </label>
                                <select name="allergie_id" id="allergie_id" class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                                    <option value="">-- Kies een allergie --</option>
                                    @foreach($allergieen as $allergieOption)
                                        <option value="{{ $allergieOption->id }}" {{ $allergieOption->id == $allergie->id ? 'selected' : '' }}>
                                            {{ $allergieOption->naam }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    Toon Gezinnen
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Allergie informatie -->
                    <div class="mb-6 p-4 bg-blue-50 rounded-lg">
                        <h3 class="text-lg font-medium text-blue-900 mb-2">{{ $allergie->naam }}</h3>
                        <p class="text-blue-800 mb-2">{{ $allergie->omschrijving }}</p>
                        <p class="text-sm text-blue-700">
                            <strong>Anafylactisch risico:</strong> 
                            <span class="px-2 py-1 text-xs font-semibold rounded-full
                                @if($allergie->anafylactisch_risico === 'hoog') bg-red-100 text-red-800
                                @elseif($allergie->anafylactisch_risico === 'redelijk_hoog') bg-orange-100 text-orange-800
                                @elseif($allergie->anafylactisch_risico === 'laag') bg-yellow-100 text-yellow-800
                                @else bg-green-100 text-green-800
                                @endif">
                                {{ ucfirst(str_replace('_', ' ', $allergie->anafylactisch_risico)) }}
                            </span>
                        </p>
                    </div>

                    <!-- Resultaten -->
                    <div>
                        @if($bericht)
                            <!-- Scenario 2: Geen gezinnen gevonden -->
                            <div class="text-center py-12">
                                <div class="max-w-md mx-auto">
                                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6">
                                        <div class="flex items-center justify-center w-12 h-12 mx-auto mb-4 bg-yellow-100 rounded-full">
                                            <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                                            </svg>
                                        </div>
                                        <h3 class="text-lg font-medium text-yellow-800 mb-2">Geen resultaten</h3>
                                        <p class="text-yellow-700">{{ $bericht }}</p>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Terug en Home buttons voor scenario 2 -->
                            <div class="flex justify-end space-x-3 mt-6">
                                <a href="{{ route('allergieen.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    ← Terug
                                </a>
                                <a href="{{ route('dashboard') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    🏠 Home
                                </a>
                            </div>
                        @else
                            <!-- Scenario 1: Gezinnen gevonden -->
                            <h3 class="text-lg font-medium mb-4">
                                Gezinnen met allergie "{{ $allergie->naam }}" ({{ $gezinnenMetAllergie->count() }} {{ $gezinnenMetAllergie->count() === 1 ? 'gezin' : 'gezinnen' }})
                            </h3>
                            
                            <div class="overflow-x-auto">
                                <table class="min-w-full bg-white border border-gray-200 rounded-lg">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b">Naam</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b">Omschrijving</th>
                                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider border-b">Volwassenen</th>
                                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider border-b">Kinderen</th>
                                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider border-b">Baby's</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b">Vertegenwoordiger</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b">{{ $allergie->naam }} Details</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200">
                                        @foreach($gezinnenMetAllergie as $gezin)
                                            <tr class="hover:bg-gray-50">
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="font-semibold text-gray-900">{{ $gezin->naam }}</div>
                                                    <div class="text-sm text-gray-500">{{ $gezin->code }}</div>
                                                </td>
                                                <td class="px-6 py-4">
                                                    <div class="text-sm text-gray-900">{{ $gezin->omschrijving }}</div>
                                                </td>
                                                <td class="px-6 py-4 text-center">
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                        {{ $gezin->aantal_volwassenen }}
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4 text-center">
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                        {{ $gezin->aantal_kinderen }}
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4 text-center">
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                                        {{ $gezin->aantal_babys }}
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4">
                                                    @php
                                                        $vertegenwoordiger = $gezin->personen->where('is_vertegenwoordiger', true)->first();
                                                    @endphp
                                                    @if($vertegenwoordiger)
                                                        <div class="text-sm font-medium text-gray-900">{{ $vertegenwoordiger->volledige_naam }}</div>
                                                    @else
                                                        <span class="text-sm text-gray-500">Geen vertegenwoordiger</span>
                                                    @endif
                                                </td>
                                                <td class="px-6 py-4">
                                                    <div class="space-y-1">
                                                        @foreach($gezin->personen as $persoon)
                                                            <div class="text-xs bg-red-50 p-2 rounded border border-red-200">
                                                                <strong class="block text-gray-700">{{ $persoon->volledige_naam }}:</strong>
                                                                <div class="mt-1 flex flex-wrap gap-1">
                                                                    @foreach($persoon->allergieen as $persoonAllergie)
                                                                        <span class="inline-block px-2 py-1 text-xs font-semibold text-white 
                                                                            @if($persoonAllergie->id === $allergie->id) bg-red-600 
                                                                            @else bg-gray-400 
                                                                            @endif rounded-full">
                                                                            {{ $persoonAllergie->naam }}
                                                                            @if($persoonAllergie->anafylactisch_risico === 'hoog')
                                                                                ⚠️
                                                                            @endif
                                                                        </span>
                                                                    @endforeach
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                        
                                                        <!-- Boek-icoon voor details -->
                                                        <div class="mt-2 flex justify-end">
                                                            <a href="{{ route('allergieen.gezin-details', $gezin->id) }}" 
                                                               class="inline-flex items-center px-2 py-1 bg-blue-600 border border-transparent rounded text-xs text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150"
                                                               title="Bekijk allergie details">
                                                                📖 Details
                                                            </a>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                        
                        <!-- Terug en Home buttons (rechts onder de tabel) -->
                        @if(!$bericht)
                            <div class="flex justify-end space-x-3 mt-6">
                                <a href="{{ route('allergieen.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    ← Terug
                                </a>
                                <a href="{{ route('dashboard') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    🏠 Home
                                </a>
                            </div>
                        @endif
                      
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
