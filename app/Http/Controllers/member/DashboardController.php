<?php

namespace App\Http\Controllers\member;

use App\Http\Controllers\Controller;
use App\Models\Member;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    //
    public function index(): View {

    $member = Member::where('user_id', Auth::id())
    ->with([
        'savingsAccounts.transactions',
        'shareCapitalTransactions',
        'dividendAllocations',
        'loans' => function($query) {
            $query->whereIn('status',['active','fully_paid'])->with('product');

        }
        ])->firstOrFail();

    // temporary solution, option is to make custom link function in loan model using where to sort there the active and non active
    $activeLoans= $member->loans->where('status','active');

    return view('member.dashboard', compact('member','activeLoans'));
    }


}
