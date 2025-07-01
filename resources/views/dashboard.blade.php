<x-app-layout>
    <x-slot name="header">
        <h2 class="heading-responsive">
            <span class="flex items-center">
                🏠 {{ __('Voedselbank Maaskantje') }}
            </span>
        </h2>
    </x-slot>

    <div class="py-6 sm:py-12">
        <div class="container-responsive">
            <!-- Hero Section -->
            <div class="bg-gradient-to-r from-blue-600 to-blue-800 rounded-lg text-white p-6 sm:p-8 mb-8">
                <div class="text-center">
                    <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold mb-4">
                        Welkom bij Voedselbank Maaskantje
                    </h1>
                    <p class="text-blue-100 text-sm sm:text-base lg:text-lg max-w-2xl mx-auto">
                        Samen zorgen wij ervoor dat iedereen toegang heeft tot voldoende en gezond voedsel. 
                        Gebruik onderstaande functionaliteiten om gezinnen optimaal te ondersteunen.
                    </p>
                </div>
            </div>

            <!-- Main Navigation Cards -->
            <div class="grid-responsive mb-8">
                <!-- Allergieën Module -->
                <div class="card-responsive bg-red-50 border-red-200 hover:bg-red-100 transition-colors p-6">
                    <div class="flex items-center mb-4">
                        <div class="flex items-center justify-center w-12 h-12 bg-red-500 rounded-lg flex-shrink-0">
                            <span class="text-white text-xl">🚫</span>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-semibold text-red-900">Allergieën Beheer</h3>
                            <p class="text-red-700 text-sm">Voedselallergieën per gezin</p>
                        </div>
                    </div>
                    <p class="text-red-800 mb-4 text-sm">
                        Overzicht en beheer van alle voedselallergieën binnen gezinnen. 
                        Filter op specifieke allergieën en bewerk persoonlijke allergie-informatie.
                    </p>
                    <div class="space-y-2">
                        <a href="{{ route('allergieen.index') }}" class="btn-primary-responsive block text-center">
                            📊 Overzicht Gezinsallergieën
                        </a>
                    </div>
                </div>

                <!-- Gezinnen Module -->
                <div class="card-responsive bg-green-50 border-green-200 hover:bg-green-100 transition-colors p-6">
                    <div class="flex items-center mb-4">
                        <div class="flex items-center justify-center w-12 h-12 bg-green-500 rounded-lg flex-shrink-0">
                            <span class="text-white text-xl">👨‍👩‍👧‍👦</span>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-semibold text-green-900">Gezinnen Beheer</h3>
                            <p class="text-green-700 text-sm">Gezinsadministratie</p>
                        </div>
                    </div>
                    <p class="text-green-800 mb-4 text-sm">
                        Beheer van gezinsgegevens, personen en contactinformatie. 
                        Registreer nieuwe gezinnen en actualiseer bestaande gegevens.
                    </p>
                    <div class="space-y-2">
                        <button class="btn-secondary-responsive w-full" disabled>
                            📋 Gezinnoverzicht (Binnenkort)
                        </button>
                    </div>
                </div>

                <!-- Leveranciers Module -->
                <div class="card-responsive bg-purple-50 border-purple-200 hover:bg-purple-100 transition-colors p-6">
                    <div class="flex items-center mb-4">
                        <div class="flex items-center justify-center w-12 h-12 bg-purple-500 rounded-lg flex-shrink-0">
                            <span class="text-white text-xl">🚚</span>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-semibold text-purple-900">Leveranciers</h3>
                            <p class="text-purple-700 text-sm">Supplierbeheer</p>
                        </div>
                    </div>
                    <p class="text-purple-800 mb-4 text-sm">
                        Beheer van leveranciers, contactgegevens en productaanbod. 
                        Onderhoud relaties met voedseldonateurs en suppliers.
                    </p>
                    <div class="space-y-2">
                        <button class="btn-secondary-responsive w-full" disabled>
                            🏢 Leveranciersoverzicht (Binnenkort)
                        </button>
                    </div>
                </div>

                <!-- Voedseluitgifte Module -->
                <div class="card-responsive bg-orange-50 border-orange-200 hover:bg-orange-100 transition-colors p-6">
                    <div class="flex items-center mb-4">
                        <div class="flex items-center justify-center w-12 h-12 bg-orange-500 rounded-lg flex-shrink-0">
                            <span class="text-white text-xl">📦</span>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-semibold text-orange-900">Voedseluitgifte</h3>
                            <p class="text-orange-700 text-sm">Pakketbeheer</p>
                        </div>
                    </div>
                    <p class="text-orange-800 mb-4 text-sm">
                        Samenstellen en uitgeven van voedselpakketten aan gezinnen. 
                        Houd rekening met allergieën en eetvoorkeuren.
                    </p>
                    <div class="space-y-2">
                        <button class="btn-secondary-responsive w-full" disabled>
                            📋 Pakketuitgifte (Binnenkort)
                        </button>
                    </div>
                </div>

                <!-- Voorraad Module -->
                <div class="card-responsive bg-blue-50 border-blue-200 hover:bg-blue-100 transition-colors p-6">
                    <div class="flex items-center mb-4">
                        <div class="flex items-center justify-center w-12 h-12 bg-blue-500 rounded-lg flex-shrink-0">
                            <span class="text-white text-xl">📊</span>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-semibold text-blue-900">Voorraad</h3>
                            <p class="text-blue-700 text-sm">Magazijnbeheer</p>
                        </div>
                    </div>
                    <p class="text-blue-800 mb-4 text-sm">
                        Voorraadbeheer van producten, houdbaarheid en magazijnlocaties. 
                        Monitor voorraadniveaus en plan inkoopbehoeften.
                    </p>
                    <div class="space-y-2">
                        <button class="btn-secondary-responsive w-full" disabled>
                            📈 Voorraadoverzicht (Binnenkort)
                        </button>
                    </div>
                </div>

                <!-- Rapportage Module -->
                <div class="card-responsive bg-yellow-50 border-yellow-200 hover:bg-yellow-100 transition-colors p-6">
                    <div class="flex items-center mb-4">
                        <div class="flex items-center justify-center w-12 h-12 bg-yellow-500 rounded-lg flex-shrink-0">
                            <span class="text-white text-xl">📈</span>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-semibold text-yellow-900">Rapportage</h3>
                            <p class="text-yellow-700 text-sm">Analytics & Statistieken</p>
                        </div>
                    </div>
                    <p class="text-yellow-800 mb-4 text-sm">
                        Statistieken en rapportages over gezinnen, allergieën, en voedseluitgifte. 
                        Inzicht in trends en prestatie-indicatoren.
                    </p>
                    <div class="space-y-2">
                        <button class="btn-secondary-responsive w-full" disabled>
                            📊 Rapportages (Binnenkort)
                        </button>
                    </div>
                </div>
            </div>

            <!-- Quick Stats Section -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-8">
                <h3 class="subheading-responsive mb-4 text-center">Snelle Statistieken</h3>
                <div class="grid grid-cols-2 gap-4 sm:grid-cols-4">
                    <div class="text-center p-4 bg-red-50 rounded-lg">
                        <div class="text-2xl font-bold text-red-600">8</div>
                        <div class="text-sm text-red-800">Bekende Allergieën</div>
                    </div>
                    <div class="text-center p-4 bg-green-50 rounded-lg">
                        <div class="text-2xl font-bold text-green-600">3</div>
                        <div class="text-sm text-green-800">Actieve Gezinnen</div>
                    </div>
                    <div class="text-center p-4 bg-blue-50 rounded-lg">
                        <div class="text-2xl font-bold text-blue-600">6</div>
                        <div class="text-sm text-blue-800">Geregistreerde Personen</div>
                    </div>
                    <div class="text-center p-4 bg-purple-50 rounded-lg">
                        <div class="text-2xl font-bold text-purple-600">5</div>
                        <div class="text-sm text-purple-800">Allergie Registraties</div>
                    </div>
                </div>
            </div>

            <!-- Footer Info -->
            <div class="text-center text-gray-600 text-sm">
                <p class="mb-2">
                    <strong>Voedselbank Maaskantje</strong> - Samen tegen voedselverspilling en voor voedselzekerheid
                </p>
                <p class="text-xs">
                    Ontwikkeld door MBO Utrecht ICT Academie - Software Development - Dag 3 Project
                </p>
            </div>
        </div>
    </div>

                        <!-- Placeholder voor andere functionaliteiten -->
                        <div class="bg-gray-50 border border-gray-200 rounded-lg p-6">
                            <div class="flex items-center mb-4">
                                <div class="flex items-center justify-center w-12 h-12 bg-gray-400 rounded-lg">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                    </svg>
                                </div>
                                <h4 class="ml-3 text-lg font-medium text-gray-700">Leveranciers</h4>
                            </div>
                            <p class="text-gray-600 mb-4">Beheer leveranciers en hun producten</p>
                            <button disabled class="inline-flex items-center px-4 py-2 bg-gray-400 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest cursor-not-allowed">
                                Binnenkort beschikbaar
                            </button>
                        </div>

                        <div class="bg-gray-50 border border-gray-200 rounded-lg p-6">
                            <div class="flex items-center mb-4">
                                <div class="flex items-center justify-center w-12 h-12 bg-gray-400 rounded-lg">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                    </svg>
                                </div>
                                <h4 class="ml-3 text-lg font-medium text-gray-700">Voedselpakketten</h4>
                            </div>
                            <p class="text-gray-600 mb-4">Samenstellen en beheren van voedselpakketten</p>
                            <button disabled class="inline-flex items-center px-4 py-2 bg-gray-400 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest cursor-not-allowed">
                                Binnenkort beschikbaar
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
