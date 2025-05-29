<x-app-layout>
    <div class="max-w-5xl mx-auto p-4 text-gray-900 dark:text-white">
        <h1 class="text-2xl font-bold mb-4">My Appointments</h1>

        @if ($appointments->isEmpty())
            <p>No appointments found.</p>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white dark:bg-gray-800 text-gray-900 dark:text-white">
                    <thead class="bg-gray-200 dark:bg-gray-700">
                        <tr>
                            <th class="py-2 px-4 border-b border-gray-300 dark:border-gray-600 text-left">
                                {{ Auth::user()->role === 'patient' ? 'Dentist' : 'Patient' }}
                            </th>
                            <th class="py-2 px-4 border-b border-gray-300 dark:border-gray-600 text-left">Date</th>
                            <th class="py-2 px-4 border-b border-gray-300 dark:border-gray-600 text-left">Time</th>
                            <th class="py-2 px-4 border-b border-gray-300 dark:border-gray-600 text-left">Status</th>
                            <th class="py-2 px-4 border-b border-gray-300 dark:border-gray-600 text-left">Notes</th>
                            <th class="py-2 px-4 border-b border-gray-300 dark:border-gray-600 text-left">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($appointments as $appointment)
                            <tr class="hover:bg-gray-100 dark:hover:bg-gray-700">
                                <td class="py-2 px-4 border-b border-gray-300 dark:border-gray-600">
                                    @if ($user->role === 'patient')
                                        {{ $appointment->dentist->name }}
                                    @else
                                        {{ $appointment->patient->name }}
                                    @endif
                                </td>

                                <td class="py-2 px-4 border-b border-gray-300 dark:border-gray-600">{{ $appointment->appointment_date }}</td>
                                <td class="py-2 px-4 border-b border-gray-300 dark:border-gray-600">{{ $appointment->appointment_time }}</td>
                                <td class="py-2 px-4 border-b border-gray-300 dark:border-gray-600">
                                    @php
                                        $statusClass = '';
                                        if($appointment->status === 'cancelled') {
                                            $statusClass = 'text-red-600';
                                        }
                                    @endphp
                                    <span class="{{ $statusClass }}">{{ ucfirst($appointment->status) }}</span>
                                </td>
                                <td class="py-2 px-4 border-b border-gray-300 dark:border-gray-600">{{ $appointment->notes ?? '-' }}</td>
                                <td class="py-2 px-4 border-b border-gray-300 dark:border-gray-600">
                                    @php
                                        $appointmentDateTime = \Carbon\Carbon::parse($appointment->appointment_date . ' ' . $appointment->appointment_time);
                                        $canModify = now()->diffInHours($appointmentDateTime, false) >= 6;
                                    @endphp

                                    @if ($appointment->status === 'cancelled')
                                        <span class="text-gray-500 italic">Cancelled</span>
                                    @elseif ($canModify)
                                        @if ($user->role === 'patient')
                                            <a href="{{ route('appointments.edit', $appointment->id) }}" class="text-blue-600 hover:underline">Edit</a>
                                            |
                                        @endif

                                        <form action="{{ route('appointments.destroy', $appointment->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" onclick="return confirm('Are you sure?')" class="text-red-600 hover:underline">Cancel</button>
                                        </form>
                                    @else
                                        <span class="text-gray-500 italic">Locked</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</x-app-layout>
