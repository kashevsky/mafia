<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Helpers\AuthHelper;
use App\Models\Admin;
use App\Models\AdminClub;
use App\Models\Club;
use Illuminate\Http\Request;
    use Illuminate\Support\Facades\DB;

use function Symfony\Component\Translation\t;

class AdminController extends Controller
{
    public function index()
    {

        $role = AuthHelper::getRole();
        $admin = AuthHelper::getAdmin();
        $permissions = $admin->permissions;

        if (AuthHelper::checkForMainAdmin()) {
            $admins = Admin::with('clubs', 'childs')->get();
            $clubs = Club::get();
        } else {

            $admin = Admin::with('clubs', 'childs')->find($admin->id);

            $admins = $admin->childs;
            $clubs = $admin->clubs;
        }



        return view('admin.admin.index', compact('admins', 'clubs', 'admin', 'role', 'permissions'));
    }

    public function create()
    {
        $clubs = Club::get();
        return view('admin.admin.create', compact('clubs'));
    }



    public function store(Request $request)
    {
        $params = $request->all();

        // Оборачиваем в транзакцию, чтобы если что-то упадет, база не засорялась частичными данными
        return DB::transaction(function () use ($params) {
            $admin = Admin::create([
                'name' => $params['name'],
                'password' => $params['password'], // Не забудьте хэшировать: bcrypt($params['password'])
            ]);

            if (!AuthHelper::checkForMainAdmin()) {
                $admin->parent_id = session()->get('admin')->id;
            }

            $admin->role = $params['role'] ?? null;
            $admin->telegram_id = $params['telegram_id'] ?? null;
            $admin->permissions = (!empty($params['permissions'])) ? $params['permissions'] : null;
            $admin->save();

            // Безопасное привязывание клубов без дублей
            if (isset($params['club_ids']) && is_array($params['club_ids'])) {
                // array_unique уберет дубликаты из самого запроса, если они там были
                $admin->clubs()->attach(array_unique($params['club_ids']));
            }

            return response()->json(['success' => true, 'admins' => Admin::all()]);
        });
    }

    public function update(Request $request, $id)
    {
        $params = $request->all();

        // Ищем по $id из параметров метода, а не из $params['id'] для надежности маршрутизации
        $admin = Admin::findOrFail($id);

        if (isset($params['name']) && $params['name'] !== 'undefined') {
            $admin->name = $params['name'];
        }

        // Обновляйте пароль только если он передан и не пустой
        if (isset($params['password']) && $params['password'] !== 'undefined' && !empty($params['password'])) {
            $admin->password = $params['password'];
        }

        if (isset($params['role']) && $params['role'] !== 'undefined') {
            $admin->role = $params['role'];
        }

        $admin->permissions = (!empty($params['permissions']) && $params['permissions'] !== 'undefined')
            ? $params['permissions']
            : [];

        $admin->telegram_id = $params['telegram_id'] ?? null;
        $admin->save();

        // ГЛАВНОЕ ИСПРАВЛЕНИЕ: Метод sync() автоматически удалит старые связи
        // и запишет ТОЛЬКО новые, полностью исключая дубликаты в AdminClub
        if (isset($params['club_ids']) && is_array($params['club_ids'])) {
            $admin->clubs()->sync(array_unique($params['club_ids']));
        }

        return response()->json(['success' => true, 'admins' => Admin::all()]);
    }


    public function delete(Request $request)
    {
        $params = $request->all();
        $admin = Admin::find($params['id']);
        $admin->delete();
        $admins = Admin::get();

        return json_encode(['success' => true, 'admins' => $admins]);
    }
}
