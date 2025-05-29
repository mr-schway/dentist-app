<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Dashboard') }}
            </h2>

            @if(Auth::user()->role === 'patient')
                <a href="{{ route('appointments.create') }}" 
                   class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded text-sm">
                    Book New Appointment
                </a>
            @endif
        </div>
    </x-slot>

    @if(Auth::user()->role === 'patient' && !Auth::user()->is_admin)
        <x-specialty-section />
    @endif

    @if(in_array(Auth::user()->role, [
        'general-dentist', 'orthodontist', 'endodontist', 'periodontist', 'prosthodontist'
    ]) && !Auth::user()->is_admin)
        <section class="max-w-4xl mx-auto mt-10 px-6 py-8 bg-white dark:bg-gray-900 rounded-lg shadow">
            <h2 class="text-2xl font-semibold mb-6 text-gray-900 dark:text-white">Pending Appointments</h2>

            @if($pendingAppointments->isEmpty())
                <p class="text-gray-700 dark:text-gray-300">No pending appointments.</p>
            @else
                <ul class="space-y-4 text-gray-800 dark:text-gray-200">
                    @foreach($pendingAppointments as $appointment)
                        <li class="border p-4 rounded bg-gray-50 dark:bg-gray-800">
                            <strong>Patient:</strong> {{ $appointment->patient->name }}<br>
                            <strong>Date:</strong> {{ $appointment->appointment_date }}<br>
                            <strong>Time:</strong> {{ $appointment->appointment_time }}<br>
                            <strong>Notes:</strong> {{ $appointment->notes ?? 'None' }}<br>

                            <div class="mt-2 flex gap-2">
                                <form method="POST" action="{{ route('appointments.approve', $appointment) }}">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded text-sm">
                                        Approve
                                    </button>
                                </form>

                                <form method="POST" action="{{ route('appointments.cancel', $appointment) }}">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded text-sm">
                                        Cancel
                                    </button>
                                </form>
                            </div>
                        </li>
                    @endforeach
                </ul>
            @endif
        </section>
    @endif

</x-app-layout>
