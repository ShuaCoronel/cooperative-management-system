<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Member;
use Illuminate\View\View;

class LedgerController extends Controller
{
    //show savings transaction
    public function show(Member $member): View {

    //anchor the member parameter to access query
    $savingsAccounts = $member->savingsAccounts()
        ->with(['member','transactions' => function($q) { $q->orderBy('transaction_date','desc')->orderBy('id','desc');
        }])->get();


        return view('admin.ledger.show', compact('savingsAccounts'));
    
    }
}
