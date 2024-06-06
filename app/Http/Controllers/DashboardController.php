<?php

namespace App\Http\Controllers;

use App\User;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function dashboard()
    {
        $now = Carbon::now();

        $data = [];

        for ($i=12; $i >= 0; $i--) {
            $month = $now->copy()->subMonths($i);

            $first = $month->copy()->startOfMonth();
            $last = $month->copy()->endOfMonth();

            $data[$i]['month'] = $month->format('M Y');

            $users = User::whereBetween('created_at', [$first->format('Y-m-d 00:00:00'), $last->format('Y-m-d 23:59:59')])->get();

            $data[$i]['users'] = $users->count();
        }

        $monthAgo = $now->copy()->subMonth();
        $new_users = User::where('id', '!=', 1)->whereBetween('created_at', [$monthAgo->format('Y-m-d 00:00:00'), $now->format('Y-m-d 23:59:59')])->orderBy('created_at', 'DESC');

        $users['new_users_count'] = $new_users->count();
        $users['new_users'] = $new_users->take(9)->get();

        $users['active_users'] = User::where('banned', 0)->whereNotNull('email_verified_at')->count();
        $users['banned_users'] = User::where('banned', 1)->count();
        $users['total_users'] = User::count();

        return view('app.dashboard', ['data' => $data, 'users' => $users]);
    }

}
