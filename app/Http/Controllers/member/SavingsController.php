<?php

namespace App\Http\Controllers\member;

use App\Http\Controllers\Controller;
use App\Models\Member;
use Auth;
use Illuminate\View\View;

class SavingsController extends Controller
{
    //
    public function show($accountNumber) : View {
        
        $member = Member::where('user_id',Auth::id())->firstOrFail();

        $savingsAccount = $member->savingsAccounts()
        ->with('transactions')
        ->where('account_number', $accountNumber)->firstOrFail();

        return view('member.savings.show',compact('savingsAccount'));
        
    
    }


}
