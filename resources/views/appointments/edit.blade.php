<x-app-layout>
    <div class="max-w-xl mx-auto p-4 text-gray-900 dark:text-white">
        <h1 class="text-2xl font-bold mb-6">Edit Appointment</h1>

        @if(session('error'))
            <div class="mb-4 text-red-600 dark:text-red-400 bg-red-100 dark:bg-red-900 p-3 rounded">
                {{ session('error') }}
            </div>
        @endif

        <form method="POST" action="{{ route('appointments.update', $appointment->id) }}" class="space-y-6">
            @csrf
            @method('PUT')

            <div>
                <label for="appointment_date" class="block font-semibold mb-1">Date</label>
                <input type="date"
                       name="appointment_date"
                       id="appointment_date"
                       value="{{ old('appointment_date', $appointment->appointment_date) }}"
                       class="w-full border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-white rounded px-3 py-2 focus:outline-none focus:ring focus:ring-blue-400 dark:focus:ring-blue-600"
                       required>
                @error('appointment_date')
                    <div class="text-sm text-red-600 dark:text-red-400 mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div>
                <label for="appointment_time" class="block font-semibold mb-1">Time</label>
                <input type="time"
                       name="appointment_time"
                       id="appointment_time"
                       value="{{ old('appointment_time', $appointment->appointment_time) }}"
                       class="w-full border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-white rounded px-3 py-2 focus:outline-none focus:ring focus:ring-blue-400 dark:focus:ring-blue-600"
                       required>
                @error('appointment_time')
                    <div class="text-sm text-red-600 dark:text-red-400 mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div>
                <label for="notes" class="block font-semibold mb-1">Notes</label>
                <textarea name="notes"
                          id="notes"
                          rows="4"
                          class="w-full border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-white rounded px-3 py-2 focus:outline-none focus:ring focus:ring-blue-400 dark:focus:ring-blue-600">{{ old('notes', $appointment->notes) }}</textarea>
                @error('notes')
                    <div class="text-sm text-red-600 dark:text-red-400 mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="flex justify-between items-center">
                <a href="{{ route('appointments.index') }}" class="text-blue-600 dark:text-blue-400 hover:underline">
                    Back to list
                </a>
                <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600 text-white font-semibold py-2 px-4 rounded">
                    Update Appointment
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
