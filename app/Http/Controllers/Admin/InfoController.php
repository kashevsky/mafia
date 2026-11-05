<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Info;
use Illuminate\Http\Request;

class InfoController extends Controller
{
    public function edit()
    {
        $info = Info::first();
        return view('admin.info.edit', compact('info'));
    }

    public function update(Request $request)
    {
        $params = $request->all();
        $info = Info::first();
        $info->delivery_text = $request->delivery_text;
        $info->about_text = $request->about_text;

        if ($request->hasFile('about_src')) {
            $file = $request->file('about_src');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            file_put_contents(public_path('img/' . $filename), file_get_contents($file));
            $info->about_src = '/img/' . $filename;
        }


        if ($request->hasFile('about_src_2')) {
            $file = $request->file('about_src_2');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            file_put_contents(public_path('img/' . $filename), file_get_contents($file));
            $info->about_src_2 = '/img/' . $filename;
        }

        $info->phone = $request->phone;
        $info->email = $request->email;
        $info->save();
        return redirect()->route('admin.info.edit');
    }
}
