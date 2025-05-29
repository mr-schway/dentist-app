<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User; // ✅ This line is required

class AdminController extends Controller
{
    public function index()
    {
        $users = User::where('is_approved', false)->get();
        return view('admin.dashboard', compact('users'));
    }
}
