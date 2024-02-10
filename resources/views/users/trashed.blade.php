<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Trashed Users') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="flex gap-2 justify-end items-center p-8 mb-6">
                    <a href="{{ route('users.index') }}" class="text-orange-500 underline hover:text-orange-700 hover:underline">
                        Back to Users list
                    </a>
                </div>

                <x-alert-message type="success" class="bg-green-100 p-4 rounded my-4"/>
                <x-alert-message type="error"  class="bg-red-100 p-4 rounded my-4" />

                @if($trashed_users->isNotEmpty())
                    <table class="min-w-full leading-normal">
                        <thead>
                        <tr>
                            <th class="px-5 py-3 border-b-2 border-gray-200 text-gray-800  text-left text-sm uppercase font-normal">
                                Name
                            </th>
                            <th class="px-5 py-3 border-b-2 border-gray-200 text-gray-800  text-left text-sm uppercase font-normal">
                                Email
                            </th>
                            <th class="px-5 py-3 border-b-2 border-gray-200 text-gray-800 text-left text-sm uppercase font-normal">
                                Username
                            </th>
                            <th class="px-5 py-3 border-b-2 border-gray-200 text-gray-800 text-left text-sm uppercase font-normal">
                                Type
                            </th>
                            <th class="px-5 py-3 border-b-2 border-gray-200 text-gray-800 text-left text-sm uppercase font-normal">
                                Actions
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($trashed_users as $user)
                            <tr>
                                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                    <div class="flex items-center">
                                        <div class="ml-3">
                                            <p class="text-gray-900 whitespace-no-wrap">
                                                {{ $user->firstname }} {{ $user->lastname }}
                                            </p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                    <p class="text-gray-900 whitespace-no-wrap">{{ $user->email }}</p>
                                </td>
                                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                    <p class="text-gray-900 whitespace-no-wrap">{{ $user->username }}</p>
                                </td>
                                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                    <p class="text-gray-900 whitespace-no-wrap">{{ $user->type }}</p>
                                </td>
                                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                    <div class="flex space-x-3">
                                        <form action="{{ route('users.restore', $user->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to restore this user?')">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="text-blue-600 hover:text-blue-900">Restore</button>
                                        </form>

                                        <form action="{{ route('users.delete', $user->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-500 hover:text-red-700" onclick="return confirm('Are you sure you want to permanently delete this user?');">
                                                Delete Forever
                                            </button>
                                        </form>

                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    <!-- Pagination -->
                    <div class="px-5 py-5 bg-white border-t  xs:flex-row items-center xs:justify-between">
                        {{ $trashed_users->links() }}
                    </div>
                @else
                    <p class="text-orange-400 p-6"> No Trashed Users</p>
                @endif


            </div>
        </div>
    </div>
</x-app-layout>
