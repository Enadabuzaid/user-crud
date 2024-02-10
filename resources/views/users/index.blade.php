<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('User List') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <!-- Add User Button and Trashed Users Link -->
                <div class="flex gap-2 justify-end items-center p-8 mb-6">
                    <a href="{{ route('users.create') }}" class="bg-orange-500 hover:bg-orange-700 text-white font-bold py-2 px-4 rounded">
                        Add User
                    </a>
                    <a href="{{ route('users.trashed') }}" class="text-orange-500 underline hover:text-orange-700 hover:underline">
                        Trashed Users
                    </a>
                </div>

                <x-alert-message type="success" class="bg-green-100 p-4 rounded my-4"/>
                <x-alert-message type="error"  class="bg-red-100 p-4 rounded my-4" />


                <table class="min-w-full leading-normal dark:bg-gray-900 dark:text-white">
                    <thead class="dark:bg-gray-900 dark:text-white">
                    <tr>
                        <th class=" px-5 py-3 border-b-2 border-gray-200 text-gray-800  text-left text-sm uppercase font-normal dark:bg-gray-900  dark:text-white dark:border-gray-500">
                            Name
                        </th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 text-gray-800  text-left text-sm uppercase font-normal dark:bg-gray-900 dark:text-white dark:border-gray-500">
                            Email
                        </th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 text-gray-800 text-left text-sm uppercase font-normal dark:bg-gray-900 dark:text-white dark:border-gray-500">
                            Username
                        </th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 text-gray-800 text-left text-sm uppercase font-normal dark:bg-gray-900 dark:text-white dark:border-gray-500">
                            Type
                        </th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 text-gray-800 text-left text-sm uppercase font-normal dark:bg-gray-900 dark:text-white dark:border-gray-500">
                            Actions
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($users as $user)
                        <tr>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm dark:bg-gray-700 dark:border-gray-500">
                                <div class="flex items-center">
                                    <div class="ml-3">
                                        <p class="text-gray-900 whitespace-no-wrap dark:text-white">
                                            {{ $user->firstname }} {{ $user->lastname }}
                                        </p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm dark:bg-gray-700 dark:border-gray-500">
                                <p class="text-gray-900 whitespace-no-wrap dark:text-white">{{ $user->email }}</p>
                            </td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm dark:bg-gray-700 dark:border-gray-500">
                                <p class="text-gray-900 whitespace-no-wrap dark:text-white">{{ $user->username }}</p>
                            </td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm dark:bg-gray-700 dark:border-gray-500">
                                <p class="text-gray-900 whitespace-no-wrap dark:text-white">{{ $user->type }}</p>
                            </td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm dark:bg-gray-700 dark:border-gray-500">
                                <div class="flex space-x-3">
                                    <a href="{{ route('users.show', $user->id) }}" class="text-blue-600 hover:text-blue-900">Show</a>
                                    <a href="{{ route('users.edit', $user->id) }}" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                                    <form action="{{ route('users.destroy', $user->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Are you sure?')">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                <!-- Pagination -->
                <div class="px-5 py-5 bg-white border-t  xs:flex-row items-center xs:justify-between dark:bg-gray-700 dark:border-gray-500">
                    {{ $users->links() }}
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
