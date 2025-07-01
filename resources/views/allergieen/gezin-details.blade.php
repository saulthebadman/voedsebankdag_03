<x-app-layout>
    <x-slot name="header">
        <h2 class="heading-responsive">
            <span class="desktop-only">{{ __('Allergieën in het gezin: ') . $gezin->naam }}</span>
            <span class="mobile-only">{{ __('Allergieën: ') . $gezin->naam }}</span>
        </h2>
    </x-slot>

    <div class="py-6 sm:py-12">
        <div class="container-responsive">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-responsive text-gray-900">
                    
                    <!-- Navigatie terug -->
                    <div class="mb-6">
                        <a href="{{ route('allergieen.index') }}" class="btn-secondary-responsive w-full sm:w-auto">
                            ← <span class="ml-1">Terug naar overzicht</span>
                        </a>
                    </div>

                    <!-- Success message -->
                    @if(session('success'))
                        <div id="success-message" class="alert-success-responsive mb-6">
                            <div class="flex items-center">
                                <div class="flex items-center justify-center w-6 h-6 sm:w-8 sm:h-8 bg-green-100 rounded-full mr-3 flex-shrink-0">
                                    <svg class="w-3 h-3 sm:w-4 sm:h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                </div>
                                <p class="text-green-700 font-medium text-sm sm:text-base">{{ session('success') }}</p>
                            </div>
                        </div>
                        
                        <script>
                            // Auto hide success message after 3 seconds (Wireframe-05)
                            setTimeout(function() {
                                const message = document.getElementById('success-message');
                                if (message) {
                                    message.style.transition = 'opacity 0.5s ease-out';
                                    message.style.opacity = '0';
                                    setTimeout(() => message.remove(), 500);
                                }
                            }, 3000);
                        </script>
                    @endif

                    <!-- Gezin informatie -->
                    <div class="mb-6 p-4 bg-blue-50 rounded-lg">
                        <div class="space-y-2 sm:space-y-1">
                            <p class="text-blue-800 text-sm sm:text-base"><strong>Gezinsnaam:</strong> {{ $gezin->naam }}</p>
                            <p class="text-blue-800 text-sm sm:text-base"><strong>Omschrijving:</strong> {{ $gezin->omschrijving }}</p>
                            <p class="text-blue-800 text-sm sm:text-base"><strong>Totaal aantal personen:</strong> {{ $gezin->totaal_aantal_personen }}</p>
                        </div>
                    </div>

                    <!-- Allergieën per persoon tabel (Wireframe-03) -->
                    <div>
                        <h3 class="text-lg font-medium mb-4">Personen met allergieën</h3>
                        
                        @if($personenMetAllergieen->isEmpty())
                            <div class="text-center py-8">
                                <p class="text-gray-500">Er zijn geen personen met allergieën gevonden in dit gezin.</p>
                            </div>
                        @else
                            <div class="overflow-x-auto">
                                <table class="min-w-full bg-white border border-gray-200 rounded-lg">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b">Naam</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b">Type Persoon</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b">Gezinsrol</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b">Allergie</th>
                                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider border-b">Wijzig Allergie</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200">
                                        @foreach($personenMetAllergieen as $persoon)
                                            <tr class="hover:bg-gray-50">
                                                <td class="px-6 py-4">
                                                    <div class="font-semibold text-gray-900">{{ $persoon->volledige_naam ?? 'Onbekende naam' }}</div>
                                                </td>
                                                <td class="px-6 py-4 text-sm text-gray-900">
                                                    {{ ucfirst($persoon->type_persoon ?? 'Klant') }}
                                                </td>
                                                <td class="px-6 py-4 text-sm text-gray-900">
                                                    @if(isset($persoon->is_vertegenwoordiger) && $persoon->is_vertegenwoordiger)
                                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                            Vertegenwoordiger
                                                        </span>
                                                    @else
                                                        <span class="text-gray-500">Gezinslid</span>
                                                    @endif
                                                </td>
                                                <td class="px-6 py-4">
                                                    <div class="flex flex-wrap gap-1">
                                                        @if(isset($persoon->allergieen) && $persoon->allergieen && $persoon->allergieen->isNotEmpty())
                                                            @foreach($persoon->allergieen as $allergie)
                                                                <span class="inline-block px-2 py-1 text-xs font-semibold text-white bg-red-500 rounded-full">
                                                                    {{ $allergie->naam ?? 'Onbekende allergie' }}
                                                                    @if(isset($allergie->anafylactisch_risico) && $allergie->anafylactisch_risico === 'hoog')
                                                                        ⚠️
                                                                    @endif
                                                                </span>
                                                            @endforeach
                                                        @else
                                                            <span class="text-gray-500 text-sm">Geen allergieën bekend</span>
                                                        @endif
                                                    </div>
                                                </td>                                <td class="px-6 py-4 text-center">
                                    <div class="flex flex-col space-y-1">
                                        @if(isset($persoon->allergieen) && $persoon->allergieen && $persoon->allergieen->isNotEmpty())
                                            @foreach($persoon->allergieen as $allergie)
                                                <a href="{{ route('allergieen.edit-persoon-allergie', [$persoon->id ?? 0, $allergie->id ?? 0]) }}" 
                                                   class="inline-flex items-center px-2 py-1 bg-orange-600 border border-transparent rounded text-xs text-white hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 transition ease-in-out duration-150"
                                                   title="Wijzig {{ $allergie->naam }} allergie">
                                                    ✏️ {{ $allergie->naam }}
                                                </a>
                                            @endforeach
                                        @else
                                            <span class="text-gray-500 text-sm">Geen allergieën</span>
                                        @endif
                                    </div>
                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                        
                        <!-- Terug en Home buttons -->
                        <div class="flex justify-end space-x-3 mt-6">
                            <a href="{{ route('allergieen.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                ← Terug
                            </a>
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
