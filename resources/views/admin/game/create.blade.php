@extends('admin.layouts.main')
@section('content')

    <div class="page-header">
        <h1><i class="fas fa-plus-circle"></i> Создание игры</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/admin/dashboard">Главная</a></li>
                <li class="breadcrumb-item"><a href="/admin/products">Игры</a></li>
                <li class="breadcrumb-item active">Создать</li>
            </ol>
        </nav>
    </div>

    <form action="{{ route('admin.game.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <!-- Основная информация -->
        <div class="content-card">
            <h2 class="section-title"><i class="fas fa-info-circle"></i> Основная информация</h2>

            <div class="row">
                <div class="col-md-8 mb-3">
                    <label for="title" class="form-label">Название игры *</label>
                    <input type="text" class="form-control" id="title" name="title" value="{{ old('title') }}"
                        required>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="address" class="form-label">Место проведения</label>
                    <input type="text" class="form-control" id="address" name="address" value="{{ old('address') }}">
                </div>

                <div class="col-md-4 mb-3">
                    <label for="price" class="form-label">Стоимость</label>
                    <input type="text" class="form-control" id="price" name="price" value="{{ old('price') }}">
                </div>
                <div class="col-md-4 mb-3">
                    <label for="total_space" class="form-label">Количество мест</label>
                    <input type="number" class="form-control" id="total_space" name="total_space" value="{{ old('total_space') }}">
                </div>

@if ($admin->role == 'superadmin' || in_array('clubs.delete', $permissions))
    <div class="col-md-6 mb-3">
        <label for="club_id" class="form-label">Клуб *</label>
        <select class="form-control" id="club_id" name="club_id" required>
            <option value="" disabled {{ !old('club_id') ? 'selected' : '' }}>Выберите клуб</option>
            @foreach ($clubs as $club)
                <option value="{{ $club->id }}" {{ old('club_id') == $club->id ? 'selected' : '' }}>
                    {{ $club->title }} - {{ $club->id }}
                </option>
            @endforeach
        </select>
    </div>
@else
    <!-- ИСПРАВЛЕНО: Динамически передаем ID родного клуба админа, либо ID первого существующего клуба из коллекции -->
    <input type="hidden" name="club_id" value="{{ $admin->club_id ?? ($clubs->first()->id ?? 1) }}">
