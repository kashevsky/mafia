<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Helpers\AuthHelper;
use App\Models\Merch;
use Illuminate\Http\Request;

class MerchController extends Controller
{
    public function index()
    {
        $merches = Merch::get();
        $admin = AuthHelper::getAdmin();

        return view('admin.merch.index', compact('merches', 'admin'));
    }

    public function edit($id)
    {
        $merch = Merch::find($id);

        return view('admin.merch.edit', compact('merch'));
    }

    public function store(Request $request)
    {
        // Валидация входящих данных
        $request->validate([
            'title' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'src' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $merch = new Merch();
        $merch->title = $request->input('title');
        $merch->price = $request->input('price');
$merch->description = $request->input('description');

        // Загрузка фото через file_put_contents
        if ($request->hasFile('src')) {
            $file = $request->file('src');

            // Генерация уникального имени для предотвращения дублирования
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $uploadDir = public_path('images/merch');

            // Создаем целевую директорию, если она отсутствует на сервере
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            $destination = $uploadDir . '/' . $filename;

            // Физическое сохранение бинарного потока файла на диск
            file_put_contents($destination, file_get_contents($file->getRealPath()));

            // Запись относительного URL в бд
            $merch->src = '/images/merch/' . $filename;
        }

        $merch->save();

        // Перенаправляем на общий список товаров (измените имя роута на ваше при необходимости)
        return redirect()->route('admin.merch.index');
    }


    public function update(Request $request, $id)
    {
        $merch = Merch::findOrFail($id);

        // Обновление текстовых полей
        $merch->title = $request->input('title');
        $merch->price = $request->input('price');
        $merch->description = $request->input('description');

        // Удаление старых изображений, если они отмечены
        if ($request->has('delete_images')) {
            foreach ($request->input('delete_images') as $imageUrl) {
                // Превращаем URL в абсолютный путь на сервере
                $parsedPath = parse_url($imageUrl, PHP_URL_PATH);
                $localPath = public_path($parsedPath);

                if (file_exists($localPath)) {
                    @unlink($localPath);
                }

                // Очищаем поле в базе, если удаляемое фото совпадает с текущим
                if ($merch->src === $imageUrl) {
                    $merch->src = null;
                }
            }
        }

        // Загрузка нового фото через file_put_contents
        if ($request->hasFile('about_src_2')) {
            $file = $request->file('about_src_2');

            // Генерируем уникальное имя файла
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $uploadDir = public_path('images/merch');

            // Создаем директорию, если она не существует
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            $destination = $uploadDir . '/' . $filename;

            // Переносим файл с помощью file_put_contents и get_file_contents/getRealPath
            file_put_contents($destination, file_get_contents($file->getRealPath()));

            // Записываем путь в базу данных
            $merch->src = '/images/merch/' . $filename;
        }

        $merch->save();

        return redirect()->back();
    }


    public function delete($id)
    {
        $merch = Merch::find($id);
        $merch->delete();
        return redirect()->back();
    }

    public function create()
    {
        return view('admin.merch.create');
    }
}
