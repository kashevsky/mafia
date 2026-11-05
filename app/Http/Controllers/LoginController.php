<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Club;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function index()
    {
        $account = session()->get('account');
        if ($account) {
            $account = Account::find($account->id);
        }
        return view('login', compact('account'));
    }

    public function make(Request $request)
    {
        $params = $request->all();
        $nickname = $params['nickname'];
        $password = $params['password'];

        $account = Account::where('nickname', $nickname)->where('password', $password)->first();

        if ($account) {
            session()->put('account', $account);
        } else {
            return redirect()->route('login');
        }

        return redirect()->route('club.index');
    }

    public function register()
    {
        $account = session()->get('account');
        if ($account) {
            $account = Account::find($account->id);
        }
        return view('register', compact('account'));
    }

    public function registerMake(Request $request)
    {

        $params = $request->all();

        $account = Account::where('nickname', $params['nickname'])->first();

        if ($account) {
            return redirect()->route('register');
        }
        $a = Account::create([
            'nickname' => $params['nickname'],
            'password' => $params['password'],
            'club_id' => Club::first()->id
        ]);

        session()->put('account', $a);

        return redirect()->route('club.index');
    }
}
