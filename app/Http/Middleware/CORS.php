<?php namespace App\Http\Middleware;

use Closure;

class CORS {

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // No extra restriction applied

        if ($request->isMethod('options'))
        {

            return response('', 200)
                ->header('Access-Control-Allow-Origin', '*')
                ->header('Access-Control-Allow-Methods', 'POST, GET, OPTIONS, PUT, DELETE')
                ->header('Access-Control-Allow-Headers', 'content-type');
        }
        else
        {

            header("Access-Control-Allow-Origin: *");
            header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');

        }

        return $next($request);
    }

}