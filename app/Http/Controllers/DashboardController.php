<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index() {
        $user                = auth()->user();
        $events              = $user->events;   
        $eventsAsParticipant = $user->eventsAsParticipant;     

        return View('events.dashboard', ['events' => $events, 'eventsasparticipant' => $eventsAsParticipant]);
    }
}
