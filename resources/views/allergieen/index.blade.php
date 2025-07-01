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
                        @else
                            <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                                @foreach($gezinnenMetAllergieen as $gezin)
                                    <div class="bg-white border border-gray-200 rounded-lg shadow-sm p-4">
                                        <div class="mb-3">
                                            <h4 class="font-semibold text-lg text-gray-900">{{ $gezin->naam }}</h4>
                                            <p class="text-sm text-gray-600">Code: {{ $gezin->code }}</p>
                                            <p class="text-sm text-gray-600">{{ $gezin->omschrijving }}</p>
                                            <p class="text-sm text-gray-600">Totaal personen: {{ $gezin->totaal_aantal_personen }}</p>
                                        </div>

                                        <div class="space-y-2">
                                            <h5 class="font-medium text-sm text-gray-700">Personen met allergieën:</h5>
                                            @foreach($gezin->personen as $persoon)
                                                @if($persoon->allergieen->isNotEmpty())
                                                    <div class="text-sm bg-gray-50 p-2 rounded">
                                                        <strong>{{ $persoon->volledige_naam }}</strong>
                                                        <div class="mt-1">
                                                            @foreach($persoon->allergieen as $allergie)
                                                                <span class="inline-block px-2 py-1 text-xs font-semibold text-white bg-red-500 rounded-full mr-1 mb-1">
                                                                    {{ $allergie->naam }}
                                                                    @if($allergie->anafylactisch_risico === 'hoog')
                                                                        ⚠️
                                                                    @endif
                                                                </span>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                @endif
                                            @endforeach
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
