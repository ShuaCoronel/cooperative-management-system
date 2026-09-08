<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Member;
use App\Models\ShareCapitalTransaction;
use Illuminate\View\View;

class AdminDashboardController extends Controller
{
  
    public function index(): View
    {
        
        $members = Member::withCount('savingsAccounts','loans')
            ->latest()
            ->paginate(15);

        $totalActive = Member::where('membership_status','active')->count();
        $totalSharedCapital = ShareCapitalTransaction::totalSharedCapital();



        return view('admin.dashboard', compact('members','totalActive','totalSharedCapital'));
    }
}