<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Appointment;


class AppointmentActionController extends Controller
{
    public function approve(Appointment $appointment)
    {
        $appointment->update(['status' => 'confirmed']);
        return redirect()->back()->with('success', 'Appointment approved.');
    }

    public function cancel(Appointment $appointment)
    {
        $appointment->update(['status' => 'cancelled']);
        return redirect()->back()->with('success', 'Appointment cancelled.');
    }
}
