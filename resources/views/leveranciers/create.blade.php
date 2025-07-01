<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                ➕ {{ __('Nieuwe Leverancier Toevoegen') }}
            </h2>
            <a href="{{ route('leveranciers.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                ← Terug naar overzicht
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    @if ($errors->any())
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                            <strong class="font-bold">Oops! Er zijn wat problemen:</strong>
                            <ul class="mt-2 list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('leveranciers.store') }}" class="space-y-6">
                        @csrf
                        
                        <!-- Basisinformatie sectie -->
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">📋 Basisinformatie</h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="naam" class="block text-sm font-medium text-gray-700 mb-2">
                                        Naam <span class="text-red-500">*</span>
                                    </label>
                                    <input 
                                        type="text" 
                                        id="naam"
                                        name="naam" 
                                        value="{{ old('naam') }}"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('naam') border-red-300 @enderror"
                                        placeholder="Bijv. Albert Heijn"
                                        autofocus
                                        required
                                    >
                                    @error('naam')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="leveranciernummer" class="block text-sm font-medium text-gray-700 mb-2">
                                        Leveranciernummer <span class="text-red-500">*</span>
                                    </label>
                                    <input 
                                        type="text" 
                                        id="leveranciernummer"
                                        name="leveranciernummer" 
                                        value="{{ old('leveranciernummer') }}"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('leveranciernummer') border-red-300 @enderror"
                                        placeholder="Bijv. LV001"
                                        required
                                    >
                                    @error('leveranciernummer')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="mt-6">
                                <label for="leverancier_type" class="block text-sm font-medium text-gray-700 mb-2">
                                    Leveranciertype <span class="text-red-500">*</span>
                                </label>
                                <select 
                                    id="leverancier_type"
                                    name="leverancier_type" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('leverancier_type') border-red-300 @enderror"
                                    required
                                >
                                    <option value="">Selecteer leveranciertype...</option>
                                    @foreach($types as $type)
                                        <option value="{{ $type }}" @if(old('leverancier_type') == $type) selected @endif>
                                            {{ $type }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('leverancier_type')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Contactinformatie sectie -->
                        <div class="bg-blue-50 p-4 rounded-lg">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">📞 Contactinformatie</h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="contactpersoon" class="block text-sm font-medium text-gray-700 mb-2">
                                        Contactpersoon
                                    </label>
                                    <input 
                                        type="text" 
                                        id="contactpersoon"
                                        name="contactpersoon" 
                                        value="{{ old('contactpersoon') }}"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('contactpersoon') border-red-300 @enderror"
                                        placeholder="Bijv. Jan van der Berg"
                                    >
                                    @error('contactpersoon')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="mobiel" class="block text-sm font-medium text-gray-700 mb-2">
                                        Mobiel
                                    </label>
                                    <input 
                                        type="tel" 
                                        id="mobiel"
                                        name="mobiel" 
                                        value="{{ old('mobiel') }}"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('mobiel') border-red-300 @enderror"
                                        placeholder="06-12345678"
                                    >
                                    @error('mobiel')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="mt-6">
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                    Email
                                </label>
                                <input 
                                    type="email" 
                                    id="email"
                                    name="email" 
                                    value="{{ old('email') }}"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('email') border-red-300 @enderror"
                                    placeholder="contact@leverancier.nl"
                                >
                                @error('email')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Submit buttons -->
                        <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200">
                            <a 
                                href="{{ route('leveranciers.index') }}" 
                                class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-6 rounded transition duration-200"
                            >
                                ✕ Annuleren
                            </a>
                            <button 
                                type="submit" 
                                class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-6 rounded transition duration-200"
                            >
                                ✓ Leverancier Opslaan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>