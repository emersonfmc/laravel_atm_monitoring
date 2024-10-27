<?php

namespace App\Listeners;

use Illuminate\Support\Facades\Auth;

class UpdateUserSessionStatus
{
    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        // Check if the user is logged in
        if (Auth::check()) {
            // Update the user's session status to 'Offline'
            $user = Auth::user();
            $user->session = 'Offline';
            $user->save();
        }
    }
}

