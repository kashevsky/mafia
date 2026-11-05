@extends('admin.layouts.main')
@section('content')
    <style>
        .table-card {
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 15px;
            background: #fff;
            transition: all 0.3s ease;
            min-height: 140px;
        }

        .table-card:hover {
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            transform: translateY(-2px);
        }

        .table-active {
            border-left: 4px solid #28a745;
        }

        .table-inactive {
            border: 2px solid #dc3545;
        }

        .table-info {
            margin-bottom: 10px;
        }

        .status-badge {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 0.85rem;
            font-weight: 500;
        }

        .status-active {
            background: #d4edda;
            color: #155724;
        }

        .status-inactive {
            background: #f8d7da;
            color: #721c24;
        }

        .table-actions {
            display: flex;
            gap: 8px;
            justify-content: flex-end;
        }

        /* Стили для блока обложки */
        .cover-upload-block {
            margin-top: 15px;
            padding: 12px;
            border: 1px dashed #ccc;
            border-radius: 6px;
            background: #fafafa;
            display: none;
            /* скрыто по умолчанию */
            animation: fadeIn 0.3s ease;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-5px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .avatar-upload-preview img {
            border-radius: 12px;
            background: #f8f9fa;
        }

        .scrollable-image-list {
            display: flex;
            gap: 10px;
            padding: 10px 0;
            overflow-x: auto;
            white-space: nowrap;
            border: 1px solid #e9ecef;
            border-radius: 8px;
            background: #f8f9fa;
            min-height: 100px;
            padding-bottom: 10px;
        }

        .image-option {
            display: inline-block;
            text-align: center;
            margin-right: 10px;
        }

        .image-option img {
            cursor: pointer;
        }

        .image-option input[type="radio"] {
            margin-bottom: 5px;
        }
    </style>

    <div class="page-header">
        <h1><i class="fas fa-edit"></i> Редактирование игры: {{ $game->title }}</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/admin/dashboard">Главная</a></li>
                <li class="breadcrumb-item"><a href="/admin/products">Игры</a></li>
                <li class="breadcrumb-item"><a href="/admin/products">{{ $game->title }}</a></li>
                <li class="breadcrumb-item active">Редактирование</li>
            </ol>
        </nav>
    </div>

    <div class="page-actions mb-4">

        @if (!$game->is_closed)
            @if (!$game->finished_at)
                @if (!$game->is_published)
                    <a href="{{ route('table.create', ['game_id' => $game->id, 'is_dop' => 0]) }}"
                        class="btn btn-success me-2" style="background-color: green">
                        <i class="fas fa-table"></i> Создать стол для основной игры
                    </a>
                @else
                    <a href="{{ route('table.create', ['game_id' => $game->id, 'is_dop' => 0]) }}"
                        class="btn btn-success me-2 disabled"
                        style="background-color: rgb(50, 83, 37); pointer-events: none; border: none" aria-disabled="true">
                        <i class="fas fa-table"></i> Создать стол для основной игры
                    </a>
                @endif
                <a href="{{ route('table.create', ['game_id' => $game->id, 'is_dop' => 1]) }}" class="btn btn-success me-2"
                    style="background-color: rgb(233, 155, 0); border-color: rgb(233, 155, 0);">
                    <i class="fas fa-table"></i> Создать стол для доп игры
                </a>
            @else
                <!-- Кнопка 1 -->
                <a href="{{ route('table.create', ['game_id' => $game->id, 'is_dop' => 0]) }}"
                    class="btn btn-success me-2 disabled"
                    style="background-color: rgb(50, 83, 37); pointer-events: none; border: none" aria-disabled="true">
                    <i class="fas fa-table"></i> Создать стол для основной игры
                </a>

                <!-- Кнопка 2 -->
                <a href="{{ route('table.create', ['game_id' => $game->id, 'is_dop' => 1]) }}"
                    class="btn btn-success me-2 disabled"
                    style="background-color: rgb(233, 155, 0); border-color: rgb(71, 63, 45); pointer-events: none; border: none"
                    aria-disabled="true">
                    <i class="fas fa-table"></i> Создать стол для доп игры
                </a>
            @endif
        @else
            <h2 style="color: rgb(194, 0, 0)">Игра закрыта</h2>
        @endif


        @if (!$game->is_published)
            <button type="button" class="btn btn-success me-2" data-bs-toggle="modal" data-bs-target="#confirmPublicModal"
                style="background-color: rgb(233, 155, 0); border-color: rgb(233, 155, 0);">
                <i class="fas fa-table"></i> Опубликовать результаты
            </button>
        @else
            <h2 style="color: rgb(194, 0, 0); margin-top: 30px">Результаты опубликованы</h2>
        @endif
        <!-- Модальное окно подтверждения публикации -->
        <div class="modal fade" id="confirmPublicModal" tabindex="-1" aria-labelledby="confirmPublicModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="confirmPublicModalLabel">Подтверждение действия</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Закрыть"></button>
                    </div>
                    <div class="modal-body text-center py-4">
                        <h4 class="mb-0">Вы уверены?</h4>
                        <p class="text-muted mt-2 mb-0">Вы действительно хотите опубликовать результаты игры?</p>
                    </div>
                    <div class="modal-footer justify-content-center">
                        <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Нет</button>
                        <a href="{{ route('admin.game.makePublic', $game->id) }}" class="btn btn-success px-4"
                            style="background-color: rgb(233, 155, 0); border-color: rgb(233, 155, 0);">Да</a>
                    </div>
                </div>
            </div>
        </div>

        <button type="button" class="btn btn-success me-2" data-href="/modal2/{{$game->id}}" data-vbtype="ajax" data-vbox
                style="background-color: rgb(233, 155, 0); border-color: rgb(233, 155, 0);">
                <i class="fas fa-table"></i>
                 Оплаты
        </button>


    </div>

    <div class="col-md-6 mb-3">
        <label for="status" class="form-label">Статус игры *</label>

        <!-- Проверяем оба условия: и флаг, и текстовый статус из базы -->
        <select class="form-select" id="status" name="status" required
            @if ($game->is_closed || $game->status === 'closed' || $game->status === 'сlosed') disabled @endif>

            <!-- Изменено: Опция по умолчанию убрана из selected, если в базе ничего нет -->
            <option value="" disabled>
                Статус
            </option>

            <!-- Изменено: Если статус пустой, ставим selected принудительно -->
            <option value="active"
                {{ !old('status', $game->status ?? '') || old('status', $game->status ?? '') == 'active' ? 'selected' : '' }}>
                Видна только админам
            </option>

            <option value="pending" {{ old('status', $game->status ?? '') == 'pending' ? 'selected' : '' }}>
                Видна всем
            </option>

            <option value="cancelled" {{ old('status', $game->status ?? '') == 'cancelled' ? 'selected' : '' }}>
                Отменена
            </option>

            <option value="closed"
                {{ old('status', $game->status ?? '') == 'closed' || old('status', $game->status ?? '') == 'сlosed' ? 'selected' : '' }}>
                Закрыта
            </option>
        </select>


        @if ($game->is_closed || $game->status === 'closed' || $game->status === 'сlosed')
            <input type="hidden" name="status" value="closed">
        @endif
    </div>



    @if (!$game->is_closed)
        <!-- БЛОК: Список столов -->
        <div class="tables-list mb-4">
            <h3 class="section-title"><i class="fas fa-table"></i> Созданные столы</h3>

            @if ($game->tables && $game->tables->count() > 0)
                <div class="row">
                    @foreach ($game->tables as $table)
                        <div class="col-md-4 mb-3">
                            <div class="table-card {{ $table->is_active ? 'table-active' : 'table-inactive' }}">
                                <h4>{{ $table->title }}</h4>
                                <div class="table-info">
                                    <p><strong>Проведено игр:</strong> {{ $table->step ?? 0 }}</p>
                                    </p>
                                    <p><strong>Занято мест:</strong> {{ $table->occupied_space }} /
                                        {{ $table->total_seats }}
                                    </p>
                                    {{-- <p><strong>Статус:</strong>
                                    <span
                                        class="status-badge {{ $table->is_running ? 'status-active' : 'status-inactive' }}">
                                        {{ $table->is_running ? 'Запущен' : 'Не запущен' }}
                                    </span>
                                </p> --}}
                                </div>
                                @if (!$game->is_closed)
                                    <div class="table-actions">
                                        <button type="button" class="btn btn-sm btn-outline-primary btn-delete-table"
                                            style="background: rgb(203, 13, 13); color: rgb(222, 222, 222); border: none; padding: 5px 10px"
                                            data-delete-url="{{ route('table.delete', $table->id) }}"
                                            data-table-name="{{ $table->title }}" data-bs-toggle="modal"
                                            data-bs-target="#confirmDeleteTableModal">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                                viewBox="0 0 24 24" fill="currentColor">
                                                <path d="M3 6h18M8 6V4h8v2M19 6l-1 14H6L5 6" />
                                            </svg>
                                        </button>
                                        <a href="{{ route('table.edit', $table->id) }}"
                                            class="btn btn-sm btn-outline-primary"
                                            style="background: rgb(255, 213, 0); color: black; border: none; padding: 5px 10px">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" />
                                                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z" />
                                            </svg>
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-muted">Столов пока не создано. Нажмите «Создать стол», чтобы добавить первый.</p>
            @endif
        </div>
    @endif

    @if ($game->is_closed != 1)
        @if ($admin->role != 'hoster')

            <form action="{{ route('admin.game.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="game_id" id="game_id_val" value="{{ $game->id }}">

                <!-- Основная информация -->
                <div class="content-card">
                    <h2 class="section-title"><i class="fas fa-info-circle"></i> Основная информация</h2>

                    <div class="row">
                        <div class="col-md-8 mb-3">
                            <label for="title" class="form-label">Название игры *</label>
                            <input type="text" class="form-control" id="title" name="title"
                                value="{{ old('title', $game->title) }}" required>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="price" class="form-label">Стоимость</label>
                            <input type="text" class="form-control" id="price" name="price"
                                value="{{ old('price', $game->price) }}">
                        </div>

                        @if ($admin->role == 'superadmin' || in_array('clubs.delete', $permissions))
                            <div class="col-md-6 mb-3">
                                <label for="club_id" class="form-label">Клуб *</label>
                                <select class="form-control" id="club_id" name="club_id" required>
                                    <option value="" disabled {{ !$game->club_id ? 'selected' : '' }}>Выберите
                                        клуб
                                    </option>
                                    @foreach ($clubs as $club)
                                        <option value="{{ $club->id }}"
                                            {{ $game->club_id == $club->id ? 'selected' : '' }}>
                                            {{ $club->title }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        @endif

                        <!-- Аватар игры -->
                        <div class="content-card">
                            <h2 class="section-title"><i class="fas fa-user-circle"></i> Аватар игры</h2>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="avatar" class="form-label">Главное изображение (аватар)</label>
                                    <div class="avatar-upload-preview">
                                        @if ($game->avatar)
                                            <img src="{{ asset($game->avatar) }}" alt="Аватар игры" id="avatarPreview"
                                                class="img-thumbnail" style="height: 150px; object-fit: cover;">
                                        @else
                                            <img src="{{ asset('images/default-game-avatar.png') }}" alt="Нет аватара"
                                                id="avatarPreview" class="img-thumbnail"
                                                style="height: 150px; object-fit: cover;">
                                        @endif
                                    </div>
                                    <input type="file" class="form-control mt-2" name="avatar" id="avatarInput"
                                        accept="image/*" onchange="previewAvatar()">
                                    <small class="text-muted">Рекомендуется квадратное изображение (например,
                                        300×300)</small>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Или выберите из загруженных фото</label>
                                    <div class="scrollable-image-list">
                                        @if ($game->photos && $game->photos->count() > 0)
                                            @foreach ($game->photos as $slide)
                                                <div class="image-option">
                                                    <input type="radio" name="avatar_from_photos"
                                                        value="{{ $slide->src }}"
                                                        id="avatar_photo_{{ $slide->id }}"
                                                        {{ $game->avatar === $slide->src ? 'checked' : '' }}>
                                                    <label for="avatar_photo_{{ $slide->id }}">
                                                        <img src="{{ asset($slide->src) }}" alt="Фото"
                                                            class="img-thumbnail"
                                                            style="height: 80px; object-fit: cover;">
                                                    </label>
                                                </div>
                                            @endforeach
                                        @else
                                            <p class="text-muted">Нет загруженных фото</p>
                                        @endif
                                    </div>
                                    <small class="text-muted">Выберите, чтобы использовать фото как аватар</small>
                                </div>
                            </div>
                        </div>

                        <!-- НОВЫЕ ПОЛЯ: is_in_slider и cover -->
                        <div class="col-md-12 mb-3">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="is_in_slider" name="is_in_slider"
                                    value="1"
                                    {{ old('is_in_slider', $game->is_in_slider ? '1' : '') ? 'checked' : '' }}
                                    onchange="toggleCoverBlock(this)">
                                <label class="form-check-label" for="is_in_slider">
                                    Показывать в слайдере
                                </label>
                            </div>

                            <div id="coverBlock" class="cover-upload-block">
                                <label for="cover" class="form-label">Обложка для слайдера</label>
                                @if ($game->cover)
                                    <img src="{{ asset($game->cover) }}" alt="Обложка" class="img-thumbnail mb-2"
                                        style="max-width: 200px;">
                                @endif

                                <!-- ВАЖНО: input остаётся в DOM, мы не делаем fileInput.value = '' при скрытии -->
                                <input type="file" class="form-control" name="cover" id="cover"
                                    accept="image/*">

                                <small class="text-muted">Изображение для слайдера (рекомендуется 1920×1080 или
                                    аналогичное
                                    соотношение сторон)</small>

                                @if ($game->cover)
                                    <div class="mt-2">
                                        <label class="form-check-label">
                                            <input type="checkbox" name="remove_cover" value="1"
                                                class="form-check-input">
                                            Удалить текущую обложку
                                        </label>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <!-- Конец новых полей -->


                        <!-- Дата, время, места, адрес, описание -->
                        <div class="col-md-6 mb-3">
                            <label for="event_date" class="form-label">Дата мероприятия *</label>
                            <input type="date" class="form-control" id="event_date" name="event_date"
                                value="{{ old('event_date', $game->date ? $game->date : '') }}" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="total_space" class="form-label">Количество мест</label>
                            <input type="number" class="form-control" id="total_space" name="total_space"
                                value="{{ old('seats', $game->total_space ?? '') }}">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="event_time" class="form-label">Время проведения *</label>
                            <input type="time" class="form-control" id="event_time" name="event_time"
                                value="{{ old('event_time', $game->time ?? '') }}" required>
                        </div>

                        <div class="col-md-8 mb-3">
                            <label for="address" class="form-label">Место проведения</label>
                            <input type="text" class="form-control" id="address" name="address"
                                value="{{ $game->address ?? '' }}">
                        </div>

                        <div class="col-md-12 mb-3">
                            <label for="description" class="form-label">Описание</label>
                            <textarea class="form-control summernote" id="description" name="description">{{ old('description', $game->description) }}</textarea>
                        </div>
                    </div>
                </div>



                <!-- Кнопки действий -->
                <div class="content-card">
                    <div class="action-buttons">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Сохранить изменения
                        </button>
                        <a href="/admin/products" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Отменить
                        </a>
                        <button type="submit" name="save_and_continue" value="1" class="btn btn-primary">
                            <i class="fas fa-check"></i> Сохранить и продолжить
                        </button>
                    </div>
                </div>
            </form>
        @endif
    @endif


    <!-- Модальное окно подтверждения смены статуса -->
    <div class="modal fade" id="confirmStatusModal" tabindex="-1" aria-labelledby="confirmStatusModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmStatusModalLabel">Подтверждение смены статуса</h5>
                    <button type="button" class="btn-close" id="statusModalCloseX" data-bs-dismiss="modal"
                        aria-label="Закрыть"></button>
                </div>
                <div class="modal-body text-center py-4">
                    <h4 class="mb-0">Вы уверены?</h4>
                    <p class="text-muted mt-2 mb-0">Вы действительно хотите изменить статус игры на: <strong
                            id="modalStatusName" class="text-dark"></strong>?</p>
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-secondary px-4" id="statusModalCancel"
                        data-bs-dismiss="modal">Нет</button>
                    <button type="button" class="btn btn-primary px-4" id="statusModalConfirm"
                        style="background-color: rgb(233, 155, 0); border-color: rgb(233, 155, 0);">Да, изменить</button>
                </div>
            </div>
        </div>
    </div>

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

        // Функция для показа/скрытия блока обложки
        function toggleCoverBlock(checkbox) {
            const block = document.getElementById('coverBlock');
            if (!block) return;

            if (checkbox.checked) {
                block.style.display = 'block';
            } else {
                block.style.display = 'none';
                // ❌ УБРАТЬ ЭТУ СТРОКУ:
                // const fileInput = document.getElementById('cover');
                // if (fileInput) fileInput.value = '';
            }
        }


        // Инициализируем видимость блока при загрузке страницы
        document.addEventListener('DOMContentLoaded', () => {
            const checkbox = document.getElementById('is_in_slider');
            if (checkbox) {
                toggleCoverBlock(checkbox);
            }
        });

        // Обновление счётчика выбранных игроков
        function updateSelectionCount() {
            const checkedCount = document.querySelectorAll('input[name="selected_players[]"]:checked').length;
            const el = document.getElementById('selectionCount');
            if (el) el.textContent = `Выбрано: ${checkedCount} игроков`;
        }

        // Обработчик изменения чекбоксов
        const checkboxes = document.querySelectorAll('input[name="selected_players[]"]');
        checkboxes.forEach(checkbox => {
            checkbox.addEventListener('change', updateSelectionCount);
        });

        // Обработчик кнопки «Записать выбранных на игру»
        const btn = document.getElementById('confirmPlayersBtn');
        if (btn) {
            btn.addEventListener('click', function() {
                const selectedPlayers = document.querySelectorAll('input[name="selected_players[]"]:checked');

                if (selectedPlayers.length === 0) {
                    alert('Пожалуйста, выберите хотя бы одного игрока.');
                    return;
                }

                // Создаём скрытую форму для отправки выбранных игроков
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '{{ route('admin.game.confirm-players', $game->id) }}';

                // Добавляем CSRF‑токен
                const csrfInput = document.createElement('input');
                csrfInput.type = 'hidden';
                csrfInput.name = '_token';
                csrfInput.value = '{{ csrf_token() }}';
                form.appendChild(csrfInput);

                // Добавляем выбранных игроков
                selectedPlayers.forEach(checkbox => {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'player_ids[]';
                    input.value = checkbox.value;
                    form.appendChild(input);
                });

                // Отправляем форму
                document.body.appendChild(form);
                form.submit();
            });
        }
    </script>
    <!-- Модальное окно подтверждения удаления стола -->
    <div class="modal fade" id="confirmDeleteTableModal" tabindex="-1" aria-labelledby="confirmDeleteTableModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmDeleteTableModalLabel">Подтверждение удаления</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Закрыть"></button>
                </div>
                <div class="modal-body text-center py-4">
                    <h4 class="mb-2">Вы уверены?</h4>
                    <p class="text-muted mb-0">Вы действительно хотите удалить стол
                        <strong id="deleteTableName" class="text-dark"></strong>?
                    </p>
                    <p class="text-danger mt-2 mb-0" style="font-size: 0.85rem;">Это действие необратимо.</p>
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Нет, отмена</button>
                    <a id="deleteTableConfirmBtn" href="#" class="btn btn-danger px-4">Да, удалить</a>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.btn-delete-table').forEach(function(btn) {
                btn.addEventListener('click', function() {
                    document.getElementById('deleteTableName').textContent = this.dataset.tableName;
                    document.getElementById('deleteTableConfirmBtn').href = this.dataset.deleteUrl;
                });
            });
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const statusSelect = document.getElementById('status');
            const statusModalEl = document.getElementById('confirmStatusModal');

            if (statusSelect && statusModalEl) {
                // Инициализируем модальное окно через Bootstrap API
                const statusModal = new bootstrap.Modal(statusModalEl);

                let previousStatus = statusSelect.value;
                let targetStatusValue = '';

                // Перехватываем изменение селекта
                statusSelect.addEventListener('change', function() {
                    const selectedOption = this.options[this.selectedIndex];
                    const statusText = selectedOption.text;
                    targetStatusValue = this.value; // Запоминаем, какой статус хочет юзер

                    // Подставляем текст статуса в модалку
                    document.getElementById('modalStatusName').textContent = statusText;

                    // Показываем красивое модальное окно
                    statusModal.show();
                });

                // Обработка кнопки "Да, изменить" в модалке
                document.getElementById('statusModalConfirm').addEventListener('click', async function() {
                    const gameId = '{{ $game->id }}';

                    // Скрываем модалку перед отправкой
                    statusModal.hide();

                    try {
                        const response = await fetch('/change-status-game', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                game_id: gameId,
                                status: targetStatusValue
                            })
                        });

                        if (response.ok) {
                            previousStatus = targetStatusValue; // Закрепляем успех
                            alert('Статус игры успешно изменен!');
                            window.location.reload();
                        } else {
                            alert('Ошибка на сервере при изменении статуса.');
                            statusSelect.value = previousStatus; // Откат
                        }
                    } catch (error) {
                        console.error('Ошибка отправки:', error);
                        alert('Произошла сетевая ошибка при смене статуса.');
                        statusSelect.value = previousStatus; // Откат
                    }
                });

                // Если модалку закрыли (кликнули "Нет", "Х" или мимо окна) — возвращаем старый статус
                statusModalEl.addEventListener('hidden.bs.modal', function() {
                    // Проверяем, совпадает ли текущее значение селекта с тем, что подтверждено.
                    // Если не совпадает, значит пользователь просто закрыл окно отмены.
                    if (statusSelect.value !== previousStatus) {
                        statusSelect.value = previousStatus;
                    }
                });
            }
        });
    </script>
@endsection