@endif



                <!-- Дата мероприятия -->
                <div class="col-md-6 mb-3">
                    <label for="event_date" class="form-label">Дата мероприятия *</label>
                    <input type="date" class="form-control" id="event_date" name="event_date"
                        value="{{ old('event_date') }}" required>
                </div>

                <!-- Время проведения -->
                <div class="col-md-6 mb-3">
                    <label for="event_time" class="form-label">Время проведения *</label>
                    <input type="time" class="form-control" id="event_time" name="event_time"
                        value="{{ old('event_time') }}" required>
                </div>

                <!-- Описание -->
                <div class="col-md-12 mb-3">
                    <label for="description" class="form-label">Описание</label>
                    <textarea class="form-control summernote" id="description" name="description">{{ old('description') }}</textarea>
                </div>
            </div>
        </div>

        <!-- Аватар игры -->
        <div class="content-card">
            <h2 class="section-title"><i class="fas fa-user-circle"></i> Аватар игры</h2>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="avatar" class="form-label">Главное изображение (аватар)</label>
                    <div class="avatar-upload-preview">
                        <img src="{{ asset('images/default-game-avatar.png') }}" alt="Нет аватара" id="avatarPreview"
                            class="img-thumbnail" style="height: 150px; object-fit: cover; border-radius: 12px;">
                    </div>
                    <input type="file" class="form-control mt-2" name="avatar" id="avatarInput" accept="image/*"
                        onchange="previewAvatar()">
                    <small class="text-muted">Рекомендуется квадратное изображение (например, 300×300)</small>
                </div>
            </div>
        </div>

        <!-- Блок: Показывать в слайдере и обложка -->
        <div class="content-card">
            <h2 class="section-title"><i class="fas fa-image"></i> Слайдер и обложка</h2>
            <div class="row">
                <div class="col-md-12 mb-3">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="is_in_slider" name="is_in_slider"
                            value="1" {{ old('is_in_slider') ? 'checked' : '' }}
                            onchange="toggleCoverBlock(this)">
                        <label class="form-check-label" for="is_in_slider">
                            Показывать в слайдере
                        </label>
                    </div>

                    <div id="coverBlock" class="cover-upload-block" style="display: none;">
                        <label for="cover" class="form-label">Обложка для слайдера</label>

                        <input type="file" class="form-control" name="cover" id="cover" accept="image/*">
                        <small class="text-muted">Изображение для слайдера (рекомендуется 1920×1080 или аналогичное соотношение сторон)</small>
                    </div>
                </div>
            </div>
        </div>



        <!-- Кнопки действий -->
        <div class="content-card">
            <div class="action-buttons">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Сохранить игру
                </button>
                <a href="{{ route('admin.game.index') }}" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Отменить
                </a>
                <button type="submit" name="save_and_continue" value="1" class="btn btn-primary">
                    <i class="fas fa-check"></i> Сохранить и продолжить
                </button>
            </div>
        </div>
    </form>

    <!-- Стили -->
    <style>
        .avatar-upload-preview img {
            background: #f8f9fa;
            border: 1px dashed #dee2e6;
        }

        .image-upload-container {
            border: 2px dashed #dee2e6;
            border-radius: 12px;
            padding: 30px;
            text-align: center;
            background: #f8f9fa;
            cursor: pointer;
            transition: all 0.3s;
        }

        .image-upload-container:hover {
            border-color: #007bff;
            background: #e9ecef;
        }

        .image-preview {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            margin-top: 15px;
        }

        .image-preview-item {
            position: relative;
            width: 120px;
            height: 120px;
            border-radius: 8px;
            overflow: hidden;
        }

        .image-preview-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .remove-image {
            position: absolute;
            top: 5px;
            right: 5px;
            background: rgba(255, 0, 0, 0.7);
            color: white;
            width: 24px;
            height: 24px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            cursor: pointer;
        }

        /* Стили для блока обложки (как в редактировании) */
        .cover-upload-block {
            margin-top: 15px;
            padding: 12px;
            border: 1px dashed #ccc;
            border-radius: 6px;
            background: #fafafa;
            animation: fadeIn 0.3s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-5px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Summernote -->
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>

    <!-- Скрипты -->
    <script>
        // Предпросмотр аватара
        function previewAvatar() {
            const file = document.getElementById('avatarInput').files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('avatarPreview').src = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        }

        // Показывать/скрывать блок обложки
        function toggleCoverBlock(checkbox) {
            const block = document.getElementById('coverBlock');
            if (!block) return;

            if (checkbox.checked) {
                block.style.display = 'block';
            } else {
                block.style.display = 'none';
            }
        }

        // Обработка загрузки фото (превью)
        document.getElementById('product-images').addEventListener('change', function(e) {
            const preview = document.getElementById('imagePreview');
            preview.innerHTML = ''; // Очистить предыдущие превью

            const files = e.target.files;
            for (let i = 0; i < files.length; i++) {
                const file = files[i]; // ИСПРАВЛЕНО: было files[I]
                if (!file.type.match('image')) continue;

                const reader = new FileReader();
                reader.onload = function(e) {
                    const div = document.createElement('div');
                    div.className = 'image-preview-item';
                    div.innerHTML = `
                        <img src="${e.target.result}" alt="Фото">
                        <div class="remove-image" onclick="this.parentElement.remove()">
                            <i class="fas fa-times"></i>
                        </div>
                    `;
                    preview.appendChild(div);
                };
                reader.readAsDataURL(file);
            }
        });

        // Инициализация Summernote
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof jQuery === 'undefined') {
                console.error('❌ jQuery не загружен!');
                return;
            }

            if (typeof $.fn.summernote === 'undefined') {
                console.error('❌ Summernote не загружен!');
                return;
            }

            $('.summernote').summernote({
                lang: 'ru-RU',
                height: 300,
                placeholder: 'Введите описание игры...',
                toolbar: [
                    ['style', ['style']],
                    ['font', ['bold', 'underline', 'clear']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['table', ['table']],
                    ['insert', ['link', 'picture', 'video']],
                    ['view', ['fullscreen', 'codeview', 'help']]
                ]
            });
        });
    </script>
@endsection
