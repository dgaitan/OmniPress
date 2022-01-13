<?php

namespace App\Http\Controllers\Auth;

use App\Models\Team;
use App\Models\Organization;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

class VerifyEmailController extends Controller
{
    /**
     * Mark the authenticated user's email address as verified.
     *
     * @param  \Illuminate\Foundation\Auth\EmailVerificationRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function __invoke(EmailVerificationRequest $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended(RouteServiceProvider::HOME.'?verified=1');
        }

        if ($request->user()->markEmailAsVerified()) {
            $user = $request->user();
            $team = Team::forceCreate([
                'user_id' => $user->id,
                'name' => explode(' ', $user->name, 2)[0]."'s Team",
                'personal_team' => true,
            ]);
            $team->save();

            $team->organization()->save(Organization::forceCreate([
                'name' => $team->name
            ]));

            $user->ownedTeams()->save($team);
            event(new Verified($user));
        }

        return redirect()->intended(RouteServiceProvider::HOME.'?verified=1');
    }
}
