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
    public function handle(Request $request, $user_id, Closure $next): Response
    {

        //check if previously visited or not
        $visitedProfile = VisitedProfile::where('user_id', auth()->user()->id)->where('visited_user_id', $user_id)->first();

        if (!$visitedProfile) {
            $visitedProfile = new VisitedProfile();
            $visitedProfile->user_id = auth()->user()->id;
            $visitedProfile->visited_user_id = $request->user_id;
            $visitedProfile->save();
        }

        if ($visitedProfile->count > 0) {
            $visitedProfile->count = $visitedProfile->count - 1;
            $visitedProfile->save();

            return $next($request);
        }


        //check has connection or not
        $connection = Connection::where('user_id', auth()->user()->id)->firstOrCreate();

        if ($connection->connection > 0) {
            //first or new
            $visitedProfile = VisitedProfile::where('user_id', auth()->user()->id)->where('visited_user_id', $user_id)->firstOrCreate();

            $visitedProfile->count = 10;
            $visitedProfile->save();

            $connection->connection = $connection->connection - 1;
            $connection->save();
        } else {
            return response()->back()->with('error', 'You have no more connections');
        }
        return $next($request);
    }
}
