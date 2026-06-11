<?php

namespace App\Http\Controllers\Auth;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Models\Member;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);



        // initialized $member variable, check if the email exist in members data
        $member= Member::where('email', $request->email)->first();




        //if $member email was not in the data base reject the user registration 
        if(!$member) {
        
            return back()->withErrors([
            'email'=> 'Email was not registered as a member, please contact and coordinate with the cooperative admin to register']

            )->withInput();
        };



        // if member user-id foreign key is not null, means it has an email value
        // then it must be registered already or use other email
        if($member->user_id !== null) {
            return back()->withErrors([
                
            'email'=> 'email has already been registered
            please contact cooperative admin for details
            '])->withInput();

        };


        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => UserRole::MEMBER, // default role must be member
        ]);


        // now we need to update the member data, use the useraccount id to change the 
        // member data user id null to its newly created user data id, the foreign key
    
        $member->update([
        'user_id'=> $user->id

        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}
