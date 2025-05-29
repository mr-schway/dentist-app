<?php

namespace App\Http\Controllers\Admin;

use App\Models\Appointment;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminAppointmentController extends Controller
{
    public function index()
    {
        $appointments = Appointment::with(['patient', 'dentist'])->latest()->paginate(10);
        return view('admin.appointments.index', compact('appointments'));
    }

    public function edit(Appointment $appointment)
    {
        return view('admin.appointments.edit', compact('appointment'));
    }

    public function update(Request $request, Appointment $appointment)
    {
        $request->validate([
            'appointment_time' => 'required|date_format:H:i',
            'appointment_date' => 'required|date',
            'status' => 'required|in:pending,confirmed,cancelled',
            'notes' => 'nullable|string',
        ]);

        $appointment->update([
            'status' => $request->status,
            'notes' => $request->notes,
            'appointment_date' => $request->appointment_date,
            'appointment_time' => $request->appointment_time,
        ]);

        return redirect()->route('admin.appointments.index')->with('success', 'Appointment updated.');
    }


    public function destroy(Appointment $appointment)
    {
        $appointment->delete();
        return back()->with('success', 'Appointment deleted.');
    }
}
