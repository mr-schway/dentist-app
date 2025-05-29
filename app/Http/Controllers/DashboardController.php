<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Appointment;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->is_admin) {
            return redirect()->route('admin.dashboard');
        }

        $pendingAppointments = [];

        if (in_array($user->role, [
            'general-dentist', 'orthodontist', 'endodontist', 'periodontist', 'prosthodontist'
        ])) {
            $pendingAppointments = $user->dentistAppointments()
                                        ->where('status', 'pending')
                                        ->orderBy('appointment_date')
                                        ->get();
        }

        return view('dashboard', compact('pendingAppointments'));
    }
}