<?php

namespace App\Http\Controllers\member;

use App\Http\Controllers\Controller;
use App\Models\Member;
use Auth;
use Illuminate\View\View;

class ShowSavingsController extends Controller
{
    //
    public function show($ulid) : View {
        
        $member = Member::where('user_id',Auth::id())->firstOrFail();

        $savingsAccount = $member->savingsAccounts()
        ->with('transactions')
        ->where('ulid', $ulid)->firstOrFail();

        return view('member.savings.show',compact('savingsAccount'));
        
    
    }


}
