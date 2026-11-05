<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Helpers\AuthHelper;
use App\Models\Application;
use App\Models\Game;
use App\Models\Table;
use Illuminate\Http\Request;

class RolesController extends Controller
{
    public function index($id)
    {
        $role = AuthHelper::getRole();
        $admin = AuthHelper::getAdmin();
        $permissions = $admin->permissions;
        $table = Table::with('applications', 'game')->find($id);
        $applications = Application::with('account')->where('table_id', $id)->get();

        foreach ($applications as $application) {
            $application->nickname = $application->account->nickname;
            $application->avatar = $application->account->avatar;
        }

        $players = $applications;

        return view('admin.roles', compact('role', 'admin', 'permissions', 'players', 'table'));
    }
    public function saveRoles(Request $request)
    {

        $jsonData = $request->getContent();
        $roleAssignments = json_decode($jsonData, true);

        foreach ($roleAssignments as $assignment) {
            $application = Application::find($assignment['id']);

            $account = $application->account;
            $account->role = $assignment['role'];
            $account->game_id = 1;
            $account->position = $application->position;
            $account->save();
        }


        return redirect()->back()->with('success', 'Роли успешно сохранены!');
    }
}
