<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <h2 class="text-2xl font-bold text-green-600 border-b-2 border-green-600 pb-1">
                Overzicht gezinnen met allergieën
            </h2>
            
            <!-- Compacte filter rechts -->
            <div class="flex items-center gap-3">
                <form method="GET" action="{{ route('allergieen.index') }}" class="flex items-center gap-3" id="filterForm">
                    <select name="allergie_id" id="allergie_id" class="px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 min-w-48">
                        <option value="">Selecteer Allergie</option>
                        @foreach($allergieen as $allergie)
                            <option value="{{ $allergie->id }}" {{ request('allergie_id') == $allergie->id ? 'selected' : '' }}>{{ $allergie->naam }}</option>
                        @endforeach
                    </select>
                    <button type="submit" id="filterBtn" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 transition-colors">
                        Toon Gezinnen
                    </button>
                </form>
            </div>
        </div>
    </x-slot>

    <div class="py-6 sm:py-12">
        <div class="container-responsive">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-responsive text-gray-900">
                    
                    <!-- Overzicht van alle gezinnen met allergieën -->
                    <div>
                        @if(request('allergie_id') && $geselecteerdeAllergie)
                            <h3 class="subheading-responsive mb-4">Gezinnen met allergie "{{ $geselecteerdeAllergie->naam }}"</h3>
                        @else
                            <h3 class="subheading-responsive mb-4">Alle gezinnen met voedselallergieën</h3>
                        @endif
                        
                        @if($bericht)
                            <!-- Desktop Table View met melding -->
                            <div class="table-responsive desktop-only">
                                <table class="min-w-full bg-white border border-gray-200 rounded-lg">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b">Naam</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b">Omschrijving</th>
                                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider border-b">Volwassenen</th>
                                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider border-b">Kinderen</th>
                                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider border-b">Baby's</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b">Vertegenwoordiger</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b">Allergie Details</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Gele melding rij -->
                                        <tr>
                                            <td colspan="7" class="px-6 py-8">
                                                <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4">
                                                    <div class="flex justify-center items-center">
                                                        <div class="flex-shrink-0">
                                                            <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                                                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                                            </svg>
                                                        </div>
                                                        <div class="ml-3">
                                                            <p class="text-sm text-yellow-700 font-medium">
                                                                {{ $bericht }}
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- Reset filter knop in tabel -->
                                                <div class="text-center mt-4">
                                                    <a href="{{ route('allergieen.index') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                                                        Toon alle gezinnen
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <!-- Mobile melding -->
                            <div class="mobile-only">
                                <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-4">
                                    <div class="flex justify-center items-center">
                                        <div class="flex-shrink-0">
                                            <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-sm text-yellow-700 font-medium">
                                                {{ $bericht }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="text-center">
                                    <a href="{{ route('allergieen.index') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                                        Toon alle gezinnen
                                    </a>
                                </div>
                            </div>
                        @elseif($gezinnenMetAllergieen->isEmpty())
                            <div class="text-center py-8">
                                <p class="text-gray-500 text-sm sm:text-base">Er zijn geen gezinnen met allergieën gevonden.</p>
                            </div>
                            
                            <!-- Home button voor lege lijst -->
                            <div class="flex justify-center sm:justify-end mt-6">
                                <a href="{{ route('dashboard') }}" class="btn-primary-responsive w-full sm:w-auto">
                                    🏠 <span class="ml-2">Home</span>
                                </a>
                            </div>
                        @else
                            <!-- Desktop Table View -->
                            <div class="table-responsive desktop-only">
                                <table class="min-w-full bg-white border border-gray-200 rounded-lg">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b">Naam</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b">Omschrijving</th>
                                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider border-b">Volwassenen</th>
                                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider border-b">Kinderen</th>
                                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider border-b">Baby's</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b">Vertegenwoordiger</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b">Allergie Details</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200">
                                        @foreach($gezinnenMetAllergieen as $gezin)
                                            <tr class="hover:bg-gray-50">
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="font-semibold text-gray-900">{{ $gezin->naam }}</div>
                                                    <div class="text-sm text-gray-500">{{ $gezin->code ?? 'Geen code' }}</div>
                                                </td>
                                                <td class="px-6 py-4">
                                                    <div class="text-sm text-gray-900">{{ $gezin->omschrijving ?? 'Gezin met allergieën' }}</div>
                                                </td>
                                                <td class="px-6 py-4 text-center">
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                        {{ $gezin->aantal_volwassenen ?? 0 }}
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4 text-center">
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                        {{ $gezin->aantal_kinderen ?? 0 }}
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4 text-center">
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                                        {{ $gezin->aantal_babys ?? 0 }}
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4">
                                                    @php
                                                        $vertegenwoordiger = null;
                                                        if (isset($gezin->personen) && is_object($gezin->personen) && method_exists($gezin->personen, 'where')) {
                                                            $vertegenwoordiger = $gezin->personen->where('is_vertegenwoordiger', true)->first();
                                                        }
                                                    @endphp
                                                    @if($vertegenwoordiger)
                                                        <div class="text-sm font-medium text-gray-900">
                                                            {{ $vertegenwoordiger->voornaam ?? '' }} {{ $vertegenwoordiger->achternaam ?? '' }}
                                                        </div>
                                                    @else
                                                        <span class="text-sm text-gray-500">Contactgegevens beschikbaar</span>
                                                    @endif
                                                </td>
                                                <td class="px-6 py-4">
                                                    <div class="text-center">
                                                        <a href="{{ route('allergieen.gezin-details', $gezin->id) }}" 
                                                           class="btn-primary-responsive"
                                                           title="Bekijk allergie details">
                                                            📖 Details
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <!-- Mobile Card View -->
                            <div class="mobile-only space-y-4">
                                @foreach($gezinnenMetAllergieen as $gezin)
                                    <div class="card-responsive border border-gray-200 rounded-lg p-4">
                                        <div class="flex justify-between items-start mb-3">
                                            <div>
                                                <h4 class="font-semibold text-gray-900 text-lg">{{ $gezin->naam }}</h4>
                                                <p class="text-sm text-gray-500">{{ $gezin->code }}</p>
                                            </div>
                                            <a href="{{ route('allergieen.gezin-details', $gezin->id) }}" 
                                               class="btn-primary-responsive text-xs"
                                               title="Bekijk allergie details">
                                                📖
                                            </a>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <p class="text-sm text-gray-700">{{ $gezin->omschrijving }}</p>
                                        </div>
                                        
                                        <div class="grid grid-cols-3 gap-2 mb-3">
                                            <div class="text-center">
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                    {{ $gezin->aantal_volwassenen }} Volw.
                                                </span>
                                            </div>
                                            <div class="text-center">
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    {{ $gezin->aantal_kinderen }} Kind.
                                                </span>
                                            </div>
                                            <div class="text-center">
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                                    {{ $gezin->aantal_babys }} Baby's
                                                </span>
                                            </div>
                                        </div>
                                        
                                        @php
                                            $vertegenwoordiger = $gezin->personen->where('is_vertegenwoordiger', true)->first();
                                        @endphp
                                        @if($vertegenwoordiger)
                                            <div class="text-sm">
                                                <span class="font-medium text-gray-600">Contact:</span>
                                                <span class="text-gray-900">{{ $vertegenwoordiger->volledige_naam }}</span>
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @endif
                        
                        <!-- Home button (rechts onder de tabel) -->
                        <div class="flex justify-center sm:justify-end mt-6">
                            <a href="{{ route('dashboard') }}" class="btn-primary-responsive w-full sm:w-auto">
                                🏠 <span class="ml-2">Home</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('filterForm');
        const filterBtn = document.getElementById('filterBtn');

        // Form submit handler
        form.addEventListener('submit', function(e) {
            // Disable button na submit om dubbele submits te voorkomen
            filterBtn.disabled = true;
            filterBtn.textContent = '⏳ Laden...';
        });
    });
    </script>
</x-app-layout>
