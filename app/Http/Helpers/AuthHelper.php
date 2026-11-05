<?php

namespace App\Http\Helpers;

use App\Models\Admin;



class AuthHelper

{


    public static function getAdmin()
    {
        $admin = session()->get('admin');
        $admin = Admin::with('clubs', 'childs')->find($admin->id);

        return $admin;
    }
    public static function getRole()
    {

        $admin = session()->get('admin');



        $role = $admin->role;

        return $role;
    }

    public static function getClubId()
    {
        $admin = session()->get('admin');

        $admin = Admin::with('clubs', 'childs')->find($admin->id);
        if (isset($admin->clubs[0])) {
            $club_id = $admin->clubs[0]->id;
        } else {
            $club_id = 2;
        }


        return $club_id;
    }

    public static function checkForMainAdmin()
    {

        $admin = session()->get('admin');


        return $admin->is_main_admin;
    }
}
