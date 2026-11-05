<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Helpers\AuthHelper;
use App\Models\Admin;
use App\Models\City;
use App\Models\Club;
use App\Models\GamePhoto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ClubController extends Controller
{
    public function index()
    {
        $role = AuthHelper::getRole();
        $admin = AuthHelper::getAdmin();
        $permissions = $admin->permissions;


        if ($role == 'admin') {
            $clubs = $admin->clubs;
            $cities = City::get();
            return view('admin.club.index', compact('clubs', 'cities', 'admin', 'permissions'));
        } elseif ($role == 'superadmin') {
            $clubs = Club::get();
            $cities = City::get();

            return view('admin.club.index', compact('clubs', 'cities', 'admin', 'permissions'));
        }
    }

    public function edit($id)
    {
        $role = AuthHelper::getRole();
        $admin = AuthHelper::getAdmin();
        $club_id = AuthHelper::getClubId();


        // if ($role == 'admin') {
        //     if ($id != $club_id) {
        //         return redirect()->route('admin.club.index');
        //     }
        // }

        $club = Club::with('videos')->find($id);

        $cities = City::get();


        return view('admin.club.edit', compact('club', 'cities', 'admin'));
    }

    public function create()
    {
        $cities = City::get();
        $admin = AuthHelper::getAdmin();

        return view('admin.club.create', compact('cities', 'admin'));
    }

    public function store(Request $request)
    {
        $params = $request->all();

        $club = Club::create([
            'title' => $params['title'],
            'totem' => $params['totem'],
        ]);

        if (isset($params['city_id'])) {
            $club->city_id = $params['city_id'];
        }
        if (isset($params['description'])) {
            $club->description = $params['description'];
        }
        if (isset($params['address'])) {
            $club->address = $params['address'];
        }

        if (isset($params['avatar']) && $params['avatar']) {
            $file = $params['avatar'];
            $name = $file->getClientOriginalName();
            $file->move(public_path('uploads/slides'), $name);
            $club->avatar = '/uploads/slides/' . $name;
        }

        // Обработка добавления новых видео
        $newVideos = $request->input('videos', []);
        $files = $request->file('videos');
        foreach ($newVideos as $index => $videoData) {
            $file = $files[$index]['file'];
            $name = Str::random(16) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/videos'), $name);

            $previewPath = null;
            if (isset($files[$index]['preview']) && $files[$index]['preview']->isValid()) {
                $previewFile = $files[$index]['preview'];
                $previewName = Str::random(16) . '.' . $previewFile->getClientOriginalExtension();
                $previewFile->move(public_path('uploads/previews'), $previewName);
                $previewPath = '/uploads/previews/' . $previewName;
            }

            $club->videos()->create([
                'title'   => $videoData['title'],
                'src'     => '/uploads/videos/' . $name,
                'preview' => $previewPath,
                'club_id' => $club->id,
            ]);
        }

        $club->save();
        return redirect()->route('admin.club.edit', $club->id);
    }

    public function update(Request $request)
    {
        $params = $request->all();

        $club = Club::find($params['id']);

        if (isset($params['title'])) {
            $club->title = $params['title'];
        }
        if (isset($params['totem'])) {
            $club->totem = $params['totem'];
        }
        if (isset($params['city_id'])) {
            $club->city_id = $params['city_id'];
        }

        if (isset($params['description'])) {
            $club->description = $params['description'];
        }
        if (isset($params['address'])) {
            $club->address = $params['address'];
        }
        if ($request->input('delete_avatar')) {
            // удалить файл из хранилища

            $club->avatar = null;
        } elseif ($request->hasFile('avatar')) {

            $file = $request->file('avatar');

            // Создаём папку, если её нет
            $dir = public_path('uploads/clubs/avatars');
            if (!is_dir($dir)) {
                mkdir($dir, 0755, true);
            }

            // hashName() — именно он убирает кириллицу и делает безопасное имя
            $filename = $file->hashName();
            $file->move($dir, $filename);

            $club->avatar = '/uploads/clubs/avatars/' . $filename;
        }

        // 1. Обработка удаления существующих видео
        $videosToDelete = $request->input('delete_videos', []);
        if (!empty($videosToDelete)) {
            $club->videos()
                ->whereIn('id', $videosToDelete)
                ->delete();
        }

        // 2. Обработка обновления существующих видео
        $existingFiles = $request->file('existing_videos', []);
        $existingVideos = $request->input('existing_videos', []);
        foreach ($existingVideos as $videoId => $videoData) {
            $video = $club->videos()->find($videoId);
            if (!$video) {
                continue;
            }

            $video->title = $videoData['title'] ?? $video->title;

            // Замена видеофайла
            if (isset($existingFiles[$videoId]['file']) && $existingFiles[$videoId]['file']->isValid()) {
                $file = $existingFiles[$videoId]['file'];
                $name = Str::random(16) . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads/videos'), $name);
                $video->src = '/uploads/videos/' . $name;
            }

            // Замена превью
            if (isset($existingFiles[$videoId]['preview']) && $existingFiles[$videoId]['preview']->isValid()) {
                $previewFile = $existingFiles[$videoId]['preview'];
                $previewName = Str::random(16) . '.' . $previewFile->getClientOriginalExtension();
                $previewFile->move(public_path('uploads/previews'), $previewName);
                $video->preview = '/uploads/previews/' . $previewName;
            }

            $video->save();
        }

        // 3. Обработка добавления новых видео
        $newVideos = $request->input('new_videos', []);
        $files = $request->file('new_videos');

        foreach ($newVideos as $index => $videoData) {
            if (!isset($files[$index]['file'])) {
                continue;
            }

            $file = $files[$index]['file'];
            if (!($file instanceof \Illuminate\Http\UploadedFile) || !$file->isValid()) {
                continue;
            }

            $name = Str::random(16) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/videos'), $name);

            $previewPath = null;
            if (isset($files[$index]['preview']) && $files[$index]['preview']->isValid()) {
                $previewFile = $files[$index]['preview'];
                $previewName = Str::random(16) . '.' . $previewFile->getClientOriginalExtension();
                $previewFile->move(public_path('uploads/previews'), $previewName);
                $previewPath = '/uploads/previews/' . $previewName;
            }

            $club->videos()->create([
                'title'   => $videoData['title'],
                'src'     => '/uploads/videos/' . $name,
                'preview' => $previewPath,
            ]);
        }

        $club->save();
        return redirect()->route('admin.club.edit', $club->id);
    }


    public function delete($id)
    {

        $club = Club::find($id);
        $club->delete();
        return redirect()->route('admin.club.index');
    }
}
