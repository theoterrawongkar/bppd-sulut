<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DashboardUserController extends Controller
{
    // UserController
    public function toggleStatus(User $user)
    {
        // Toggle Akun
        $user->is_active = !$user->is_active;
        $user->save();

        return back()->with('success', 'Status akun berhasil diperbarui.');
    }
}
