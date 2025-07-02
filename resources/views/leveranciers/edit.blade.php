<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-bold text-green-600 border-b-2 border-green-600 pb-1">
            Klantgegevens wijzigen
        </h2>
    </x-slot>

    <div class="py-6 sm:py-12">
        <div class="container-responsive">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-responsive text-gray-900">
                    
                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('leveranciers.update', $leverancier) }}" class="space-y-6">
                        @csrf
                        @method('PUT')
                        
                        <!-- Naam -->
                        <div class="flex items-center gap-4">
                            <label class="text-sm font-medium text-gray-700 w-32">Naam *</label>
                            <input type="text" name="naam" 
                                   class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                   value="{{ old('naam', $leverancier->naam) }}" required>
                        </div>

                        <!-- Contactpersoon -->
                        <div class="flex items-center gap-4">
                            <label class="text-sm font-medium text-gray-700 w-32">Contactpersoon</label>
                            <input type="text" name="contact_persoon" 
                                   class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                   value="{{ old('contact_persoon', $leverancier->contact_persoon) }}">
                        </div>

                        <!-- Email -->
                        <div class="flex items-center gap-4">
                            <label class="text-sm font-medium text-gray-700 w-32">Email</label>
                            <input type="email" name="email" 
                                   class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                   value="{{ old('email', $leverancier->email) }}">
                        </div>

                        <!-- Mobiel -->
                        <div class="flex items-center gap-4">
                            <label class="text-sm font-medium text-gray-700 w-32">Mobiel</label>
                            <input type="text" name="mobiel" 
                                   class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                   value="{{ old('mobiel', $leverancier->mobiel) }}">
                        </div>

                        <!-- Leveranciernummer -->
                        <div class="flex items-center gap-4">
                            <label class="text-sm font-medium text-gray-700 w-32">Leveranciernummer *</label>
                            <input type="text" name="leverancier_nummer" 
                                   class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                   value="{{ old('leverancier_nummer', $leverancier->leverancier_nummer) }}" required>
                        </div>

                        <!-- LeverancierType -->
                        <div class="flex items-center gap-4">
                            <label class="text-sm font-medium text-gray-700 w-32">LeverancierType *</label>
                            <select name="leverancier_type" 
                                    class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                                <option value="">Selecteer type</option>
                                @foreach($types as $type)
                                    <option value="{{ $type }}" {{ old('leverancier_type', $leverancier->leverancier_type) == $type ? 'selected' : '' }}>
                                        {{ $type }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Buttons -->
                        <div class="flex justify-start gap-3 pt-6">
                            <a href="{{ route('leveranciers.index') }}" 
                               class="px-6 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 transition-colors">
                                Terug
                            </a>
                            <button type="submit" 
                                    class="px-6 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition-colors">
                                Opslaan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>