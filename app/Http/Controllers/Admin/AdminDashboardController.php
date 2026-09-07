<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Member;
use Illuminate\View\View;

class AdminDashboardController extends Controller
{
  
    public function index(): View
    {
        
        $members = Member::with('savingsAccounts')
            ->latest()
            ->paginate(15);

        $totalActive = Member::where('membership_status','active')->count();



        return view('admin.dashboard', compact('members','totalActive'));
    }
}