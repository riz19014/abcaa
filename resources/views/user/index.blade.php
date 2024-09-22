<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Users Listing') }}
            </h2>
            <a href="{{ route('users.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white font-semibold rounded-md hover:bg-blue-700">
                {{ __('Create User') }}
            </a>
        </div>
    </x-slot>

    <!-- Flash message -->
     @if (session('status'))
        <div 
            x-data="{ show: true }"
            x-show="show"
            x-transition
            x-init="setTimeout(() => show = false, 2000)"
            class="max-w-7xl mx-auto sm:px-6 lg:px-8 mt-4"
        >
            @if (session('status') == 'user-created')
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <strong class="font-bold">{{ session('status') }}</strong>
                    <span class="block sm:inline">The user has been successfully created.</span>
                </div>
            @elseif (session('status') == 'user-updated')
                <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded relative" role="alert">
                    <strong class="font-bold">{{ session('status') }}</strong>
                    <span class="block sm:inline">The user has been successfully updated.</span>
                </div>
            @elseif (session('status') == 'user-deleted')
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    <strong class="font-bold">{{ session('status') }}</strong>
                    <span class="block sm:inline">The user has been successfully deleted.</span>
                </div>
            @endif
        </div>
    @endif


    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Search form -->
            <form action="{{ route('users.index') }}" method="GET">
                <div class="mb-4">
                    <input type="text" name="search" placeholder="Search by name or code" class="rounded-md border-gray-300 p-2 w-full" value="{{ request()->input('search') }}">
                </div>
            </form>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="w-xl">
                    <!-- Display Total Users with Different Colors -->
        <div class="mb-4 text-lg font-semibold">
            <span class="text-blue-600 ">Total Users:</span> 
            <span class="mb-4 text-lg font-semibold text-white bg-gradient-to-r from-blue-500 to-green-400 px-4 py-2 rounded-lg shadow-md">{{ $users->count() }}</span>
        </div>
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    #
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    User Code
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    User Name
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Phone
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Email
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($users as $item)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        {{ $loop->iteration }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        {{ $item->code }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        {{ $item->name }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        {{ $item->phone ? $item->phone : '--' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        {{ $item->email ? $item->email : '--' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <a href="{{ route('users.edit', $item->id) }}" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                                        <form action="{{ route('users.destroy', $item->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900 ml-2" onclick="return confirm('Are you sure you want to delete this user?')">
                                                Delete
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination Links -->
                <div class="mt-4">
                    {{ $users->appends(request()->input())->links() }}
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
