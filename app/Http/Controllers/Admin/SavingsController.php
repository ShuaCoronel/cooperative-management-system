<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SavingsAccount;
use Illuminate\View\View;

class SavingsController extends Controller
{
    //show savings transaction
    public function show(SavingsAccount $savingsAccount): View {

        $savingsAccount->load(['member','transactions'=> function ($q) {

            $q->orderBy('transaction_date', 'desc')->orderBy('id','desc');
            
            }
        ]);

        return view('admin.savings.show', compact('savingsAccount'));
    
    }
}
