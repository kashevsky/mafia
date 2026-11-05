<?php

namespace App\Http\Middleware;

use App\Models\Account;
use Closure;
use Illuminate\Http\Request;

class AccountMiddleware
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
        $admin = session()->get('account');

        $params = $request->all();
        if(isset($params['id'])) {
            $account = Account::where('telegram_id', $params['id'])->first();
            if(!$account) {
                abort(404);
            }
            session()->put('account', $account);
        }

        return $next($request);
    }
}
