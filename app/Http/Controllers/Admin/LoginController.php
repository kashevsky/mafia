<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Helpers\CountHelper;
use App\Models\Admin;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function index()
    {

        return view('admin.login');
    }

    public function make(Request $request)
    {
        $params = $request->all();
        $name = $params['name'];
        $password = $params['password'];

        $admin = Admin::where('name', $name)->where('password', $password)->first();

        if($admin) {
            session()->put('admin', $admin);
        } else{
            return redirect()->route('admin.login');
        }

        return redirect()->route('admin.index');
    }

    public function logout()
    {
        session()->forget('admin');
        return redirect()->route('admin.login');
    }
}
