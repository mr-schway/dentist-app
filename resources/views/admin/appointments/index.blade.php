<x-app-layout>
<div class="container mx-auto px-4 py-6">
    <h1 class="text-2xl font-bold mb-6 dark:text-white">Appointments</h1>

    @if (session('success'))
        <div class="mb-4 text-green-600 dark:text-green-300">{{ session('success') }}</div>
    @endif

    <div class="overflow-x-auto bg-white dark:bg-gray-900 rounded-lg shadow">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gray-100 dark:bg-gray-800">
                <tr>
                    <th class="px-4 py-2 text-left text-xs font-bold text-gray-600 dark:text-gray-300">ID</th>
                    <th class="px-4 py-2">Patient</th>
                    <th class="px-4 py-2">Dentist</th>
                    <th class="px-4 py-2">Date</th>
                    <th class="px-4 py-2">Time</th>
                    <th class="px-4 py-2">Status</th>
                    <th class="px-4 py-2">Notes</th>
                    <th class="px-4 py-2">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($appointments as $appt)
                    <tr class="hover:bg-gray-100 dark:hover:bg-gray-800 text-sm text-gray-700 dark:text-gray-200">
                        <td class="px-4 py-2">{{ $appt->id }}</td>
                        <td class="px-4 py-2">{{ $appt->patient->name ?? 'Unknown' }}</td>
                        <td class="px-4 py-2">{{ $appt->dentist->name ?? 'Unknown' }}</td>
                        <td class="px-4 py-2">{{ $appt->appointment_date }}</td>
                        <td class="px-4 py-2">{{ \Carbon\Carbon::parse($appt->appointment_time)->format('H:i') }}</td>
                        <td class="px-4 py-2 capitalize">{{ $appt->status }}</td>
                        <td class="px-4 py-2">{{ $appt->notes ?? '-' }}</td>
                        <td class="px-4 py-2 flex space-x-2">
                            <a href="{{ route('admin.appointments.edit', $appt) }}"
                               class="text-blue-600 hover:underline dark:text-blue-400">Edit</a>
                            <form method="POST" action="{{ route('admin.appointments.destroy', $appt) }}"
                                  onsubmit="return confirm('Are you sure?');">
                                @csrf @method('DELETE')
                                <button class="text-red-600 hover:underline dark:text-red-400">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="8" class="px-4 py-4 text-center">No appointments found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">{{ $appointments->links() }}</div>
</div>
</x-app-layout>