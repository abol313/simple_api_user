<?php
namespace App\Http\Middleware;
use Closure;

class RedirectToGetUserMiddleware {
    function handle($request, Closure $closure){
        if($request->has('id'))
            return redirect()->route('routeGetUser',['id'=>$request->id]);
        return $closure($request);
    }
    function terminate($request,$response){
        var_dump($request);
        var_dump($response);
    }
}