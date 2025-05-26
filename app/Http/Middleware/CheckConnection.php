<?php

namespace App\Http\Middleware;

use App\Models\Connection;
use App\Models\VisitedProfile;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckConnection
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $profileId = $request->route('profileId');

        if (!auth()->check()) {
            return response()->back()->with('error', 'You must be logged in to view this profile');
        }

        if (auth()->user()->id == $profileId) {
            return $next($request);
        }

        //check if previously visited or not
        $visitedProfile = VisitedProfile::where('user_id', auth()->user()->id)->where('visited_user_id', $profileId)->first();

        if (!$visitedProfile) {
            $visitedProfile = new VisitedProfile();
            $visitedProfile->user_id = auth()->user()->id;
            $visitedProfile->visited_user_id = $profileId;
            $visitedProfile->save();
        }

        if ($visitedProfile->count > 0) {
            $visitedProfile->count = $visitedProfile->count - 1;
            $visitedProfile->save();

            return $next($request);
        }


        //check has connection or not
        $connection = Connection::where('user_id', auth()->user()->id)->firstOrCreate(
            ['user_id' => auth()->user()->id]
        );

        if ($connection->connection > 0) {
            //first or new
            $visitedProfile = VisitedProfile::where('user_id', auth()->user()->id)->where('visited_user_id', $profileId)->firstOrCreate();

            $visitedProfile->count = 10;
            $visitedProfile->save();

            $connection->connection = $connection->connection - 1;

            $connection->save();
        } else {
            return redirect()->with('error', 'You have no connections left to view this profile');
        }
        return $next($request);
    }
}
