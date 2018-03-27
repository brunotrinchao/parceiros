<?php

namespace App\Http\Middleware;

use Closure;
use App\Helpers\Helper;
class Autorizador
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $permission)
    {
        $permission = explode('|', $permission);
        
        if(Helper::checkPermission($permission)){
            return $next($request);
        }

        return response()->view('admin.imoveis.home.index');
    }
}
