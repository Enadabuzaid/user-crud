
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('User Details') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-md mx-auto bg-white shadow-xl rounded-lg overflow-hidden md:max-w-lg">
            <div class="flex gap-2 justify-end items-center p-8 mb-6">
                <a href="{{ route('users.index') }}" class="text-orange-500 underline hover:text-orange-700 hover:underline">
                    Back to Users list
                </a>
            </div>
            <div class="md:flex">
            @if($user->photo)
                    <div class="w-full">
                        <img class="h-48 w-48 object-cover md:w-full" src="{{ asset($user->photo) }}" alt="User Photo" style="max-height: 200px; max-width: 200px;"> <!-- Adjusted max-height and max-width -->
                    </div>
                @endif
                <div class="p-4">
                    <h3 class="text-xl text-gray-900 font-semibold">{{ $user->firstname }} {{ $user->lastname }}</h3>
                    <p class="text-gray-600">{{ $user->email }}</p>
                    <p class="mt-2 text-gray-600">Username: {{ $user->username }}</p>
                    <p class="mt-2 text-gray-600">Type: {{ $user->type ?? 'N/A' }}</p>
                    @if($user->prefixname || $user->suffixname)
                        <p class="mt-2 text-gray-600">Full Name: {{ $user->prefixname }} {{ $user->firstname }} {{ $user->middlename }} {{ $user->lastname }} ,  {{ $user->suffixname }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
