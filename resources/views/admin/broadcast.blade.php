@extends('admin.layouts.main')
@section('content')
    <style>
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        .page-header {
            background-color: white;
            padding: 30px;
            border-radius: 8px;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .page-header h1 {
            font-size: 28px;
            color: #2c3e50;
            margin-bottom: 10px;
        }

        .page-header h1 i {
            color: #3498db;
            margin-right: 10px;
        }

        .breadcrumb {
            list-style: none;
            display: flex;
            gap: 10px;
            font-size: 14px;
        }

        .breadcrumb li {
            display: flex;
            align-items: center;
        }

        .breadcrumb li:not(:last-child)::after {
            content: '/';
            margin-left: 10px;
            color: #95a5a6;
        }

        .breadcrumb a {
            color: #3498db;
            text-decoration: none;
        }

        .breadcrumb a:hover {
            text-decoration: underline;
        }

        .breadcrumb .active {
            color: #7f8c8d;
        }

        .content-card {
            background-color: white;
            padding: 30px;
            border-radius: 8px;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .section-title {
            font-size: 22px;
            color: #2c3e50;
            margin-bottom: 25px;
            padding-bottom: 10px;
            border-bottom: 2px solid #ecf0f1;
        }

        .mb-4 {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #34495e;
            font-size: 14px;
        }

        .form-control {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #dfe6e9;
            border-radius: 6px;
            font-size: 14px;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            outline: none;
            border-color: #3498db;
            box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.1);
        }

        textarea.form-control {
            resize: vertical;
            font-family: inherit;
        }

        .image-upload-container {
            border: 2px dashed #bdc3c7;
            border-radius: 8px;
            padding: 40px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
        }

        .image-upload-container:hover {
            border-color: #3498db;
            background-color: #f8f9fa;
        }

        .image-upload-container i {
            font-size: 48px;
            color: #95a5a6;
            margin-bottom: 10px;
        }

        .image-upload-container p {
            color: #7f8c8d;
            font-size: 14px;
        }

        .image-upload-container input[type="file"] {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            opacity: 0;
            cursor: pointer;
        }

        .image-preview {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            margin-top: 15px;
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
            z-index: 10;
        }

        .image-preview-item .remove-image:hover {
            background-color: #c0392b;
            transform: scale(1.1);
        }

        .d-flex {
            display: flex;
        }

        .gap-3 {
            gap: 15px;
        }

        .flex-grow-1 {
            flex-grow: 1;
        }

        .btn {
            padding: 12px 24px;
            border: none;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-primary {
            background-color: #3498db;
            color: white;
        }

        .btn-primary:hover {
            background-color: #2980b9;
        }

        .btn-secondary {
            background-color: #95a5a6;
            color: white;
        }

        .btn-secondary:hover {
            background-color: #7f8c8d;
        }

        #testSend {
            background-color: #27ae60;
            color: white;
        }

        #testSend:hover {
            background-color: #219955;
        }

        .product-selector {
            max-height: 400px;
            overflow-y: auto;
            border: 1px solid #ecf0f1;
            border-radius: 8px;
        }

        .user-item {
            display: flex;
            align-items: center;
            padding: 12px 15px;
            border-bottom: 1px solid #ecf0f1;
            cursor: pointer;
            transition: background-color 0.2s ease;
        }

        .user-item:hover {
            background-color: #f8f9fa;
        }

        .user-item:last-child {
            border-bottom: none;
        }

        .user-item input[type="checkbox"] {
            margin-right: 12px;
            width: 18px;
            height: 18px;
            cursor: pointer;
        }

        .user-item img {
            width: 40px;
            height: 40px;
            object-fit: cover;
            border-radius: 50%;
            margin-right: 12px;
        }

        .user-item span {
            font-size: 14px;
            color: #2c3e50;
        }

        .action-buttons {
            display: flex;
            gap: 15px;
            margin-top: 20px;
        }

        .filters {
            display: flex;
            gap: 20px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }

        .filter-group {
            flex: 1;
            min-width: 200px;
        }

        .form-select {
            width: 100%;
            padding: 10px 15px;
            border: 1px solid #dfe6e9;
            border-radius: 6px;
            font-size: 14px;
            background-color: white;
            cursor: pointer;
        }

        .search-box {
            position: relative;
        }

        .search-box input {
            width: 100%;
            padding: 10px 40px 10px 15px;
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

        .products-table {
            overflow-x: auto;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
        }

        .table-header {
            background-color: #f8f9fa;
        }

        .table-header th {
            padding: 15px;
            text-align: left;
            font-weight: 600;
            color: #2c3e50;
            border-bottom: 2px solid #ecf0f1;
        }

        .table-row {
            transition: background-color 0.2s ease;
        }

        .table-row:hover {
            background-color: #f8f9fa;
        }

        .table-cell {
            padding: 15px;
            border-bottom: 1px solid #ecf0f1;
        }

        .product-status {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            display: inline-block;
        }

        .status-pending {
            background-color: #fff3cd;
            color: #856404;
        }

        .status-sent {
            background-color: #d4edda;
            color: #155724;
        }

        .status-failed {
            background-color: #f8d7da;
            color: #721c24;
        }

        .btn-action {
            padding: 8px 12px;
            font-size: 13px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }

        .btn-edit {
            background-color: #3498db;
            color: white;
        }

        .btn-edit:hover {
            background-color: #2980b9;
        }

        .btn-delete {
            background-color: #e74c3c;
            color: white;
        }

        .btn-delete:hover {
            background-color: #c0392b;
        }

        .loading-indicator {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }

        .spinner {
            border: 4px solid rgba(255, 255, 255, 0.3);
            border-top: 4px solid #ffffff;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        .alert {
            padding: 15px 20px;
            border-radius: 6px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .alert-error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        /* Modal window styles */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 2000;
            align-items: center;
            justify-content: center;
        }

        .modal.active {
            display: flex;
        }

        .modal-content {
            background-color: white;
            padding: 30px;
            border-radius: 8px;
            max-width: 600px;
            width: 90%;
            max-height: 80vh;
            overflow-y: auto;
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .modal-close {
            background: none;
            border: none;
            font-size: 24px;
            cursor: pointer;
            color: #7f8c8d;
        }

        .broadcast-details {
            line-height: 1.6;
        }

        .detail-item {
            margin-bottom: 15px;
        }

        .detail-label {
            font-weight: 600;
            color: #34495e;
            margin-bottom: 5px;
        }

        .detail-value {
            color: #2c3e50;
        }

        @media (max-width: 768px) {
            .image-preview-item {
                width: 120px;
                height: 120px;
            }

            .user-item img {
                width: 35px;
                height: 35px;
            }

            .action-buttons {
                flex-direction: column;
            }

            .filters {
                flex-direction: column;
            }

            .table {
                font-size: 12px;
            }

            .table-cell,
            .table-header th {
                padding: 10px;
            }

            .modal-content {
                margin: 20px;
                padding: 20px;
            }
        }
    </style>






















    <div class="container" x-data="broadcastApp()">
        <!-- Loading Indicator -->
        <div class="loading-indicator" x-show="loading" x-cloak>
            <div class="spinner"></div>
        </div>

        <!-- Alert Messages -->
        <div x-show="alert.show" x-cloak>
            <div :class="'alert alert-' + alert.type">
                <i :class="alert.type === 'success' ? 'fas fa-check-circle' : 'fas fa-exclamation-circle'"></i>
                <span x-text="alert.message"></span>
            </div>
        </div>

        <!-- Modal Window for Broadcast Details -->
        <div class="modal" :class="{ active: modal.show }" @click.self="closeModal()">
            <div class="modal-content">
                <div class="modal-header">
                    <h3>Детали рассылки #<span x-text="modal.data.id"></span></h3>
                    <button class="modal-close" @click="closeModal()">&times;</button>
                </div>
                <div class="broadcast-details">
                    <div class="detail-item">
                        <label class="detail-label">Текст сообщения:</label>
                        <p class="detail-value" x-text="modal.data.message"></p>
                    </div>
                    <div class="detail-item">
                        <label class="detail-label">Кнопка:</label>
                        <p class="detail-value">
                            <template x-if="modal.data.button_text && modal.data.button_url">
                                <span>
                                    Текст: <strong x-text="modal.data.button_text"></strong><br>
                                    Ссылка: <a :href="modal.data.button_url" target="_blank"
                                        x-text="modal.data.button_url"></a>
                                </span>
                            </template>
                            <template x-if="!modal.data.button_text || !modal.data.button_url">
                                <span>Не задана</span>
                            </template>
                        </p>
                    </div>
                    <div class="detail-item" x-show="modal.data.src_1 || modal.data.src_2">
                        <label class="detail-label">Изображения:</label>
                        <div class="image-preview">
                            <!-- Первое изображение -->
                            <template x-if="modal.data.src_1">
                                <div class="image-preview-item">
                                    <img :src="modal.data.src_1" :alt="'Изображение 1'">
                                </div>
                            </template>

                            <!-- Второе изображение -->
                            <template x-if="modal.data.src_2">
                                <div class="image-preview-item">
                                    <img :src="modal.data.src_2" :alt="'Изображение 2'">
                                </div>
                            </template>
                        </div>
                    </div>

                    <div class="detail-item" x-show="modal.data.images && modal.data.images.length > 0">
                        <label class="detail-label">Изображения:</label>
                        <div class="image-preview">
                            <template x-for="image in modal.data.images" :key="image.id">
                                <div class="image-preview-item">
                                    <img :src="image.url" :alt="'Image ' + image.id">
                                </div>
                            </template>
                        </div>
                    </div>
                    <div class="detail-item" x-show="modal.data.accounts && modal.data.accounts.length > 0">
                        <label class="detail-label">Получатели (<span x-text="modal.data.accounts.length"></span>):</label>
                        <div class="user-list">
                            <template x-for="account in modal.data.accounts" :key="account.id">
                                <div class="user-item" style="margin-bottom: 8px;">
                                    <img :src="account.avatar || '/images/default-avatar.jpg'" :alt="account.nickname"
                                        style="width: 30px; height: 30px; border-radius: 50%; margin-right: 10px;">
                                    <div>
                                        <div><span x-text="account.nickname"></span></div>
                                        <small style="color: #7f8c8d;">
                                            <span x-text="account.club?.title || 'Без клуба'"></span>
                                            • <span x-text="account.city || 'Город не указан'"></span>
                                        </small>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>

                    {{-- <div class="detail-item">
                        <label class="detail-label">Получатели:</label>
                        <p class="detail-value"><span x-text="modal.data.recipients_count"></span> пользователей</p>
                    </div> --}}
                    <div class="detail-item">
                        <label class="detail-label">Статус:</label>
                        <span class="product-status" :class="'status-' + modal.data.status"
                            x-text="getStatusText(modal.data.status)"></span>
                    </div>
                    <div class="detail-item">
                        <label class="detail-label">Время отправки:</label>
                        <p class="detail-value" x-text="modal.data.scheduled_time || 'Не задано"></p>
                    </div>
                    <div x-show="selectedUsers.length > 0">
                        <p>Выбранные получатели (<span x-text="selectedUsers.length"></span>):</p>
                        <ul>
                            <template x-for="user in selectedUsersData" :key="user.id">
                                <li x-text="user.nickname"></li>
                            </template>
                        </ul>
                    </div>

                    <div class="action-buttons">
                        <button x-show="modal.data.status != 'pending'" class="btn btn-primary"
                            @click="reSendBroadcast(modal.data.id)">
                            <i class="fas fa-redo"></i> Отправить снова
                        </button>
                    </div>
                    <button class="btn btn-primary" @click="sendBroadcast(modal.data.id)"
                        x-show="modal.data.status === 'pending'">
                        <i class="fas fa-paper-plane"></i> Отправить
                    </button>
                </div>
            </div>
        </div>

        <!-- Page Header -->
        <div class="page-header">
            <h1><i class="fas fa-paper-plane"></i> Рассылки Telegram</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li><a href="/">Главная</a></li>
                    <li class="active">Рассылки Telegram</li>
                </ol>
            </nav>
        </div>

        <!-- Create Broadcast Form -->
        <div class="content-card">
            <h2 class="section-title">Создание новой рассылки</h2>

            <form @submit.prevent="submitBroadcast()">
                <!-- Message Text -->
                <div class="mb-4">
                    <label class="form-label" for="broadcastMessage">Текст рассылки</label>
                    <textarea class="form-control" id="broadcastMessage" rows="6"
                        placeholder="Введите текст сообщения для рассылки..." required x-model="formData.message"></textarea>
                </div>

                <!-- Button Settings -->
                <div class="mb-4">
                    <label class="form-label">Кнопка к сообщению (опционально)</label>
                    <div class="d-flex gap-3">
                        <input type="text" class="form-control flex-grow-1" placeholder="Текст кнопки"
                            x-model="formData.buttonText">
                        <input type="url" class="form-control flex-grow-1"
                            placeholder="Ссылка (например, https://example.com)" x-model="formData.buttonUrl">
                    </div>
                </div>

                <!-- Image Upload -->
                <div class="mb-4">
                    <label class="form-label">Изображения для рассылки</label>
                    <div class="image-upload-container" @click="$refs.fileInput.click()">
                        <i class="fas fa-cloud-upload-alt"></i>
                        <p>Перетащите изображения сюда или кликните для выбора</p>
                        <input type="file" multiple accept="image/*" x-ref="fileInput"
                            @change="handleImageUpload($event)" style="display: none;">
                    </div>

                    <!-- Image Preview -->
                    <div class="image-preview" x-show="imageFiles.length > 0">
                        <template x-for="(file, index) in imageFiles" :key="index">
                            <div class="image-preview-item">
                                <img :src="file.preview" :alt="'Image ' + (index + 1)">
                                <button type="button" class="remove-image" @click="removeImage(index)">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </template>
                    </div>

                </div>

                <!-- Scheduled Time -->
                <div class="mb-4">
                    <label class="form-label">Время отправки</label>
                    <div class="d-flex gap-3">
                        <div class="flex-grow-1">
                            <input type="datetime-local" class="form-control" x-model="formData.scheduled_time">
                        </div>
                        <div>
                            {{-- <button type="button" class="btn btn-secondary" @click="sendNow()">
                                <i class="fas fa-bolt"></i> Отправить сейчас
                            </button> --}}
                        </div>
                    </div>
                </div>

                <!-- Recipients Selection -->
                <div class="mb-4">
                    <label class="form-label">Получатели (<span x-text="filteredUsers.length"></span> всего / <span
                            x-text="selectedUsers.length"></span> выбрано)</label>


                    <!-- Filters for users -->
                    <div class="filters" style="margin-bottom: 15px;">
                        <div class="filter-group">
                            <label class="form-label">Клуб</label>
                            <select class="form-select" x-model="userFilters.club" @change="filterUsers()">
                                <option value="">Все клубы</option>
                                <template x-for="club in clubs" :key="club">
                                    <option :value="club.title" x-text="club.title"></option>
                                </template>
                            </select>
                        </div>

                        <div class="filter-group">
                            <label class="form-label">Город</label>
                            <select class="form-select" x-model="userFilters.city" @change="filterUsers()">
                                <option value="">Все города</option>
                                <template x-for="city in cities" :key="city">
                                    <option :value="city.title" x-text="city.title"></option>
                                </template>
                            </select>
                        </div>

                        <div class="filter-group search-box">
                            <label class="form-label">Поиск</label>
                            <input type="text" placeholder="Поиск по имени..." x-model="userFilters.search"
                                @input="filterUsers()">
                            <i class="fas fa-search"></i>
                        </div>
                    </div>
                    <div class="mb-3 d-flex align-items-center">
                        <label class="form-check-label d-flex align-items-center gap-2" style="cursor: pointer;">
                            <input type="checkbox" class="form-check-input"
                                :checked="filteredUsers.length > 0 && selectedUsers.length === filteredUsers.length"
                                @change="toggleSelectAll($event.target.checked)">
                            <strong>Выбрать всех отфильтрованных (<span x-text="filteredUsers.length"></span>)</strong>
                        </label>
                    </div>
                    <!-- User Selection List -->
                    <div class="product-selector">
                        <template x-for="user in filteredUsers" :key="user.id">
                            <div class="user-item" @click="toggleUser(user.id)">
                                <input type="checkbox" :checked="selectedUsers.includes(user.id)"
                                    @click.stop="toggleUser(user.id)">
                                <img :src="user.avatar || '/def.svg'" :alt="user.nickname">

                                <div style="flex: 1;">
                                    <div><span x-text="user.nickname || user.name"></span></div>
                                    <small style="color: #7f8c8d;">
                                        <span x-text="user.club?.title || 'Без клуба'"></span>
                                        • <span x-text="user.city"></span>
                                    </small>
                                </div>
                            </div>
                        </template>
                        <template x-if="filteredUsers.length === 0">
                            <div class="user-item" style="justify-content: center; color: #95a5a6;">
                                Пользователи не найдены
                            </div>
                        </template>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="action-buttons">
                    <button type="button" class="btn btn-primary" id="testSend" @click="sendTest()">
                        <i class="fas fa-play"></i> Тестовая отправка мне
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-paper-plane"></i> Создать рассылку
                    </button>
                </div>
            </form>
        </div>

        <!-- Broadcasts History -->
        <div class="content-card">
            <h2 class="section-title">История рассылок</h2>

            <!-- Filters -->
            <div class="filters">
                <div class="filter-group">
                    <label class="form-label">Статус</label>
                    <select class="form-select" x-model="filters.status" @change="filterBroadcasts()">
                        <option value="">Все статусы</option>
                        <option value="pending">Ожидает отправки</option>
                        <option value="sent">Отправлена</option>
                        <option value="failed">Ошибка</option>
                    </select>
                </div>

                <div class="filter-group search-box">
                    <input type="text" placeholder="Поиск по тексту..." x-model="filters.search"
                        @input="filterBroadcasts()">
                    <i class="fas fa-search"></i>
                </div>
            </div>

            <!-- Broadcasts Table -->
            <div class="products-table">
                <table class="table">
                    <thead class="table-header">
                        <tr>
                            <th>ID</th>
                            <th>Текст сообщения</th>
                            <th>Кнопка</th>
                            {{-- <th>Получатели</th> --}}
                            <th>Статус</th>
                            <th>Время отправки</th>
                            <th>Действия</th>
                        </tr>
                    </thead>
                    <tbody>
                        <template x-for="broadcast in filteredBroadcasts" :key="broadcast.id">
                            <tr class="table-row">
                                <td class="table-cell" x-text="broadcast.id"></td>
                                <td class="table-cell"
                                    style="max-width: 200px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                    <span x-text="broadcast.message"></span>
                                </td>
                                <td class="table-cell">
                                    <template x-if="broadcast.button_text && broadcast.button_url">
                                        <span>
                                            <strong x-text="broadcast.button_text"></strong><br>
                                            <small x-text="broadcast.button_url"></small>
                                        </span>
                                    </template>
                                    <template x-if="!broadcast.button_text || !broadcast.button_url">
                                        <span>Не задана</span>
                                    </template>
                                </td>
                                {{-- <td class="table-cell" x-text="broadcast.recipients_count + ' пользователей'"></td> --}}
                                <td class="table-cell">
                                    <span class="product-status" :class="'status-' + broadcast.status"
                                        x-text="getStatusText(broadcast.status)"></span>
                                </td>
                                <td class="table-cell"
                                    x-text="broadcast.scheduled_time ?
  new Date(broadcast.scheduled_time).toLocaleDateString('ru-RU', {
    day: '2-digit',
    month: '2-digit',
    year: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  }) :
  'Не назначено'">
                                </td>


                                <td class="table-cell">
                                    <div class="action-buttons" style="margin-top: 0;">
                                        <button class="btn-action btn-edit" @click="viewBroadcast(broadcast.id)">
                                            <i class="fas fa-eye"></i> Просмотр
                                        </button>
                                        <button class="btn-action btn-delete" @click="deleteBroadcast(broadcast.id)">
                                            <i class="fas fa-trash"></i> Удалить
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </template>
                        <tr x-show="filteredBroadcasts.length === 0">
                            <td colspan="7" class="table-cell" style="text-align: center; color: #95a5a6;">
                                Нет данных для отображения
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        function broadcastApp() {
            return {
                // Form data
                formData: {
                    broadcast_id: '',
                    message: '',
                    buttonText: '',
                    buttonUrl: '',
                    scheduled_time: '',
                },

                // Images
                imageFiles: [],

                // Users
                users: @json($users),
                // users: [{
                //         id: 1,
                //         name: 'Иван Петров',
                //         avatar: 'https://i.pravatar.cc/150?img=1',
                //         club: 'Фитнес Центр "Энергия"',
                //         city: 'Москва'
                //     },
                //     {
                //         id: 2,
                //         name: 'Мария Сидорова',
                //         avatar: 'https://i.pravatar.cc/150?img=2',
                //         club: 'Спортклуб "Олимп"',
                //         city: 'Санкт-Петербург'
                //     },
                //     {
                //         id: 3,
                //         name: 'Алексей Смирнов',
                //         avatar: 'https://i.pravatar.cc/150?img=3',
                //         club: 'Фитнес Центр "Энергия"',
                //         city: 'Москва'
                //     },
                //     {
                //         id: 4,
                //         name: 'Елена Иванова',
                //         avatar: 'https://i.pravatar.cc/150?img=4',
                //         club: 'Клуб "Атлант"',
                //         city: 'Казань'
                //     }
                // ],
                filteredUsers: [],
                selectedUsers: [],

                // User filters
                userFilters: {
                    club: '',
                    city: '',
                    search: ''
                },

                // Dynamic lists for filters
                clubs: @json($clubs),
                cities: [],

                // Broadcasts history
                broadcasts: @json($broadcasts),
                filteredBroadcasts: [],

                // Filters
                filters: {
                    status: '',
                    search: ''
                },

                // UI state
                loading: false,
                alert: {
                    show: false,
                    type: 'success',
                    message: ''
                },

                // Modal
                modal: {
                    show: false,
                    data: {}
                },

                // API configuration
                apiUrl: 'https://your-server.com/api', // Замените на ваш URL сервера

                // Initialization
                init() {
                    this.filteredBroadcasts = this.broadcasts;
                    this.filteredUsers = this.users;


                    this.loadUsers();
                    this.loadBroadcasts();
                },

                // Image handling
                handleImageUpload(event) {
                    const files = Array.from(event.target.files);

                    files.forEach(file => {
                        if (file.type.startsWith('image/')) {
                            const reader = new FileReader();
                            reader.onload = (e) => {
                                this.imageFiles.push({
                                    file: file,
                                    preview: e.target.result
                                });
                            };
                            reader.readAsDataURL(file);
                        }
                    });
                },

                removeImage(index) {
                    this.imageFiles.splice(index, 1);
                },

                // User selection
                toggleUser(userId) {
                    const index = this.selectedUsers.indexOf(userId);
                    if (index === -1) {
                        this.selectedUsers.push(userId);
                    } else {
                        this.selectedUsers.splice(index, 1);
                    }
                },
                toggleSelectAll(isSelected) {
                    if (isSelected) {
                        // Собираем ID только тех пользователей, которые подходят под текущий фильтр
                        this.selectedUsers = this.filteredUsers.map(user => user.id);
                    } else {
                        // Сбрасываем выбор
                        this.selectedUsers = [];
                    }
                },

                // Filter users
                filterUsers() {
                    const search = String(this.userFilters.search ?? '').trim().toLowerCase();

                    this.filteredUsers = this.users.filter(user => {
                        const nickname = String(user.nickname ?? '').toLowerCase();

                        const matchesClub = !this.userFilters.club ||
                            user.club?.title === this.userFilters.club;

                        const matchesCity = !this.userFilters.city ||
                            user.city === this.userFilters.city;

                        const matchesSearch = !search || nickname.includes(search);

                        return matchesClub && matchesCity && matchesSearch;
                    });
                },
                get selectedUsersData() {
                    return this.users.filter(user => this.selectedUsers.includes(user.id));
                },


                sendNow() {
                    if (!this.formData.message) {
                        this.showAlert('error', 'Введите текст сообщения');
                        return;
                    }

                    if (this.selectedUsers.length === 0) {
                        this.showAlert('error', 'Выберите получателей');
                        return;
                    }

                    this.loading = true;

                    const formData = new FormData();
                    formData.append('message', this.formData.message);
                    if (this.formData.buttonText) {
                        formData.append('button_text', this.formData.buttonText);
                    }
                    if (this.formData.buttonUrl) {
                        formData.append('button_url', this.formData.buttonUrl);
                    }


                    // Добавляем выбранных пользователей
                    this.selectedUsers.forEach(userId => {
                        formData.append('recipients[]', userId);
                    });

                    this.imageFiles.forEach(file => {
                        formData.append('images[]', file.file);
                    });

                    // Реальный API-запрос
                    fetch('{{ route('broadcast.send') }}', {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            },
                        })
                        .then(response => response.json())
                        .then(data => {
                            this.loading = false;
                            this.showAlert('success', 'Рассылка отправлена!');
                            this.resetForm();
                        })
                        .catch(error => {
                            this.loading = false;
                            this.showAlert('error', 'Ошибка отправки: ' + error.message);
                        });
                },

                // Test send
                sendTest() {
                    if (!this.formData.message) {
                        this.showAlert('error', 'Введите текст сообщения');
                        return;
                    }

                    this.loading = true;

                    const formData = new FormData();
                    formData.append('message', this.formData.message);
                    if (this.formData.buttonText) {
                        formData.append('button_text', this.formData.buttonText);
                    }
                    if (this.formData.buttonUrl) {
                        formData.append('button_url', this.formData.buttonUrl);
                    }

                    this.imageFiles.forEach(file => {
                        formData.append('images[]', file.file);
                    });

                    // Реальный API-запрос
                    fetch('/admin/send-test-broadcast', {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                        })
                        .then(response => response.json())
                        .then(data => {
                            this.loading = false;
                            this.showAlert('success', 'Тестовая рассылка отправлена!');
                        })
                        .catch(error => {
                            this.loading = false;
                            this.showAlert('error', 'Ошибка отправки: ' + error.message);
                        });
                },

                async submitBroadcast() {
                    if (!this.formData.message || this.selectedUsers.length === 0) {
                        this.showAlert('error', 'Заполните все обязательные поля и выберите получателей');
                        return;
                    }

                    this.loading = true;

                    const formData = new FormData();
                    formData.append('message', this.formData.message);
                    if (this.formData.buttonText) {
                        formData.append('button_text', this.formData.buttonText);
                    }
                    if (this.formData.buttonUrl) {
                        formData.append('button_url', this.formData.buttonUrl);
                    }
                    if (this.formData.scheduled_time) {
                        formData.append('scheduled_time', this.formData.scheduled_time);
                    }

                    // Добавляем выбранных пользователей
                    this.selectedUsers.forEach(userId => {
                        formData.append('recipients[]', userId);
                    });

                    this.imageFiles.forEach(file => {
                        formData.append('images[]', file.file);
                    });

                    try {
                        const response = await fetch('{{ route('broadcast.store') }}', {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            }
                        });

                        if (!response.ok) {
                            throw new Error(`HTTP error! status: ${response.status}`);
                        }

                        const data = await response.json();

                        if (data.success && Array.isArray(data.broadcasts)) {
                            this.broadcasts = data.broadcasts;
                            this.filteredBroadcasts = [...data.broadcasts];
                            this.filterBroadcasts();
                            this.resetForm();
                            this.showAlert('success', 'Рассылка создана успешно!');
                        } else {
                            throw new Error('Сервер вернул неверный формат данных');
                        }
                    } catch (error) {
                        console.error('Ошибка создания рассылки:', error);
                        this.showAlert('error', 'Ошибка при создании рассылки: ' + error.message);
                    } finally {
                        this.loading = false;
                    }
                },
                sendBroadcastNow(broadcastId) {
                    if (!broadcastId) {
                        this.showAlert('error', 'ID рассылки не указан');
                        return;
                    }

                    this.loading = true;

                    fetch(`${this.apiUrl}/send/${broadcastId}`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Content-Type': 'application/json'
                            }
                        })
                        .then(response => {
                            if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
                            return response.json();
                        })
                        .then(data => {
                            this.showAlert('success', 'Рассылка отправлена!');
                            this.loadBroadcasts();
                        })
                        .catch(error => {
                            console.error('Ошибка отправки рассылки:', error);
                            this.showAlert('error', 'Ошибка при отправке рассылки: ' + error.message);
                        })
                        .finally(() => {
                            this.loading = false;
                        });
                },




                // Reset form
                resetForm() {
                    this.formData = {
                        message: '',
                        buttonText: '',
                        buttonUrl: '',
                        scheduled_time: ''
                    };
                    this.imageFiles = [];
                    this.selectedUsers = [];
                },

                // Load data
                loadUsers() {
                    // Реальный API-запрос для загрузки пользователей
                    // fetch(`${this.apiUrl}/users`)
                    //     .then(response => response.json())
                    //     .then(data => {
                    //         this.users = data;
                    //         this.filteredUsers = data;
                    //         this.clubs = [...new Set(data.map(u => u.club))].sort();
                    //         this.cities = [...new Set(data.map(u => u.city))].sort();
                    //     })
                    //     .catch(error => this.showAlert('error', 'Ошибка загрузки пользователей: ' + error.message));
                },

                loadBroadcasts() {
                    // Реальный API-запрос для загрузки рассылок
                    // fetch(`/get/broadcasts`)
                    //     .then(response => response.json())
                    //     .then(data => {
                    //         this.broadcasts = data;
                    //         this.filteredBroadcasts = data;
                    //     })
                    //     .catch(error => this.showAlert('error', 'Ошибка загрузки рассылок: ' + error.message));
                },

                // Filter broadcasts
                filterBroadcasts() {
                    this.filteredBroadcasts = this.broadcasts.filter(broadcast => {
                        const matchesStatus = !this.filters.status || broadcast.status === this.filters.status;
                        const matchesSearch = !this.filters.search ||
                            broadcast.message.toLowerCase().includes(this.filters.search.toLowerCase());
                        return matchesStatus && matchesSearch;
                    });
                },

                // Show alert message
                showAlert(type, message) {
                    this.alert = {
                        show: true,
                        type: type,
                        message: message
                    };

                    // Автоматическое скрытие через 5 секунд
                    setTimeout(() => {
                        this.alert.show = false;
                    }, 5000);
                },

                // Close modal
                closeModal() {
                    this.modal.show = false;
                    this.modal.data = {};
                },

                // View broadcast details
                viewBroadcast(id) {
                    const broadcast = this.broadcasts.find(b => b.id === id);
                    if (broadcast) {
                        this.modal.data = broadcast;
                        this.modal.show = true;
                    }
                },

                // Delete broadcast
                deleteBroadcast(id) {
                    if (confirm('Вы уверены, что хотите удалить эту рассылку?')) {
                        // Меняем URL на /broadcast-delete/{id} и метод на POST
                        fetch(`/broadcast-delete/${id}`, {
                                method: 'POST', // Так как в Route::post
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                }
                            })
                            .then(response => {
                                if (response.ok) {
                                    this.loadBroadcasts();
                                    this.showAlert('success', 'Рассылка удалена');
                                } else {
                                    throw new Error('Ошибка удаления');
                                }
                                window.location.reload();
                            })
                            .catch(error => this.showAlert('error', 'Ошибка удаления: ' + error.message));
                    }
                },


                // Send broadcast immediately
                sendBroadcast(id) {
                    if (confirm('Вы уверены, что хотите отправить эту рассылку сейчас?')) {
                        this.loading = true;

                        fetch('{{ route('broadcast.send') }}', {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'Content-Type': 'application/json'
                                },
                                body: JSON.stringify({
                                    id
                                })
                            })
                            .then(response => {
                                if (!response.ok) {
                                    throw new Error(`HTTP error! status: ${response.status}`);
                                }
                                return response.json();
                            })
                            .then(data => {
                                this.showAlert('success', 'Рассылка отправлена!');
                                // Обновляем данные в модальном окне
                                this.modal.data.status = 'sent';
                                this.modal.data.scheduled_time = new Date().toLocaleString();
                                // Перезагружаем историю рассылок
                                this.loadBroadcasts();
                            })
                            .catch(error => {
                                console.error('Ошибка отправки рассылки:', error);
                                this.showAlert('error', 'Ошибка при отправке рассылки: ' + error.message);
                            })
                            .finally(() => {
                                this.loading = false;
                            });
                    }
                },


                // Resend broadcast
                reSendBroadcast(id) {
                    if (confirm('Отправить эту рассылку повторно?')) {
                        fetch(`${this.apiUrl}/resend/${id}`, {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                }
                            })
                            .then(response => {
                                if (response.ok) {
                                    this.showAlert('success', 'Рассылка отправлена повторно');
                                    this.loadBroadcasts();
                                } else {
                                    throw new Error('Ошибка повторной отправки');
                                }
                            })
                            .catch(error => this.showAlert('error', 'Ошибка повторной отправки: ' + error.message));
                    }
                },

                // Get status text
                getStatusText(status) {
                    const statusMap = {
                        pending: 'Ожидает отправки',
                        sent: 'Отправлена',
                        failed: 'Ошибка'
                    };
                    return statusMap[status] || status;
                }
            };
        }
    </script>
@endsection
