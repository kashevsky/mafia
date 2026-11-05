@extends('admin.layouts.main')
@section('content')

    <div class="page-header">
        <h1><i class="fas fa-edit"></i> Редактирование клуба</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/admin/dashboard">Главная</a></li>
                <li class="breadcrumb-item"><a href="/admin/products">Клубы</a></li>
                <li class="breadcrumb-item active">Редактирование</li>
            </ol>
        </nav>
    </div>

    <form action="{{ route('admin.club.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="id" value="{{ $club->id }}">

        <!-- Основная информация -->
        <div class="content-card">
            <h2 class="section-title"><i class="fas fa-info-circle"></i> Основная информация</h2>
            <div class="row">
                <div class="col-md-8 mb-3">
                    <label for="title" class="form-label">Название клуба *</label>
                    <input type="text" class="form-control" id="title" name="title" value="{{ $club->title }}" required>
                </div>

                <div class="col-md-8 mb-3">
                    <label for="totem" class="form-label">Тотем *</label>
                    <input type="text" class="form-control" id="totem" name="totem" value="{{ $club->totem }}" required>
                </div>

                <!-- Описание с Summernote -->
                <div class="col-md-12 mb-3">
                    <label for="description" class="form-label">Описание клуба</label>
                    <textarea class="form-control summernote" name="description" rows="6">
                        {{ old('description', $club->description ?? '') }}
                    </textarea>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="city_id" class="form-label">Город</label>
                    <select class="form-select" id="city_id" name="city_id">
                        <option value="">Выберите город</option>
                        @foreach ($cities ?? [] as $city)
                            <option value="{{ $city->id }}" {{ old('city_id', $club->city_id ?? '') == $city->id ? 'selected' : '' }}>
                                {{ $city->title }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-8 mb-3">
                    <label for="address" class="form-label">Адрес *</label>
                    <input type="text" class="form-control" id="address" name="address" value="{{ $club->address }}" required>
                </div>
            </div>
        </div>

        <!-- Аватар -->
        <div class="content-card">
            <h2 class="section-title"><i class="fas fa-image"></i> Аватар</h2>
            <div class="image-upload-container single-upload" onclick="document.getElementById('main-image').click()">
                <i class="fas fa-cloud-upload-alt"></i>
                <h4>Загрузить фото</h4>
                <p class="text-muted">Нажмите или перетащите файл сюда</p>
                <input type="file" id="main-image" name="avatar" accept="image/*" style="display: none;" onchange="previewMainImage2(event)">
            </div>
            <div class="image-preview single-preview" id="mainImagePreview">
                @if (isset($club) && $club->avatar)
                    <div class="image-preview-item">
                        <img src="{{ $club->avatar }}" alt="Аватар клуба">
                        <button type="button" class="remove-image" onclick="removeMainImage()">
                            <i class="fas fa-times"></i>
                        </button>
                        <input type="hidden" name="keep_image2" value="1">
                    </div>
                @endif
            </div>
        </div>

        <!-- Кнопки формы -->
        <div class="content-card">
            <div class="action-buttons">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Сохранить изменения
                </button>
                <a href="/admin/products" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Отменить
                </a>
            </div>
        </div>
    </form>

    <!-- ✅ БЛОК: ИГРЫ КЛУБА -->
    <div class="content-card">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="section-title"><i class="fas fa-futbol"></i> Игры клуба</h2>
            <a href="{{ route('admin.game.create', $club->id) }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Добавить игру
            </a>
        </div>

        @if (isset($club->games) && $club->games->isNotEmpty())
            <div class="clubs-grid" id="gamesGrid">
                @foreach ($club->games as $game)
                    <div class="club-card" data-club-id="{{ $game->club_id }}" data-title="{{ $game->title }}">
                        <div class="club-image">
                            <img src="{{ $game->avatar }}" alt="{{ $game->title }}">
                        </div>
                        <div class="club-info">
                            <h3 class="club-title">{{ $game->title }}</h3>
                            <p class="club-city">
                                <i class="fas fa-calendar-alt"></i> {{ \Carbon\Carbon::parse($game->date)->format('d.m.Y') }}
                                <br>
                                <i class="fas fa-clock"></i> {{ $game->time }}
                            </p>
                        </div>
                        <div class="club-actions">
                            <a href="{{ route('admin.game.edit', $game->id) }}" class="btn-action btn-edit">
                                <i class="fas fa-edit"></i> Редактировать
                            </a>
                            <a href="{{ route('admin.game.delete', $game->id) }}" class="btn-action btn-delete"
                               onclick="return confirm('Вы уверены, что хотите удалить эту игру?')">
                                <i class="fas fa-trash"></i> Удалить
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="no-data">
                <p>У этого клуба пока нет игр. Добавьте первую игру.</p>
            </div>
        @endif
    </div>

    <!-- Стили для сетки игр -->
    <style>
        .clubs-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        .club-card {
            border: 1px solid #dee2e6;
            border-radius: 8px;
            overflow: hidden;
            background: #fff;
            box-shadow: 0 2px 4px rgba(0, 0, 0, .1);
            transition: transform .2s;
        }

        .club-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, .15);
        }

        .club-image {
            height: 180px;
            overflow: hidden;
        }

        .club-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .club-info {
            padding: 15px;
            text-align: center;
        }

        .club-title {
            font-size: 1.1rem;
            font-weight: 600;
            margin: 0 0 8px 0;
            color: #333;
        }

        .club-city {
            color: #6c757d;
            margin: 0;
            font-size: 0.9rem;
        }

        .club-actions {
            padding: 15px;
            display: flex;
            gap: 10px;
            justify-content: center;
            border-top: 1px solid #dee2e6;
        }

        .btn-action {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 8px 12px;
            text-decoration: none;
            border-radius: 4px;
            font-size: 0.875rem;
        }

        .btn-edit {
            background: #28a745;
            color: white;
        }

        .btn-delete {
            background: #dc3545;
            color: white;
        }

        .no-data {
            text-align: center;
            padding: 40px;
            color: #6c757d;
        }
    </style>

                <!-- jQuery (обязательно первым) -->
                <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

                <!-- Bootstrap JS -->
                <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

                <!-- Summernote (после jQuery) -->
                <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>

                <!-- Скрипт инициализации с диагностикой -->
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        console.log('=== ДИАГНОСТИКА ЗАГРУЖЕННЫХ БИБЛИОТЕК ===');

                        // Проверка jQuery
                        if (typeof jQuery === 'undefined') {
                            console.error('❌ ОШИБКА: jQuery не загружен!');
                            return;
                        }
                        console.log('✅ jQuery загружен успешно');

                        // Проверка Summernote
                        if (typeof $.fn.summernote === 'undefined') {
                            console.error('❌ ОШИБКА: Summernote не загружен!');

                            // Дополнительная диагностика
                            console.log('Доступные плагины jQuery:', Object.keys($.fn));
                            console.log('Все свойства jQuery:', $.fn);

                            // Попытка загрузить Summernote повторно
                            loadSummernote();
                            return;
                        }
                        console.log('✅ Summernote загружен успешно');

                        // Инициализация редакторов
                        initSummernoteEditors();
                    });

                    function initSummernoteEditors() {
                        const summernoteElements = document.querySelectorAll('.summernote');

                        if (summernoteElements.length === 0) {
                            console.log('⚠️ Элементы с классом .summernote не найдены');
                            return;
                        }

                        console.log(`✅ Найдено ${summernoteElements.length} элементов для инициализации`);


                        summernoteElements.forEach(function(element) {
                            $(element).summernote({
                                lang: 'ru-RU',
                                height: 300,
                                placeholder: 'Введите текст здесь...',
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
                    }

                    function loadSummernote() {
                        const script = document.createElement('script');
                        script.src = 'https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js';
                        script.onload = function() {
                            console.log('✅ Summernote загружен повторно');
                            initSummernoteEditors();
                        };
                        script.onerror = function() {
                            console.error('❌ Не удалось загрузить Summernote даже повторно!');
                        };
                        document.head.appendChild(script);
                    }
                </script>

    <!-- 🔍 Опционально: поиск по играм (если захотите масштабировать) -->
    <script>
        function previewMainImage2(event) {
            const file = event.target.files[0];
            if (!file) return;

            const preview = document.getElementById('mainImagePreview');
            preview.innerHTML = '';

            const reader = new FileReader();
            reader.onload = function (e) {
                const item = document.createElement('div');
                item.className = 'image-preview-item';
                item.innerHTML = `
                    <img src="${e.target.result}" alt="Аватар клуба">
                    <button type="button" class="remove-image" onclick="removeMainImage()">
                        <i class="fas fa-times"></i>
                    </button>
                `;
                preview.appendChild(item);
            };
            reader.readAsDataURL(file);
        }

        function removeMainImage() {
            const preview = document.getElementById('mainImagePreview');
            preview.innerHTML = '';

            const input = document.getElementById('main-image');
            input.value = '';

            // Сбрасываем флаг сохранения существующего изображения
            const keepInput = document.querySelector('input[name="keep_image2"]');
            if (keepInput) keepInput.remove();

            // Сигнализируем серверу об удалении аватара
            if (!document.querySelector('input[name="delete_avatar"]')) {
                const deleteFlag = document.createElement('input');
                deleteFlag.type = 'hidden';
                deleteFlag.name = 'delete_avatar';
                deleteFlag.value = '1';
                document.querySelector('form').appendChild(deleteFlag);
            }
        }
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const searchInput = document.getElementById('searchInput');
            const gamesGrid = document.getElementById('gamesGrid');
            const allGames = gamesGrid ? Array.from(gamesGrid.querySelectorAll('.club-card')) : [];

            if (!searchInput || !gamesGrid) return;

            searchInput.addEventListener('input', function () {
                const term = this.value.toLowerCase().trim();
                allGames.forEach(card => {
                    const title = card.getAttribute('data-title').toLowerCase();
                    card.style.display = title.includes(term) ? 'block' : 'none';
                });
            });
        });
    </script>

@endsection
