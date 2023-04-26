<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Add new Sub-Domain') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form method="POST" action="{{ route('subDomain.store') }}">
                    @csrf

                    <!-- Name -->
                        <div>
                            <x-input-label for="sub_domain" :value="__('Sub Domain')"/>

                            <x-text-input id="sub_domain" class="block mt-1 w-full" type="text" name="sub_domain" :value="old('sub_domain')"
                                     required autofocus/>
                            <x-input-error :messages="$errors->get('sub_domain')" class="mt-2" />
                        </div>

                        

                        <div class="flex mt-4">
                            <x-button>
                                {{ __('Save') }}
                            </x-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
