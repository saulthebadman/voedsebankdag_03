<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-green-600 border-b-2 border-green-600 pb-1">
            Wijzig Product
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    @if ($errors->any())
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                            De houdbaarheidsdatum is niet gewijzigd
                        </div>
                    @endif

                    <form method="POST" action="{{ route('products.update', [$leverancier, $product]) }}" class="space-y-6">
                        @csrf
                        @method('PUT')
                        
                        <div>
                            <label for="houdbaarheidsdatum" class="block text-lg font-medium text-gray-900 mb-4">
                                Houdbaarheidsdatum:
                            </label>
                            @php
                                // Voor test purposes: alsof we in augustus 2024 zijn
                                $today = \Carbon\Carbon::parse('2024-08-16'); // Test datum rond houdbaarheidsdatum
                                $maxDate = $today->copy()->addDays(7);
                            @endphp
                            
                            <input 
                                type="date" 
                                id="houdbaarheidsdatum"
                                name="houdbaarheidsdatum" 
                                value="{{ old('houdbaarheidsdatum', $today->format('Y-m-d')) }}"
                                min="{{ $today->format('Y-m-d') }}"
                                max="{{ $maxDate->format('Y-m-d') }}"
                                class="w-full px-4 py-3 text-lg border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('houdbaarheidsdatum') border-red-300 @enderror"
                                required
                            >
                            
                            <p class="mt-2 text-red-500 text-sm">
                                De houdbaarheidsdatum mag met maximaal 7 dagen worden verlengd
                            </p>
                        </div>

                        <!-- Submit buttons -->
                        <div class="flex justify-between pt-6">
                            <button 
                                type="submit" 
                                class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-3 px-8 rounded"
                            >
                                Wijzig Houdbaarheidsdatum
                            </button>
                            <div class="space-x-3">
                                <a 
                                    href="{{ route('leveranciers.show', $leverancier) }}" 
                                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded"
                                >
                                    Terug
                                </a>
                                <a 
                                    href="{{ route('dashboard') }}" 
                                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded"
                                >
                                    Home
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
