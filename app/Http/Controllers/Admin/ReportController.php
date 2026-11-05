<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Helpers\AuthHelper;
use App\Models\Club;
use App\Models\PayEntity;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index()
    {
        $admin = AuthHelper::getAdmin();
        $clubs = Club::get();
        $payments = PayEntity::orderBy('type', 'desc')->with('account', 'game', 'club', 'table')->where('is_confirmed', 1)->get();


        return view('admin.report.index', compact('admin', 'payments', 'clubs'));
    }
}
