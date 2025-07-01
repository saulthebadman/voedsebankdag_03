<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            🏢 {{ __('Leveranciers Overzicht') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Statistieken Cards -->
            <div class="grid grid-cols-2 gap-4 sm:grid-cols-4 mb-8">
                <div class="bg-white p-4 rounded-lg shadow-sm border">
                    <div class="text-2xl font-bold text-purple-600">{{ $statistics['total_leveranciers'] ?? 0 }}</div>
                    <div class="text-sm text-gray-600">Totaal Leveranciers</div>
                </div>
                <div class="bg-white p-4 rounded-lg shadow-sm border">
                    <div class="text-2xl font-bold text-green-600">{{ $statistics['actieve_leveranciers'] ?? 0 }}</div>
                    <div class="text-sm text-gray-600">Actieve Leveranciers</div>
                </div>
                <div class="bg-white p-4 rounded-lg shadow-sm border">
                    <div class="text-2xl font-bold text-blue-600">{{ $statistics['total_producten'] ?? 0 }}</div>
                    <div class="text-sm text-gray-600">Totaal Producten</div>
                </div>
                <div class="bg-white p-4 rounded-lg shadow-sm border">
                    <div class="text-2xl font-bold text-red-600">{{ $statistics['inactieve_leveranciers'] ?? 0 }}</div>
                    <div class="text-sm text-gray-600">Inactieve Leveranciers</div>
                </div>
            </div>

            <!-- Filter sectie -->
            <div class="bg-white rounded-lg shadow-sm border p-6 mb-8">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">🔍 Filter Leveranciers</h3>
                <form method="GET" action="{{ route('leveranciers.index') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label for="leverancier_type" class="block text-sm font-medium text-gray-700 mb-2">Leverancier Type</label>
                        <select name="leverancier_type" id="leverancier_type" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">Alle Types</option>
                            <option value="Bedrijf" {{ request('leverancier_type') == 'Bedrijf' ? 'selected' : '' }}>Bedrijf</option>
                            <option value="Instelling" {{ request('leverancier_type') == 'Instelling' ? 'selected' : '' }}>Instelling</option>
                            <option value="Overheid" {{ request('leverancier_type') == 'Overheid' ? 'selected' : '' }}>Overheid</option>
                            <option value="Particulier" {{ request('leverancier_type') == 'Particulier' ? 'selected' : '' }}>Particulier</option>
                            <option value="Donor" {{ request('leverancier_type') == 'Donor' ? 'selected' : '' }}>Donor</option>
                        </select>
                    </div>
                    <div>
                        <label for="search" class="block text-sm font-medium text-gray-700 mb-2">Zoek op naam</label>
                        <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Leverancier naam..." class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                    <div class="flex items-end">
                        <button type="submit" class="w-full bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            🔍 Filter Toepassen
                        </button>
                    </div>
                </form>
                @if(request()->hasAny(['leverancier_type', 'search']))
                    <div class="mt-4">
                        <a href="{{ route('leveranciers.index') }}" class="text-blue-600 hover:text-blue-800 underline">
                            ↩️ Alle filters wissen
                        </a>
                    </div>
                @endif
            </div>

            <!-- Leveranciers Tabel -->
            <div class="bg-white rounded-lg shadow overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">NAAM</th>
                            <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">CONTACTPERSOON</th>
                            <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">EMAIL</th>
                            <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">MOBIEL</th>
                            <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">LEVERANCIERNUMMER</th>
                            <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">LEVERANCIERTYPE</th>
                            <th class="px-3 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">PRODUCT DETAILS</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($leveranciers as $leverancier)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3">
                                    <div class="text-sm font-medium text-gray-900">{{ $leverancier->naam }}</div>
                                    <div class="text-xs text-gray-500">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs {{ $leverancier->is_actief ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            {{ $leverancier->is_actief ? 'Actief' : 'Inactief' }}
                                        </span>
                                    </div>
                                </td>
                                <td class="px-3 py-3 text-sm text-gray-900">
                                    {{ $leverancier->contact_persoon ?? '-' }}
                                </td>
                                <td class="px-3 py-3 text-sm text-gray-900">
                                    @if($leverancier->contacts->first())
                                        {{ $leverancier->contacts->first()->email ?? '-' }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="px-3 py-3 text-sm text-gray-900">
                                    @if($leverancier->contacts->first())
                                        {{ $leverancier->contacts->first()->mobiel ?? '-' }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="px-3 py-3 text-sm text-gray-900">
                                    {{ $leverancier->leverancier_nummer ?? '-' }}
                                </td>
                                <td class="px-3 py-3">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs bg-blue-100 text-blue-800">
                                        {{ $leverancier->leverancier_type }}
                                    </span>
                                </td>
                                <td class="px-3 py-3 text-center">
                                    <div class="flex flex-col items-center space-y-1">
                                        <span class="bg-gray-100 text-gray-800 text-xs px-2 py-1 rounded">
                                            {{ $leverancier->products->count() }} producten
                                        </span>
                                        <a href="{{ route('leveranciers.show', $leverancier) }}" class="text-blue-600 hover:text-blue-900 text-xs bg-blue-100 hover:bg-blue-200 px-2 py-1 rounded">
                                            👁️ Details
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center">
                                    <div class="text-gray-500">
                                        <div class="text-4xl mb-4">🏢</div>
                                        <h3 class="text-lg font-medium mb-2">Geen leveranciers gevonden</h3>
                                        @if(request()->hasAny(['leverancier_type', 'search']))
                                            <p class="mb-4">Er zijn geen leveranciers die voldoen aan de huidige filters.</p>
                                            <a href="{{ route('leveranciers.index') }}" class="text-blue-600 hover:text-blue-800 underline">
                                                Alle filters wissen
                                            </a>
                                        @else
                                            <p class="mb-4">Er zijn nog geen leveranciers in het systeem.</p>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Terug naar dashboard -->
            <div class="mt-8 text-center">
                <a href="{{ route('dashboard') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    ← Terug naar Dashboard
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
