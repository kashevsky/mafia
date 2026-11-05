<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Helpers\AuthHelper;
use App\Models\Account;
use App\Models\Club;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class AccountController extends Controller
{
    public function index()
    {
        $role = AuthHelper::getRole();
        $admin = AuthHelper::getAdmin();
        $permissions = $admin->permissions;
        if ($role == 'admin') {
            $club_id = AuthHelper::getClubId();
            $accounts = Account::where('club_id', $club_id)->get();
            return view('admin.account.index', compact('accounts', 'admin', 'permissions'));
        } else {
            $accounts = Account::with('club')->get();

            $clubs = Club::get();
            return view('admin.account.index', compact('accounts', 'clubs', 'admin', 'permissions'));
        }
    }

    public function edit($id)
    {
        $account = Account::find($id);
        $clubs = Club::get();
        $admin = AuthHelper::getAdmin();
        return view('admin.account.edit', compact('account', 'clubs', 'admin'));
    }

    public function create()
    {
        $admin = AuthHelper::getAdmin();
        $clubs = Club::get();
        return view('admin.account.create', compact('admin', 'clubs'));
    }

    public function update(Request $request)
    {

        $params = $request->all();
        dd($params);

        $id = $params['id'];

        $account = Account::find($id);
        // Обновляем все поля модели данными из запроса (кроме аватара и флага удаления)
        $updateData = $request->except(['avatar', 'delete_avatar']);

        $account->update($updateData);

        // Обрабатываем загрузку нового аватара
        if ($request->hasFile('avatar')) {
            // Удаляем старый аватар, если он существует
            if ($account->avatar && file_exists(public_path($account->avatar))) {
                unlink(public_path($account->avatar));
            }

            // Сохраняем новый аватар
            $file = $request->file('avatar');
            $filename = uniqid() . '_' . $file->getClientOriginalName();
            $path = public_path('uploads/avatars/' . $filename);

            file_put_contents($path, file_get_contents($file->getRealPath()));

            // Обновляем поле avatar у модели
            $account->avatar = '/uploads/avatars/' . $filename;
            $account->save();
        }

        // Обрабатываем удаление аватара (если установлен флаг delete_avatar)
        if ($request->input('delete_avatar')) {
            if ($account->avatar && file_exists(public_path($account->avatar))) {
                unlink(public_path($account->avatar));
            }
            $account->avatar = null;
            $account->save();
        }

        // Перенаправляем на страницу редактирования с сообщением об успехе
        return redirect()->back()->with('success', 'Аккаунт успешно обновлен!');
    }

    public function store(Request $request)
    {
        // Создаём новый аккаунт с данными из запроса (без аватара)
        $accountData = $request->except('avatar');
        $account = Account::create($accountData);

        // Обрабатываем загрузку аватара, если файл был загружен
        if ($request->hasFile('avatar')) {
            $file = $request->file('avatar');
            $filename = uniqid() . '_' . $file->getClientOriginalName();
            $path = public_path('uploads/avatars/' . $filename);

            // Сохраняем файл через file_put_contents
            file_put_contents($path, file_get_contents($file->getRealPath()));

            // Обновляем модель аккаунта — сохраняем путь к аватару
            $account->avatar = '/uploads/avatars/' . $filename;
            $account->save();
        }

        // Перенаправляем на список аккаунтов с сообщением об успехе
        return Redirect::route('admin.account.index')
            ->with('success', 'Аккаунт успешно создан!');
    }

    public function delete($id)
    {
        $account = Account::find($id);
        $account->delete();
        return Redirect::route('admin.account.index')
            ->with('success', 'Аккаунт успешно удален!');
    }


}
