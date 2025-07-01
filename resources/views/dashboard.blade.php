<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-4">
                <div class="p-6 text-gray-900">
                    {{ __("You're logged in!") }}
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="card border-success mb-3" style="box-shadow:0 2px 8px #e0e0e0;">
                        <div class="card-body text-center">
                            <h5 class="card-title" style="color:#1a7f37; font-size:1.5rem;">Ga naar Leveranciers</h5>
                            <p class="card-text">Bekijk en beheer alle leveranciers van de voedselbank.</p>
                            <a href="{{ route('leveranciers.index') }}" class="btn btn-success" style="font-size:1.1rem;">Naar Leveranciersoverzicht</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
