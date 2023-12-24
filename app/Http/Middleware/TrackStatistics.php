<?php

namespace App\Http\Middleware;

use Closure;
use Jenssegers\Agent\Agent;
use Illuminate\Http\Request;
use App\Models\admin\Statistic;

class TrackStatistics
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $url = $request->url();

        if (str_contains($url, '/admin') ) {
            return $next($request);
        } else {
            $statistics = new Statistic();
            $statistics->ip_address = $request->ip();
            $statistics->url = str_replace(route('web.index'), '', $url);

            $agent = new Agent();
            $statistics->device = $agent->device();
            $statistics->platform = $agent->platform();
            $statistics->browser = $agent->browser();
            $statistics->visit_time = now();
            $statistics->save();
        }

        return $next($request);
    }
}
