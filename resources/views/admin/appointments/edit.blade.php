<x-app-layout>
    <div class="max-w-xl mx-auto py-10">
        <h2 class="text-xl font-semibold mb-6 text-gray-900 dark:text-white">Edit Appointment</h2>

        @if ($errors->any())
            <div class="mb-4 text-red-600 dark:text-red-400">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.appointments.update', $appointment) }}">
            @csrf
            @method('PUT')

            {{-- Patient Name --}}
            <div class="mb-4">
                <label class="block text-gray-700 dark:text-gray-300">Patient</label>
                <input type="text" value="{{ $appointment->patient->name ?? 'N/A' }}" class="w-full border rounded px-3 py-2 dark:bg-gray-800 dark:text-white" readonly>
            </div>

            {{-- Dentist Name --}}
            <div class="mb-4">
                <label class="block text-gray-700 dark:text-gray-300">Dentist</label>
                <input type="text" value="{{ $appointment->dentist->name ?? 'N/A' }}" class="w-full border rounded px-3 py-2 dark:bg-gray-800 dark:text-white" readonly>
            </div>

            {{-- Appointment Date --}}
            <div class="mb-4">
                <label class="block text-gray-700 dark:text-gray-300">Appointment Date</label>
                <input type="date" name="appointment_date" value="{{ old('appointment_date', $appointment->appointment_date) }}" class="w-full border rounded px-3 py-2 dark:bg-gray-800 dark:text-white">
            </div>

            {{-- Appointment Time --}}
            <div class="mb-4">
                <label class="block text-gray-700 dark:text-gray-300">Appointment Time</label>
                <input type="time" name="appointment_time" value="{{ old('appointment_time', \Carbon\Carbon::createFromFormat('H:i:s', $appointment->appointment_time)->format('H:i')) }}" class="w-full border rounded px-3 py-2 dark:bg-gray-800 dark:text-white">
            </div>

            {{-- Status --}}
            <div class="mb-4">
                <label class="block text-gray-700 dark:text-gray-300">Status</label>
                <select name="status" class="w-full border rounded px-3 py-2 dark:bg-gray-800 dark:text-white">
                    @foreach(['pending', 'confirmed', 'cancelled'] as $status)
                        <option value="{{ $status }}" @selected($appointment->status === $status)>{{ ucfirst($status) }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Notes --}}
            <div class="mb-4">
                <label class="block text-gray-700 dark:text-gray-300">Notes</label>
                <textarea name="notes" rows="4" class="w-full border rounded px-3 py-2 dark:bg-gray-800 dark:text-white">{{ old('notes', $appointment->notes) }}</textarea>
            </div>

            {{-- Buttons --}}
            <div class="flex space-x-2">
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Update</button>
                <a href="{{ route('admin.appointments.index') }}" class="text-gray-600 dark:text-gray-300">Cancel</a>
            </div>
        </form>
    </div>
</x-app-layout>