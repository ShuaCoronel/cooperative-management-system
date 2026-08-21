<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Member;
use Illuminate\View\View;

class AdminDashboardController extends Controller
{
    /**
     * Display the admin dashboard with a list of members and their accounts.
     */
    public function index(): View
    {
        // Query members and eager-load their savings accounts.
        // We use pagination (15 per page) to prevent O(N) memory crashes.
        $members = Member::with('savingsAccounts')
            ->latest()
            ->paginate(15);

        return view('admin.dashboard', compact('members'));
    }
}