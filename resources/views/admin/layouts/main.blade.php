<!DOCTYPE html>
<html lang="ru">

@php

    if (isset($admin->permissions)) {
        $permissions = $admin->permissions;
    } else {
        $permissions = [];
    }

@endphp

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Админ панель</title>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Summernote CSS -->
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
    <script src="//unpkg.com/alpinejs" defer></script>
    
    {{-- Vbox Styles --}}
    <link href="{{ asset('libs/vbox/style.css') }}" rel="stylesheet">
    <link href="{{ asset('libs/vbox/custom.css') }}" rel="stylesheet">
   
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f6f9;
        }

        .admin-wrapper {
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar */
        .sidebar {
            width: 260px;
            background: linear-gradient(180deg, #2c3e50 0%, #34495e 100%);
            color: white;
            position: fixed;
            height: 100vh;
            overflow-y: auto;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
            z-index: 100;
        }

        .sidebar-header {
            padding: 25px 20px;
            background-color: rgba(0, 0, 0, 0.2);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .sidebar-header h3 {
            font-size: 22px;
            font-weight: 600;
            margin: 0;
            color: #fff;
        }

        .sidebar-header p {
            font-size: 13px;
            color: rgba(255, 255, 255, 0.7);
            margin: 5px 0 0 0;
        }

        .sidebar-menu {
            list-style: none;
            padding: 20px 0;
        }

        .sidebar-menu li {
            margin: 0;
        }

        .sidebar-menu a {
            display: flex;
            align-items: center;
            padding: 15px 25px;
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            transition: all 0.3s ease;
            border-left: 3px solid transparent;
        }

        .sidebar-menu a:hover {
            background-color: rgba(255, 255, 255, 0.1);
            color: #fff;
            border-left-color: #3498db;
        }

        .sidebar-menu a.active {
            background-color: rgba(52, 152, 219, 0.2);
            color: #fff;
            border-left-color: #3498db;
        }

        .sidebar-menu a i {
            margin-right: 12px;
            font-size: 18px;
            width: 25px;
            text-align: center;
        }

        /* Main Content */
        .main-content {
            flex: 1;
            margin-left: 260px;
            padding: 30px;
        }

        .page-header {
            background: white;
            padding: 25px 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            margin-bottom: 30px;
        }

        .page-header h1 {
            font-size: 28px;
            font-weight: 600;
            color: #2c3e50;
            margin: 0;
        }

        .breadcrumb {
            background: transparent;
            padding: 0;
            margin: 8px 0 0 0;
            font-size: 14px;
        }

        .content-card {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            padding: 30px;
            margin-bottom: 30px;
        }

        .section-title {
            font-size: 18px;
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #ecf0f1;
        }

        /* Form Styles */
        .form-label {
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 8px;
        }

        .form-control,
        .form-select {
            border: 1px solid #dfe6e9;
            border-radius: 6px;
            padding: 12px 15px;
            font-size: 14px;
            transition: all 0.3s ease;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #3498db;
            box-shadow: 0 0 0 0.2rem rgba(52, 152, 219, 0.15);
        }

        /* Image Upload */
        .image-upload-container {
            border: 2px dashed #dfe6e9;
            border-radius: 8px;
            padding: 30px;
            text-align: center;
            background-color: #f8f9fa;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .image-upload-container:hover {
            border-color: #3498db;
            background-color: #e3f2fd;
        }

        .image-upload-container i {
            font-size: 48px;
            color: #3498db;
            margin-bottom: 15px;
        }

        .image-preview {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            margin-top: 20px;
        }

        .image-preview-item {
            position: relative;
            width: 150px;
            height: 150px;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .image-preview-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .image-preview-item .remove-image {
            position: absolute;
            top: 5px;
            right: 5px;
            background-color: rgba(231, 76, 60, 0.9);
            color: white;
            border: none;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            cursor: pointer;
            font-size: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }

        .image-preview-item .remove-image:hover {
            background-color: #c0392b;
        }

        /* Product Selection */
        .product-selector {
            border: 1px solid #dfe6e9;
            border-radius: 8px;
            padding: 15px;
            max-height: 300px;
            overflow-y: auto;
        }

        .product-item {
            display: flex;
            align-items: center;
            padding: 10px;
            border-bottom: 1px solid #ecf0f1;
            cursor: pointer;
            transition: background-color 0.2s ease;
        }

        .product-item:last-child {
            border-bottom: none;
        }

        .product-item:hover {
            background-color: #f8f9fa;
        }

        .product-item input[type="checkbox"] {
            margin-right: 12px;
            width: 18px;
            height: 18px;
            cursor: pointer;
        }

        .product-item img {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 6px;
            margin-right: 12px;
        }

        /* Buttons */
        .btn-primary {
            background-color: #3498db;
            border: none;
            padding: 12px 30px;
            font-weight: 600;
            border-radius: 6px;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #2980b9;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(52, 152, 219, 0.3);
        }

        .btn-secondary {
            background-color: #95a5a6;
            border: none;
            padding: 12px 30px;
            font-weight: 600;
            border-radius: 6px;
            transition: all 0.3s ease;
        }

        .btn-secondary:hover {
            background-color: #7f8c8d;
        }

        .action-buttons {
            display: flex;
            gap: 15px;

            border-top: 2px solid #ecf0f1;
        }

        .page-header {
            background: white;
            padding: 25px 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            margin-bottom: 30px;
        }

        /* Таблица товаров */
        .products-table {
            width: 100%;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            overflow: hidden;
        }

        .table-header {
            background-color: #ecf0f1;
            font-weight: 600;
            color: #2c3e50;
        }

        .table-row {
            border-bottom: 1px solid #ecf0f1;
            transition: background-color 0.2s ease;
        }

        .table-row:hover {
            background-color: #f8f9fa;
        }

        .table-cell {
            padding: 6px 20px;
            vertical-align: middle;
            border-bottom: 1px solid #ecf0f1;
        }

        .product-name {
            font-weight: 600;
            color: #2c3e50;
        }

        .product-price {
            font-size: 16px;
            font-weight: 700;
            color: #3498db;
        }

        .product-status {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .status-active {
            background-color: #d4edda;
            color: #155724;
        }

        .status-inactive {
            background-color: #f8d7da;
            color: #721c24;
        }

        .action-buttons {
            display: flex;
            gap: 8px;
        }

        .btn-action {
            padding: 6px 12px;
            font-size: 13px;
            border-radius: 4px;
            border: none;
            cursor: pointer;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            gap: 3px;
            text-decoration: none;
        }

        .btn-edit {
            background-color: #f39c12;
            color: white;
        }

        .btn-edit:hover {
            background-color: #e67e22;
        }

        .btn-delete {

            background-color: #e74c3c;
            color: white;

        }

        .btn-delete:hover {
            background-color: #c0392b;
        }

        /* Фильтры и поиск */
        .filters {
            display: flex;
            gap: 15px;
            margin-bottom: 25px;
            flex-wrap: wrap;
        }

        .filter-group {
            flex: 1;
            min-width: 200px;
        }

        .search-box {
            position: relative;
        }

        .search-box input {
            width: 100%;
            padding: 12px 40px 12px 15px;
            border: 1px solid #dfe6e9;
            border-radius: 6px;
            font-size: 14px;
        }

        .search-box i {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #95a5a6;
        }

        /* Пагинация */
        .pagination {
            display: flex;
            justify-content: center;
            margin-top: 40px;
            gap: 8px;
        }

        .page-item {
            padding: 8px 16px;
            border-radius: 6px;
            background: white;
            border: 1px solid #dfe6e9;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .page-item.active {
            background: #3498db;
            color: white;
            border-color: #3498db;
        }

        .page-item:hover:not(.active) {
            background: #f8f9fa;
        }

        /* Адаптивность */
        @media (max-width: 768px) {
            .admin-wrapper {
                flex-direction: column;
            }

            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
            }

            .main-content {
                margin-left: 0;
            }

            .products-table {
                font-size: 14px;
            }

            .table-cell {
                padding: 12px 10px;
            }

            .filters {
                flex-direction: column;
            }
        }

        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                width: 0;
                overflow: hidden;
            }

            .main-content {
                margin-left: 0;
            }

            .sidebar.mobile-open {
                width: 260px;
            }
        }

        /* Summernote Customization */
        .note-editor.note-frame {
            border: 1px solid #dfe6e9;
            border-radius: 6px;
        }

        .note-toolbar {
            background-color: #f8f9fa;
            border-bottom: 1px solid #dfe6e9;
        }
    </style>
</head>

<body>
    @php
        $url = url()->full();
    @endphp
    <div class="admin-wrapper">
        <!-- Sidebar -->

        <aside class="sidebar">
            <div class="sidebar-header">
                <h3><i class="fas fa-store"></i> Админ панель</h3>
                <p></p>
            </div>
            <ul class="sidebar-menu">
                @isset($admin)
                    <li>
                        <a href="{{ route('admin.index') }}">
                            <i class="fas fa-home"></i>
                            <span>Главная</span>
                        </a>
                    </li>

                    @if ($admin->role == 'superadmin' || in_array('clubs.show', $permissions))
                        <li>
                            <a href="{{ route('admin.club.index') }}"
                                class="{{ str_contains($url, 'admin/categor') ? 'active' : '' }}">
                                <i class="fas fa-shield-alt"></i>

                                <span>
                                    Клубы

                                </span>
                            </a>
                        </li>
                    @endif
                    @if ($admin->role == 'superadmin' || in_array('players.show', $permissions))
                        <li>
                            <a href="{{ route('admin.account.index') }}"
                                class="{{ str_contains($url, 'admin/categor') ? 'active' : '' }}">
                                <i class="fas fa-user-friends"></i>
                                @if ($admin->role != 'superadmin')
                                    <span>Игроки клуба</span>
                                @else
                                    <span>Все игроки</span>
                                @endif
                            </a>
                        </li>
                    @endif
                    @if ($admin->role == 'superadmin' || in_array('games.show', $permissions))
                        <li>
                            <a href="{{ route('admin.game.index') }}"
                                class="{{ str_contains($url, 'admin/categor') ? 'active' : '' }}">
                                <i class="fas fa-gamepad"></i>
                                @if ($admin->role != 'superadmin')
                                    <span>Все игры</span>
                                @else
                                    <span>Игры</span>
                                @endif
                            </a>
                        </li>
                    @endif
                    @if ($admin->role == 'hoster')
                        <li>
                            <a href="{{ route('admin.game.index') }}"
                                class="{{ str_contains($url, 'admin/categor') ? 'active' : '' }}">
                                <i class="fas fa-gamepad"></i>
                                <span>Игры</span>
                            </a>
                        </li>
                    @endif
                    @if ($admin->role == 'superadmin')
                        <li>
                            <a href="{{ route('admin.merch.index') }}"
                                class="{{ str_contains($url, 'admin/categor') ? 'active' : '' }}">
                                <i class="fas fa-shopping-bag"></i>

                                <span>Мерч</span>

                            </a>
                        </li>
                    @endif
                    @if ($admin->role == 'superadmin')
                        <li>
                            <a href="{{ route('admin.category.index') }}"
                                class="{{ str_contains($url, 'admin/categor') ? 'active' : '' }}">
                                <i class="fas fa-percent"></i>
                                <span>Вероятности</span>
                            </a>
                        </li>
                    @endif
                    @if ($admin->role == 'superadmin' || in_array('payments.show', $permissions))
                        <li>
                            <a href="{{ route('payment.index') }}"
                                class="{{ str_contains($url, 'admin/categor') ? 'active' : '' }}">
                                <i class="fas fa-credit-card"></i>
                                <span>Оплаты</span>
                            </a>
                        </li>
                    @endif
                    @if ($admin->role == 'superadmin' || in_array('reports.show', $permissions))
                        <li>
                            <a href="{{ route('admin.report.index') }}"
                                class="{{ str_contains($url, 'admin/categor') ? 'active' : '' }}">
                                <i class="fas fa-chart-bar"></i>
                                <span>Отчеты</span>
                            </a>
                        </li>
                    @endif
                    @if ($admin->role == 'superadmin' || in_array('administrators.show', $permissions))
                        <li>
                            <a href="{{ route('admin.admin.index') }}">
                                <i class="fas fa-users"></i>
                                <span>Администраторы</span>
                            </a>
                        </li>
                    @endif

                    @if ($admin->role == 'superadmin')
                        <li>
                            <a href="/admin/settings">
                                <i class="fas fa-cog"></i>
                                <span>Настройки</span>
                            </a>
                        </li>
                    @endif
                    @if ($admin->role == 'superadmin' || in_array('broadcasts.show', $permissions))
                        <li>
                            <a href="{{ route('broadcast.index') }}">
                                <i class="fas fa-cog"></i>
                                <span>Рассылки</span>
                            </a>
                        </li>
                    @endif
                    <li>
                        <a href="{{ route('admin.logout') }}">
                            <i class="fas fa-sign-out-alt"></i>
                            <span>Выход</span>
                        </a>
                    </li>
                    @if ($admin->role != 'superadmin' && in_array('invites.create', $permissions))
                        <li>
                            <a href="{{ route('make.link') }}">
                                <i class="fas fa-sign-out-alt"></i>
                                <span>Пригласить в клуб</span>
                            </a>
                        </li>
                    @endif
                    <style>
                        /* Стили модального окна */
                        .modal {
                            display: none;
                            position: fixed;
                            z-index: 1100;
                            left: 0;
                            top: 0;
                            width: 100%;
                            height: 100%;
                            background-color: rgba(0, 0, 0, 0.5);
                        }

                        .modal-content {
                            background-color: white;
                            margin: 5% auto;
                            padding: 0;
                            border-radius: 8px;
                            width: 90%;
                            max-width: 500px;
                            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
                        }

                        .modal-header {
                            padding: 20px 25px;
                            border-bottom: 1px solid #ecf0f1;
                            display: flex;
                            justify-content: space-between;
                            align-items: center;
                        }

                        .modal-header h3 {
                            margin: 0;
                            font-size: 20px;
                            color: #2c3e50;
                        }

                        .close {
                            font-size: 28px;
                            font-weight: bold;
                            cursor: pointer;
                            color: #7f8c8d;
                        }

                        .close:hover {
                            color: #2c3e50;
                        }

                        .modal-body {
                            padding: 25px;
                        }

                        .invite-link-container {
                            display: flex;
                            gap: 10px;
                            margin: 20px 0;
                        }

                        .invite-link-container input {
                            flex: 1;
                            padding: 12px 15px;
                            font-size: 14px;
                            border: 1px solid #dfe6e9;
                            border-radius: 6px;
                            background-color: #f8f9fa;
                        }

                        #copyLinkBtn {
                            padding: 12px 20px;
                            white-space: nowrap;
                        }
                    </style>
                    <!-- Модальное окно приглашения в клуб -->
                    <div id="inviteModal" class="modal" style="display: none;">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h3>Пригласить в клуб</h3>
                                <span class="close">&times;</span>
                            </div>
                            <div class="modal-body">
                                <p>Поделитесь этой ссылкой, чтобы пригласить новых участников в ваш клуб:</p>
                                <div class="invite-link-container">
                                    <input type="text" id="inviteLink" readonly class="form-control">
                                    <button class="btn-primary" id="copyLinkBtn">Скопировать в буфер</button>
                                </div>
                                <div id="copyStatus" style="margin-top: 10px; color: #27ae60; display: none;">Ссылка
                                    скопирована!</div>
                            </div>
                        </div>
                    </div>
                    <script>
                        // Элементы модального окна
                        const inviteModal = document.getElementById('inviteModal');
                        const modalClose = document.querySelector('.close');
                        const inviteLinkInput = document.getElementById('inviteLink');
                        const copyLinkBtn = document.getElementById('copyLinkBtn');
                        const copyStatus = document.getElementById('copyStatus');

                        // Функция открытия модального окна и получения ссылки
                        async function openInviteModal() {
                            try {
                                // Показываем индикатор загрузки
                                inviteLinkInput.value = 'Загрузка...';
                                inviteModal.style.display = 'block';

                                // Запрос к контроллеру за уникальной ссылкой
                                const response = await fetch('/admin/club/generate-invite-link', {
                                    method: 'GET',
                                    headers: {
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                        'Content-Type': 'application/json'
                                    }
                                });

                                if (!response.ok) {
                                    throw new Error('Ошибка при получении ссылки');
                                }

                                const data = await response.json();

                                // Устанавливаем полученную ссылку в поле ввода
                                inviteLinkInput.value = data.link || 'Ошибка получения ссылки';
                            } catch (error) {
                                console.error('Ошибка:', error);
                                inviteLinkInput.value = 'Ошибка получения ссылки';
                            }
                        }

                        // Функция копирования ссылки в буфер обмена
                        async function copyInviteLink() {
                            try {
                                await navigator.clipboard.writeText(inviteLinkInput.value);

                                // Визуальная обратная связь
                                copyStatus.style.display = 'block';
                                setTimeout(() => {
                                    copyStatus.style.display = 'none';
                                }, 2000);
                            } catch (err) {
                                console.error('Ошибка копирования:', err);
                                fallbackCopyTextToClipboard(inviteLinkInput.value);
                            }
                        }

                        // Резервный метод копирования для браузеров без поддержки navigator.clipboard
                        function fallbackCopyTextToClipboard(text) {
                            const textArea = document.createElement('textarea');
                            textArea.value = text;
                            document.body.appendChild(textArea);
                            textArea.focus();
                            textArea.select();

                            try {
                                document.execCommand('copy');
                                copyStatus.style.display = 'block';
                                setTimeout(() => {
                                    copyStatus.style.display = 'none';
                                }, 2000);
                            } catch (err) {
                                console.error('Ошибка резервного копирования:', err);
                                alert('Не удалось скопировать ссылку. Пожалуйста, скопируйте её вручную.');
                            }

                            document.body.removeChild(textArea);
                        }

                        // Закрытие модального окна
                        function closeInviteModal() {
                            inviteModal.style.display = 'none';
                            inviteLinkInput.value = '';
                            copyStatus.style.display = 'none';
                        }

                        // Обработчики событий
                        modalClose.addEventListener('click', closeInviteModal);
                        copyLinkBtn.addEventListener('click', copyInviteLink);

                        // Закрытие по клику вне окна
                        window.addEventListener('click', (event) => {
                            if (event.target === inviteModal) {
                                closeInviteModal();
                            }
                        });

                        // Обновляем обработчик кнопки «Пригласить в клуб»
                        document.querySelector('a[href="{{ route('make.link') }}"]').addEventListener('click', function(e) {
                            e.preventDefault();
                            openInviteModal();
                        });
                    </script>
                @endisset
            </ul>
        </aside>


        <main class="main-content">
            @yield('content')
        </main>
    </div>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Summernote JS -->
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs5.min.js"></script>

      <script src="{{ asset('libs/vbox/script.js') }}"></script>
      <script src="{{ asset('js/vbox.js') }}"></script>

    <script>
        // Инициализация Summernote
        $(document).ready(function() {
            $('#description').summernote({
                height: 300,
                lang: 'ru-RU',
                toolbar: [
                    ['style', ['style']],
                    ['font', ['bold', 'italic', 'underline', 'clear']],
                    ['fontname', ['fontname']],
                    ['fontsize', ['fontsize']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['height', ['height']],
                    ['table', ['table']],
                    ['insert', ['link', 'picture', 'video']],
                    ['view', ['fullscreen', 'codeview', 'help']]
                ]
            });
        });

        // Превью загружаемых изображений
        document.getElementById('product-images').addEventListener('change', function(e) {
            const preview = document.getElementById('imagePreview');
            const files = e.target.files;

            for (let i = 0; i < files.length; i++) {
                const file = files[i];
                const reader = new FileReader();

                reader.onload = function(event) {
                    const div = document.createElement('div');
                    div.className = 'image-preview-item';
                    div.innerHTML = `
                        <img src="${event.target.result}" alt="Preview">
                        <button type="button" class="remove-image" onclick="this.parentElement.remove()">
                            <i class="fas fa-times"></i>
                        </button>
                    `;
                    preview.appendChild(div);
                };

                reader.readAsDataURL(file);
            }
        });

        // Удаление существующего изображения
        function removeExistingImage(imageId) {
            if (confirm('Вы уверены, что хотите удалить это изображение?')) {
                // Отправка AJAX запроса на удаление
                fetch(`/admin/products/images/${imageId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Content-Type': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            event.target.closest('.image-preview-item').remove();
                        }
                    });
            }
        }

        // // Поиск рекомендуемых товаров
        // document.getElementById('searchRelated').addEventListener('input', function(e) {
        //     const search = e.target.value.toLowerCase();
        //     const items = document.querySelectorAll('#relatedProducts .product-item');

        //     items.forEach(item => {
        //         const text = item.textContent.toLowerCase();
        //         item.style.display = text.includes(search) ? 'flex' : 'none';
        //     });
        // });

        // Drag and Drop для изображений
        const uploadContainer = document.querySelector('.image-upload-container');

        uploadContainer.addEventListener('dragover', (e) => {
            e.preventDefault();
            uploadContainer.style.borderColor = '#3498db';
            uploadContainer.style.backgroundColor = '#e3f2fd';
        });

        uploadContainer.addEventListener('dragleave', (e) => {
            e.preventDefault();
            uploadContainer.style.borderColor = '#dfe6e9';
            uploadContainer.style.backgroundColor = '#f8f9fa';
        });

        uploadContainer.addEventListener('drop', (e) => {
            e.preventDefault();
            uploadContainer.style.borderColor = '#dfe6e9';
            uploadContainer.style.backgroundColor = '#f8f9fa';

            const files = e.dataTransfer.files;
            const input = document.getElementById('product-images');
            input.files = files;

            // Trigger change event
            const event = new Event('change');
            input.dispatchEvent(event);
        });
    </script>
</body>

</html>
