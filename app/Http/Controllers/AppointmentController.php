<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;


class AppointmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();

        $dentistRoles = [
            'general-dentist',
            'orthodontist',
            'endodontist',
            'periodontist',
            'prosthodontist',
        ];

        if (in_array($user->role, $dentistRoles)) {
            $appointments = $user->dentistAppointments()->with('patient')->get();
        } elseif ($user->role === 'patient') {
            $appointments = $user->patientAppointments()->with('dentist')->get();
        } else {
            $appointments = collect();
        }

        return view('appointments.index', compact('appointments', 'user'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $dentists = User::whereIn('role', [
            'general-dentist',
            'orthodontist',
            'endodontist',
            'periodontist',
            'prosthodontist'
        ])->get();

        return view('appointments.create', compact('dentists'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'appointment_date' => 'required|date|after_or_equal:today',
            'appointment_time' => 'required',
            'notes' => 'nullable|string',
        ]);

        // Combine date and time to compare against current datetime
        $appointmentDateTime = \Carbon\Carbon::parse($request->appointment_date . ' ' . $request->appointment_time);
        if ($appointmentDateTime->lessThan(now())) {
            return back()->withErrors(['appointment_time' => 'The appointment time must be in the future.'])->withInput();
        }

        Appointment::create([
            'patient_id' => Auth::id(),
            'dentist_id' => $request->dentist_id,
            'appointment_date' => $request->appointment_date,
            'appointment_time' => $request->appointment_time,
            'notes' => $request->notes,
            'status' => 'pending',
        ]);

        return redirect()->route('appointments.index')->with('success', 'Appointment created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Appointment $appointment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Appointment $appointment)
    {
        // Check if current user is the patient and allowed to edit
        if (auth()->user()->id !== $appointment->patient_id) {
            abort(403);
        }

        $appointmentDateTime = Carbon::parse($appointment->appointment_date . ' ' . $appointment->appointment_time);

        if (now()->diffInHours($appointmentDateTime, false) < 6) {
            return redirect()->route('appointments.index')->with('error', 'You can only edit appointments at least 6 hours in advance.');
        }

        return view('appointments.edit', compact('appointment'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Appointment $appointment)
    {
        // Validate input
        $request->validate([
            'appointment_date' => 'required|date|after_or_equal:today',
            'appointment_time' => 'required',
            'notes' => 'nullable|string',
        ]);

        // Combine date and time to compare with current time + 6 hours
        $appointmentDateTime = \Carbon\Carbon::parse($request->appointment_date . ' ' . $request->appointment_time);
        $nowPlus6 = now()->addHours(6);

        // Check if it's too late to edit
        if ($appointmentDateTime->lessThan($nowPlus6)) {
            return redirect()->route('appointments.index')
                            ->with('error', 'You can only edit appointments at least 6 hours in advance.');
        }

        // Update and reset status
        $appointment->update([
            'appointment_date' => $request->appointment_date,
            'appointment_time' => $request->appointment_time,
            'notes' => $request->notes,
            'status' => 'pending', // reset status to pending
        ]);

        return redirect()->route('appointments.index')
                        ->with('success', 'Appointment updated and set to pending.');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Appointment $appointment)
    {
        if (auth()->user()->id !== $appointment->patient_id) {
            abort(403);
        }

        $appointmentDateTime = Carbon::parse($appointment->appointment_date . ' ' . $appointment->appointment_time);
        if (now()->diffInHours($appointmentDateTime, false) < 6) {
            return redirect()->route('appointments.index')->with('error', 'You can only cancel appointments at least 6 hours in advance.');
        }

        $appointment->status = 'cancelled';
        $appointment->save();

        return redirect()->route('appointments.index')->with('success', 'Appointment cancelled successfully.');
    }

}
