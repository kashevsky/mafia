@extends('admin.layouts.main')
@section('content')
    <div class="page-header">
        <h1><i class="fas fa-user-plus"></i> Создание игрока</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/admin/dashboard">Главная</a></li>
                <li class="breadcrumb-item"><a href="/admin/players">Игроки</a></li>
                <li class="breadcrumb-item active">Создание</li>
            </ol>
        </nav>
    </div>

    <form action="{{ route('admin.account.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <!-- Основная информация -->
        <div class="content-card">
            <h2 class="section-title"><i class="fas fa-info-circle"></i> Основная информация</h2>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="name" class="form-label">Имя *</label>
                    <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}"
                        required>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="nickname" class="form-label">Никнейм *</label>
                    <input type="text" class="form-control" id="nickname" name="nickname" value="{{ old('nickname') }}"
                        required>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}">
                </div>

                <div class="col-md-6 mb-3">
                    <label for="phone" class="form-label">Телефон</label>
                    <input type="tel" class="form-control" id="phone" name="phone" value="{{ old('phone') }}">
                </div>

                <div class="col-md-6 mb-3">
                    <label for="birth" class="form-label">Дата рождения</label>
                    <input type="date" class="form-control" id="birth" name="birth" value="{{ old('birth') }}">
                </div>

                <div class="col-md-6 mb-3">
                    <label for="telegram_id" class="form-label">Telegram ID</label>
                    <input type="text" class="form-control" id="telegram_id" name="telegram_id"
                        value="{{ old('telegram_id') }}">
                </div>
            </div>
        </div>



        <!-- Статус и права -->
        <div class="content-card">
            <h2 class="section-title"><i class="fas fa-shield-alt"></i>Баланс</h2>
            <div class="row">










                <div class="col-md-6 mb-3">
                    <label for="balance" class="form-label">Баланс</label>
                    <input type="number" class="form-control" id="balance" name="balance"
                        value="{{ old('balance', 0) }}">
                </div>
            </div>
        </div>


        <!-- Связи -->
        <div class="content-card">
            <h2 class="section-title"><i class="fas fa-link"></i> Связи</h2>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="club_id" class="form-label">Клуб</label>
                    <select class="form-select" id="club_id" name="club_id">
                        <option value="">Выберите клуб</option>
                        @foreach ($clubs ?? [] as $club)
                            <option value="{{ $club->id }}" {{ old('club_id') == $club->id ? 'selected' : '' }}>
                                {{ $club->title ?? 'Без названия' }}
                            </option>
                        @endforeach
                    </select>
                </div>

            </div>
        </div>

        <!-- Аватар -->
        <div class="content-card">
            <h2 class="section-title"><i class="fas fa-image"></i> Аватар</h2>
            <div class="image-upload-container single-upload" onclick="document.getElementById('avatar-image').click()">
                <i class="fas fa-cloud-upload-alt"></i>
                <h4>Загрузить фото</h4>
                <p class="text-muted">Нажмите или перетащите файл сюда</p>
                <input type="file" id="avatar-image" name="avatar" accept="image/*" style="display: none;"
                    onchange="previewAvatarImage(event)">
            </div>
            <div class="image-preview single-preview" id="avatarImagePreview">
                <!-- При создании игрока превью изначально пустое -->
            </div>
        </div>

        <!-- Биография -->
        <div class="content-card">
            <h2 class="section-title"><i class="fas fa-pen"></i> Биография</h2>
            <div class="row">
                <div class="col-md-12 mb-3">
                    <textarea class="form-control summernote" name="bio" rows="6">
                {{ old('bio') }}
            </textarea>
                </div>
            </div>
        </div>

        <!-- Кнопки формы -->
        <div class="content-card">
            <div class="action-buttons">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Создать игрока
                </button>
                <a href="/admin/players" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Отменить
                </a>
            </div>
        </div>
    </form>

    <!-- Стили для страницы создания игрока -->
    <style>
        .content-card {
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, .1);
            padding: 20px;
            margin-bottom: 20px;
        }

        .section-title {
            color: #333;
            font-size: 1.25rem;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .action-buttons {
            display: flex;
            gap: 10px;
            justify-content: flex-start;
        }

        /* Стили для загрузки изображений */
        .image-upload-container {
            border: 2px dashed #ddd;
            border-radius: 8px;
            padding: 30px;
            text-align: center;
            cursor: pointer;
            transition: border-color .3s;
        }

        .image-upload-container:hover {
            border-color: #007bff;
        }

        .image-preview {
            margin-top: 15px;
        }

        .image-preview-item {
            position: relative;
            display: inline-block;
        }

        .image-preview img {
            max-width: 200px;
            max-height: 200px;
            border-radius: 4px;
        }

        .remove-image {
            position: absolute;
            top: 5px;
            right: 5px;
            background: rgba(255, 255, 255, 0.9);
            border: none;
            width: 25px;
            height: 25px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }
    </style>

    <!-- Скрипты для работы с аватаром -->
    <script>
        function previewAvatarImage(event) {
            const file = event.target.files[0];
            if (!file) return;

            const preview = document.getElementById('avatarImagePreview');
            preview.innerHTML = '';

            const reader = new FileReader();
            reader.onload = function(e) {
                const item = document.createElement('div');
                item.className = 'image-preview-item';
                item.innerHTML = `
            <img src="${e.target.result}" alt="Аватар игрока">
            <button type="button" class="remove-image" onclick="removeAvatarImage()">
                <i class="fas fa-times"></i>
            </button>
        `;
                preview.appendChild(item);
            };
            reader.readAsDataURL(file);
        }

        function removeAvatarImage() {
            const preview = document.getElementById('avatarImagePreview');
            preview.innerHTML = '';

            const input = document.getElementById('avatar-image');
            input.value = '';
        }
    </script>

    <!-- Инициализация Summernote для поля биографии -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Проверка загрузки Summernote
            if (typeof $.fn.summernote !== 'undefined') {
                $('.summernote').summernote({
                    lang: 'ru-RU',
                    height: 200,
                    placeholder: 'Расскажите о игроке...',
                    toolbar: [
                        ['style', ['style']],
                        ['font', ['bold', 'italic', 'underline', 'clear']],
                        ['color', ['color']],
                        ['para', ['ul', 'ol', 'paragraph']],
                        ['insert', ['link', 'picture']],
                        ['view', ['fullscreen', 'codeview']]
                    ]
                });
            }
        });
    </script>
@endsection
