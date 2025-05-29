<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">Admin Dashboard</h2>
    </x-slot>

    <div class="py-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <p>Welcome, Admin! Approve dentists here.</p>

            @if(Auth::user()->is_admin)
                <section class="mt-10 px-6 py-8 bg-white dark:bg-gray-900 rounded-lg shadow">
                    <h2 class="text-2xl font-semibold mb-6 text-gray-900 dark:text-white">All Users</h2>

                    @if($users->isEmpty())
                        <p class="text-gray-700 dark:text-gray-300">No users found.</p>
                    @else
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-gray-200 dark:bg-gray-800 text-gray-700 dark:text-gray-300">
                                    <th class="p-3 border-b">Name</th>
                                    <th class="p-3 border-b">Email</th>
                                    <th class="p-3 border-b">Role</th>
                                    <th class="p-3 border-b">Approved</th>
                                    <th class="p-3 border-b">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($users as $user)
                                    <tr class="hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-900 dark:text-gray-100">
                                        <td class="p-3 border-b">{{ $user->name }}</td>
                                        <td class="p-3 border-b">{{ $user->email }}</td>
                                        <td class="p-3 border-b capitalize">{{ $user->role }}</td>
                                        <td class="p-3 border-b">
                                            @if($user->is_approved)
                                                <span class="text-green-600 font-semibold">Yes</span>
                                            @else
                                                <span class="text-red-600 font-semibold">No</span>
                                            @endif
                                        </td>
                                        <td class="p-3 border-b">
                                            @if(!$user->is_approved)
                                                <form method="POST" action="{{ route('admin.users.approve', $user->id) }}" style="display:inline">
                                                    @csrf
                                                    <button class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-sm">
                                                        Approve
                                                    </button>
                                                </form>

                                                <form method="POST" action="{{ route('admin.users.reject', $user->id) }}" onsubmit="return confirm('Are you sure you want to reject and delete this user? This action cannot be undone.')">
                                                    @csrf
                                                    <button class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded text-sm">
                                                        Reject
                                                    </button>
                                                </form>
                                            @else
                                                <span class="text-gray-500 text-sm">Approved</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </section>
            @endif
        </div>
    </div>
</x-app-layout>
