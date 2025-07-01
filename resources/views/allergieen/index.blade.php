<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Overzicht Gezinsallergieën') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
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
                                    @foreach($allergieen as $allergie)
                                        <option value="{{ $allergie->id }}">{{ $allergie->naam }}</option>
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

                    <!-- Overzicht van alle gezinnen met allergieën -->
                    <div>
                        <h3 class="text-lg font-medium mb-4">Alle gezinnen met voedselallergieën</h3>
                        
                        @if($gezinnenMetAllergieen->isEmpty())
                            <div class="text-center py-8">
                                <p class="text-gray-500">Er zijn geen gezinnen met allergieën gevonden.</p>
                            </div>
                            
                            <!-- Home button voor lege lijst -->
                            <div class="flex justify-end mt-6">
                                <a href="{{ route('dashboard') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    🏠 Home
                                </a>
                            </div>
                        @else
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
                                                        <!-- Boek-icoon voor details (Wireframe-02) -->
                                                        <a href="{{ route('allergieen.gezin-details', $gezin->id) }}" 
                                                           class="inline-flex items-center px-3 py-2 bg-blue-600 border border-transparent rounded text-sm text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150"
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
                        @endif
                        
                        <!-- Home button (rechts onder de tabel) -->
                        <div class="flex justify-end mt-6">
                            <a href="{{ route('dashboard') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                🏠 Home
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
