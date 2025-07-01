<x-app-layout>
    <x-slot name="header">
        <h2 class="heading-responsive">
            <span class="desktop-only">{{ __('Allergie wijzigen voor ') . $persoon->volledige_naam }}</span>
            <span class="mobile-only">{{ __('Allergie wijzigen') }}</span>
        </h2>
    </x-slot>

    <div class="py-6 sm:py-12">
        <div class="container-responsive max-w-4xl">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-responsive text-gray-900">
                    
                    <!-- Navigatie terug -->
                    <div class="mb-6">
                        <a href="{{ route('allergieen.gezin-details', $persoon->gezin_id) }}" class="btn-secondary-responsive w-full sm:w-auto">
                            ← <span class="ml-1">Terug naar gezin details</span>
                        </a>
                    </div>

                    <!-- Mobile persoon info -->
                    <div class="mobile-only mb-4 p-3 bg-gray-50 rounded-lg">
                        <p class="text-sm font-medium text-gray-900">{{ $persoon->volledige_naam }}</p>
                    </div>

                    <!-- Error message -->
                    @if(session('error'))
                        <div class="alert-error-responsive mb-6">
                            <div class="flex items-center">
                                <div class="flex items-center justify-center w-6 h-6 sm:w-8 sm:h-8 bg-red-100 rounded-full mr-3 flex-shrink-0">
                                    <svg class="w-3 h-3 sm:w-4 sm:h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </div>
                                <p class="text-red-700 font-medium text-sm sm:text-base">{{ session('error') }}</p>
                            </div>
                        </div>
                    @endif

                    <!-- Waarschuwing voor hoog risico (Scenario 2 - Wireframe-06) -->
                    @if($waarschuwing)
                        <div class="alert-error-responsive mb-6">
                            <div class="flex items-start">
                                <div class="flex items-center justify-center w-6 h-6 sm:w-8 sm:h-8 bg-red-100 rounded-full mr-3 flex-shrink-0 mt-0.5">
                                    <svg class="w-3 h-3 sm:w-4 sm:h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-base sm:text-lg font-medium text-red-800 mb-1">⚠️ Medische waarschuwing</h3>
                                    <p class="text-red-700 text-sm sm:text-base">{{ $waarschuwing }}</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Persoon en gezin informatie -->
                    <div class="mb-6 p-4 bg-blue-50 rounded-lg">
                        <h3 class="text-lg font-medium text-blue-900 mb-2">{{ $persoon->volledige_naam }}</h3>
                        <p class="text-blue-800 mb-1"><strong>Gezin:</strong> {{ $persoon->gezin->naam }} ({{ $persoon->gezin->code }})</p>
                        <p class="text-blue-800 mb-1"><strong>Geboortedatum:</strong> {{ $persoon->geboortedatum->format('d-m-Y') }}</p>
                        @if($persoon->is_vertegenwoordiger)
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                Vertegenwoordiger
                            </span>
                        @endif
                    </div>

                    <!-- Huidige allergie info -->
                    <div class="mb-6 p-4 bg-yellow-50 rounded-lg">
                        <h4 class="text-md font-medium text-yellow-900 mb-2">Huidige allergie</h4>
                        <div class="flex items-center space-x-4">
                            <span class="inline-block px-3 py-2 text-sm font-semibold text-white bg-red-500 rounded-full">
                                {{ $huidigeAllergie->naam }}
                                @if($huidigeAllergie->anafylactisch_risico === 'hoog')
                                    ⚠️
                                @endif
                            </span>
                            <div class="text-yellow-800">
                                <p><strong>Omschrijving:</strong> {{ $huidigeAllergie->omschrijving }}</p>
                                <p><strong>Anafylactisch risico:</strong> 
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full
                                        @if($huidigeAllergie->anafylactisch_risico === 'hoog') bg-red-100 text-red-800
                                        @elseif($huidigeAllergie->anafylactisch_risico === 'redelijk_hoog') bg-orange-100 text-orange-800
                                        @elseif($huidigeAllergie->anafylactisch_risico === 'laag') bg-yellow-100 text-yellow-800
                                        @else bg-green-100 text-green-800
                                        @endif">
                                        {{ ucfirst(str_replace('_', ' ', $huidigeAllergie->anafylactisch_risico)) }}
                                    </span>
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Wijzig formulier (Wireframe-04) -->
                    <div class="mb-6">
                        <h4 class="text-md font-medium text-gray-900 mb-4">Allergie wijzigen</h4>
                        
                        <form method="POST" action="{{ route('allergieen.update-persoon-allergie', [$persoon->id, $huidigeAllergie->id]) }}" class="space-y-6" id="allergieWijzigForm">
                            @csrf
                            @method('PUT')
                            
                            <div>
                                <label for="nieuwe_allergie_id" class="block text-sm font-medium text-gray-700 mb-2">
                                    Selecteer nieuwe allergie: <span class="text-red-500">*</span>
                                </label>
                                <select name="nieuwe_allergie_id" id="nieuwe_allergie_id" required class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                                    <option value="">-- Kies een allergie --</option>
                                    @foreach($allergieen as $allergie)
                                        @if($allergie->id !== $huidigeAllergie->id)
                                            <option value="{{ $allergie->id }}" data-risico="{{ $allergie->anafylactisch_risico }}" {{ old('nieuwe_allergie_id') == $allergie->id ? 'selected' : '' }}>
                                                {{ $allergie->naam }} 
                                                ({{ ucfirst(str_replace('_', ' ', $allergie->anafylactisch_risico)) }} risico)
                                                @if($allergie->anafylactisch_risico === 'hoog') ⚠️ @endif
                                            </option>
                                        @endif
                                    @endforeach
                                </select>
                                @error('nieuwe_allergie_id')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                
                                <!-- Waarschuwing voor hoog risico allergie -->
                                <div id="hogeRisicoWaarschuwing" class="hidden mt-3 p-3 bg-red-50 border-l-4 border-red-400 rounded">
                                    <div class="flex">
                                        <div class="flex-shrink-0">
                                            <svg class="h-5 w-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                            </svg>
                                        </div>
                                        <div class="ml-3">
                                            <h3 class="text-sm font-medium text-red-800">⚠️ Hoog anafylactisch risico</h3>
                                            <p class="mt-1 text-sm text-red-700">Deze allergie heeft een hoog anafylactisch risico. Zorg ervoor dat de wijziging correct is.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Bevestiging checkbox voor wijziging -->
                            <div class="flex items-start space-x-3">
                                <input type="checkbox" id="bevestiging" name="bevestiging" value="1" required class="mt-1 h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                <label for="bevestiging" class="text-sm text-gray-700">
                                    Ik bevestig dat deze allergiewijziging correct is en dat de persoon/gezin hiervan op de hoogte is. <span class="text-red-500">*</span>
                                </label>
                            </div>
                            @error('bevestiging')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror

                            <div class="flex items-center space-x-4">
                                <button type="submit" id="submitBtn" disabled class="inline-flex items-center px-4 py-2 bg-gray-400 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest cursor-not-allowed transition ease-in-out duration-150">
                                    ✓ Wijzig Allergie
                                </button>
                                
                                <a href="{{ route('allergieen.gezin-details', $persoon->gezin_id) }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    ✕ Annuleren
                                </a>
                            </div>
                        </form>
                    </div>

                    <!-- Info over andere allergieën van de persoon -->
                    @if($persoon->allergieen->count() > 1)
                        <div class="p-4 bg-gray-50 rounded-lg">
                            <h5 class="text-sm font-medium text-gray-700 mb-2">Andere allergieën van {{ $persoon->voornaam }}:</h5>
                            <div class="flex flex-wrap gap-1">
                                @foreach($persoon->allergieen as $anderAllergie)
                                    @if($anderAllergie->id !== $huidigeAllergie->id)
                                        <span class="inline-block px-2 py-1 text-xs font-semibold text-white bg-gray-500 rounded-full">
                                            {{ $anderAllergie->naam }}
                                        </span>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Terug en Home buttons -->
                    <div class="flex justify-end space-x-3 mt-6">
                        <a href="{{ route('allergieen.gezin-details', $persoon->gezin_id) }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            ← Terug naar gezin
                        </a>
                        <a href="{{ route('dashboard') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            🏠 Home
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const allergieSelect = document.getElementById('nieuwe_allergie_id');
        const bevestigingCheckbox = document.getElementById('bevestiging');
        const submitBtn = document.getElementById('submitBtn');
        const hogeRisicoWaarschuwing = document.getElementById('hogeRisicoWaarschuwing');
        const form = document.getElementById('allergieWijzigForm');

        // Functie om submit button status te updaten
        function updateSubmitButton() {
            const allergieGeselecteerd = allergieSelect.value !== '';
            const bevestigd = bevestigingCheckbox.checked;
            
            if (allergieGeselecteerd && bevestigd) {
                submitBtn.disabled = false;
                submitBtn.classList.remove('bg-gray-400', 'cursor-not-allowed');
                submitBtn.classList.add('bg-green-600', 'hover:bg-green-700', 'focus:bg-green-700', 'active:bg-green-900', 'focus:outline-none', 'focus:ring-2', 'focus:ring-green-500', 'focus:ring-offset-2', 'cursor-pointer');
            } else {
                submitBtn.disabled = true;
                submitBtn.classList.add('bg-gray-400', 'cursor-not-allowed');
                submitBtn.classList.remove('bg-green-600', 'hover:bg-green-700', 'focus:bg-green-700', 'active:bg-green-900', 'focus:outline-none', 'focus:ring-2', 'focus:ring-green-500', 'focus:ring-offset-2', 'cursor-pointer');
            }
        }

        // Functie om hoog risico waarschuwing te tonen/verbergen
        function toggleHoogRisicoWaarschuwing() {
            const geselecteerdeOptie = allergieSelect.options[allergieSelect.selectedIndex];
            if (geselecteerdeOptie && geselecteerdeOptie.dataset.risico === 'hoog') {
                hogeRisicoWaarschuwing.classList.remove('hidden');
            } else {
                hogeRisicoWaarschuwing.classList.add('hidden');
            }
        }

        // Event listeners
        allergieSelect.addEventListener('change', function() {
            updateSubmitButton();
            toggleHoogRisicoWaarschuwing();
        });

        bevestigingCheckbox.addEventListener('change', updateSubmitButton);

        // Form validatie bij submit
        form.addEventListener('submit', function(e) {
            if (!allergieSelect.value) {
                e.preventDefault();
                alert('Selecteer eerst een nieuwe allergie.');
                allergieSelect.focus();
                return false;
            }

            if (!bevestigingCheckbox.checked) {
                e.preventDefault();
                alert('Bevestig eerst dat de wijziging correct is.');
                bevestigingCheckbox.focus();
                return false;
            }

            // Extra bevestiging voor hoog risico allergieën
            const geselecteerdeOptie = allergieSelect.options[allergieSelect.selectedIndex];
            if (geselecteerdeOptie && geselecteerdeOptie.dataset.risico === 'hoog') {
                const confirmatie = confirm('⚠️ WAARSCHUWING: U gaat wijzigen naar een allergie met hoog anafylactisch risico.\n\nWeet u zeker dat u wilt doorgaan?');
                if (!confirmatie) {
                    e.preventDefault();
                    return false;
                }
            }

            // Laatste bevestiging
            const allergieName = geselecteerdeOptie ? geselecteerdeOptie.text.split(' (')[0] : '';
            const confirmatie = confirm(`Weet u zeker dat u de allergie wilt wijzigen naar "${allergieName}"?\n\nDeze actie kan niet ongedaan gemaakt worden.`);
            if (!confirmatie) {
                e.preventDefault();
                return false;
            }

            // Disable submit button na submit om dubbele submits te voorkomen
            submitBtn.disabled = true;
            submitBtn.textContent = '⏳ Bezig met wijzigen...';
        });

        // Initial check
        updateSubmitButton();
        toggleHoogRisicoWaarschuwing();
    });
    </script>
</x-app-layout>
