<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Voedselbank Maaskantje - Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold mb-6">Welkom bij Voedselbank Maaskantje</h3>
                    
                    <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                        <!-- Allergieën beheer -->
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 hover:bg-blue-100 transition-colors">
                            <div class="flex items-center mb-4">
                                <div class="flex items-center justify-center w-12 h-12 bg-blue-500 rounded-lg">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <h4 class="ml-3 text-lg font-medium text-blue-900">Allergieën</h4>
                            </div>
                            <p class="text-blue-800 mb-4">Bekijk en beheer voedselallergieën van gezinnen</p>
                            <a href="{{ route('allergieen.index') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Overzicht gezinsallergieën
                            </a>
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
