<x-app-layout>
    <x-slot name="header">
        <h2 class="heading-responsive">
            {{ __('Overzicht Gezinsallergieën') }}
        </h2>
    </x-slot>

    <div class="py-6 sm:py-12">
        <div class="container-responsive">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-responsive text-gray-900">
                    
                    <!-- Filter sectie -->
                    <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                        <h3 class="subheading-responsive mb-4">Filter op allergie</h3>
                        <form method="GET" action="{{ route('allergieen.filter') }}" class="form-responsive">
                            <div class="form-group-responsive">
                                <label for="allergie_id" class="form-label-responsive">
                                    Selecteer allergie:
                                </label>
                                <div class="w-full sm:w-3/4">
                                    <select name="allergie_id" id="allergie_id" class="form-input-responsive">
                                        <option value="">-- Kies een allergie --</option>
                                        @foreach($allergieen as $allergie)
                                            <option value="{{ $allergie->id }}">{{ $allergie->naam }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="flex justify-start sm:justify-end mt-4">
                                <button type="submit" class="btn-primary-responsive w-full sm:w-auto">
                                    <span class="mobile-only">🔍</span>
                                    <span class="desktop-only">Toon Gezinnen</span>
                                    <span class="mobile-only ml-2">Zoeken</span>
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Overzicht van alle gezinnen met allergieën -->
                    <div>
                        <h3 class="subheading-responsive mb-4">Alle gezinnen met voedselallergieën</h3>
                        
                        @if($gezinnenMetAllergieen->isEmpty())
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
</x-app-layout>
