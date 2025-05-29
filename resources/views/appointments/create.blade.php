<x-app-layout>
    <div class="max-w-2xl mx-auto p-4">
        <h1 class="text-2xl font-bold mb-4 text-gray-800 dark:text-white">Book an Appointment</h1>

        <form method="POST" action="{{ route('appointments.store') }}">
            @csrf

            <!-- Dentist Type -->
            <div class="mb-4">
                <label for="dentist_type" class="block font-medium text-gray-800 dark:text-gray-200">Select Dentist Type:</label>
                <select id="dentist_type" class="w-full border rounded p-2 bg-white dark:bg-gray-800 text-gray-900 dark:text-white">
                    @foreach ($dentists->groupBy('role') as $role => $group)
                        <option value="{{ $role }}">{{ ucfirst(str_replace('-', ' ', $role)) }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Dentist Name -->
            <div class="mb-4">
                <label for="dentist_id" class="block font-medium text-gray-800 dark:text-gray-200">Select Dentist:</label>
                <select name="dentist_id" id="dentist_id" class="w-full border rounded p-2 bg-white dark:bg-gray-800 text-gray-900 dark:text-white" required>
                    @foreach ($dentists as $dentist)
                        <option value="{{ $dentist->id }}" data-role="{{ $dentist->role }}">
                            {{ $dentist->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Appointment Date -->
            <div class="mb-4">
                <label for="appointment_date" class="block font-medium text-gray-800 dark:text-gray-200">Date:</label>
                <input type="date" name="appointment_date" id="appointment_date" class="w-full border rounded p-2 bg-white dark:bg-gray-800 text-gray-900 dark:text-white" required min="{{ \Carbon\Carbon::today()->toDateString() }}">
            </div>

            <!-- Appointment Time -->
            <div class="mb-4">
                <label for="appointment_time" class="block font-medium text-gray-800 dark:text-gray-200">Time:</label>
                <input type="time" name="appointment_time" id="appointment_time" class="w-full border rounded p-2 bg-white dark:bg-gray-800 text-gray-900 dark:text-white" required>
            </div>

            <!-- Notes -->
            <div class="mb-4">
                <label for="notes" class="block font-medium text-gray-800 dark:text-gray-200">Notes (optional):</label>
                <textarea name="notes" id="notes" rows="3" class="w-full border rounded p-2 bg-white dark:bg-gray-800 text-gray-900 dark:text-white"></textarea>
            </div>

            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Book Appointment</button>
        </form>

        <!-- Display validation errors if any -->
        @if ($errors->any())
            <div class="mt-4 text-red-500">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>

    <!-- JavaScript to filter dentists -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const dentistTypeSelect = document.getElementById('dentist_type');
            const dentistSelect = document.getElementById('dentist_id');

            dentistTypeSelect.addEventListener('change', function () {
                const selectedType = this.value;
                const options = dentistSelect.querySelectorAll('option');

                options.forEach(option => {
                    if (!option.value) return; // skip placeholder
                    const role = option.getAttribute('data-role');
                    option.style.display = (selectedType === '' || role === selectedType) ? 'block' : 'none';
                });

                dentistSelect.value = ''; // reset selected dentist
            });
        });
    </script>
</x-app-layout>
