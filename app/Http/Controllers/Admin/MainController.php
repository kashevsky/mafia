<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Helpers\AuthHelper;
use App\Http\Helpers\TgHelper;
use App\Models\AccountLink;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class MainController extends Controller
{
    public function index()
    {
        $admin = AuthHelper::getAdmin();


        return view('admin.index', compact('admin'));
    }

    public function makeLink()
    {


        $admin = session()->get('admin');
        $admin = Admin::with('clubs')->find($admin->id);
        $club_id = $admin->clubs[0]->id;
        $code = Str::random(16);

        AccountLink::create([
            'code' => $code,
            'club_id' => $club_id
        ]);


        $link = url('/invite/' . $code);



        return response()->json(['link' => $link]);
    }
}
