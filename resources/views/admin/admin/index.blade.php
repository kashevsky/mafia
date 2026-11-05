@extends('admin.layouts.main')
@section('content')
    <style>
        .admin-wrapper {
            display: flex;
            min-height: 100vh;
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
            list-style: none;
            display: flex;
            gap: 5px;
        }

        .breadcrumb li:not(:last-child)::after {
            content: '/';
            margin-left: 5px;
            color: #95a5a6;
        }

        .breadcrumb a {
            color: #3498db;
            text-decoration: none;
        }

        .breadcrumb a:hover {
            text-decoration: underline;
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
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        /* Filters */
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

        .form-label {
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 8px;
            display: block;
        }

        .form-select {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #dfe6e9;
            border-radius: 6px;
            font-size: 14px;
            background-color: white;
            cursor: pointer;
        }

        /* Table */
        .products-table {
            width: 100%;
            overflow-x: auto;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
        }

        .table-header {
            background-color: #ecf0f1;
        }

        .table-header th {
            padding: 15px 20px;
            text-align: left;
            font-weight: 600;
            color: #2c3e50;
            border-bottom: 2px solid #dfe6e9;
        }

        .table-row {
            border-bottom: 1px solid #ecf0f1;
            transition: background-color 0.2s ease;
        }

        .table-row:hover {
            background-color: #f8f9fa;
        }

        .table-cell {
            padding: 15px 20px;
            vertical-align: middle;
        }

        .admin-name {
            font-weight: 600;
            color: #2c3e50;
        }

        .admin-role {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .role-superadmin {
            background-color: #e8f5e9;
            color: #2e7d32;
        }

        .role-admin {
            background-color: #e3f2fd;
            color: #1565c0;
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
            gap: 5px;
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

        .btn-primary {
            background-color: #3498db;
            border: none;
            padding: 12px 24px;
            font-weight: 600;
            border-radius: 6px;
            transition: all 0.3s ease;
            color: white;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-primary:hover {
            background-color: #2980b9;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(52, 152, 219, 0.3);
        }

        /* Modal */
        .modal {
            display: none;
            position: fixed;
            z-index: 1100;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            overflow-y: auto;
            /* Включаем прокрутку для всего модального окна */
        }

        .modal.active {
            display: flex;
            align-items: flex-start;
            /* Смещение к верху */
            justify-content: center;
            padding: 20px;
            /* Отступы от краёв экрана */
        }

        .modal-content {
            background-color: white;
            margin: auto;
            padding: 0;
            border-radius: 8px;
            width: 90%;
            max-width: 600px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
            max-height: calc(100vh - 40px);
            /* Максимальная высота с учётом отступов */
            overflow-y: auto;
            /* Прокрутка внутри модального окна, если контент длинный */
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
            background: none;
            border: none;
        }

        .close:hover {
            color: #2c3e50;
        }

        .modal-body {
            padding: 25px;
        }

        .modal-footer {
            padding: 20px 25px;
            border-top: 1px solid #ecf0f1;
            display: flex;
            gap: 10px;
            justify-content: flex-end;
        }

        .form-group {
            margin-bottom: 20px;
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
            box-shadow: 0 0 0 0.2rem rgba(52, 152, 219, 0.15);
        }

        .permissions-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 15px;
            margin-top: 10px;
        }

        .permission-item {
            display: flex;
            align-items: center;
            padding: 10px;
            background-color: #f8f9fa;
            border-radius: 6px;
            transition: background-color 0.2s ease;
        }

        .permission-item:hover {
            background-color: #e9ecef;
        }

        .permission-item input[type="checkbox"] {
            width: 18px;
            height: 18px;
            margin-right: 10px;
            cursor: pointer;
        }

        .permission-item label {
            cursor: pointer;
            margin: 0;
            font-size: 14px;
            color: #2c3e50;
        }

        .btn-secondary {
            background-color: #95a5a6;
            border: none;
            padding: 10px 20px;
            font-weight: 600;
            border-radius: 6px;
            transition: all 0.3s ease;
            color: white;
            cursor: pointer;
        }

        .btn-secondary:hover {
            background-color: #7f8c8d;
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

        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                width: 0;
                overflow: hidden;
            }

            .main-content {
                margin-left: 0;
            }

            .permissions-grid {
                grid-template-columns: 1fr;
            }

            .filters {
                flex-direction: column;
            }

            .products-table {
                font-size: 14px;
            }

            .table-cell {
                padding: 12px 10px;
            }
        }
    </style>

    <div x-data="adminManagement()">
        <!-- Sidebar -->


        <!-- Alert Messages -->
        <div x-show="alert.show" x-cloak>
            <div :class="'alert alert-' + alert.type">
                <i :class="alert.type === 'success' ? 'fas fa-check-circle' : 'fas fa-exclamation-circle'"></i>
                <span x-text="alert.message"></span>
            </div>
        </div>

        <!-- Page Header -->
        <div class="page-header">
            <h1><i class="fas fa-users"></i> Управление администраторами</h1>
            <ul class="breadcrumb">
                <li><a href="/admin">Главная</a></li>
                <li>Администраторы</li>
            </ul>
        </div>

        <!-- Content Card -->
        <div class="content-card">
            @if ($admin->role == 'superadmin' || in_array('administrators.create', $permissions))
                <div class="section-title">
                    <span>Список администраторов</span>
                    <button class="btn-primary" @click="openCreateModal()">
                        <i class="fas fa-plus"></i> Добавить администратора
                    </button>
                </div>
            @endif

            <!-- Filters -->
            <div class="filters">

                <div class="filter-group">
                    <label class="form-label">Клуб</label>
                    <select class="form-select" x-model="filters.club" @change="filterAdmins()">
                        <option value="">Все клубы</option>
                        <template x-for="club in clubs" :key="club.id">
                            <option :value="club.id" x-text="club.title"></option>
                        </template>
                    </select>
                </div>

                <div class="filter-group">
                    <label class="form-label">Роль</label>
                    <select class="form-select" x-model="filters.role" @change="filterAdmins()">
                        <option value="">Все роли</option>
                        @if ($admin->role == 'superadmin')
                            <option value="superadmin">Супер админ</option>
                        @endif
                        <option value="admin">Администратор</option>
                        <option value="hoster">Ведущий</option>
                    </select>
                </div>

                <div class="filter-group search-box">
                    <label class="form-label">Поиск</label>
                    <input type="text" placeholder="Поиск по имени..." x-model="filters.search" @input="filterAdmins()">
                    <i class="fas fa-search"></i>
                </div>
            </div>

            <!-- Admins Table -->
            <div class="products-table">
                <table class="table">
                    <thead class="table-header">
                        <tr>
                            <th>ID</th>
                            <th>Имя</th>
                            <th>Логин</th>
                            <th>Клуб</th>
                            <th>Роль</th>
                            <th>Дата создания</th>
                            <th>Действия</th>
                        </tr>
                    </thead>
                    <tbody>
                        <template x-for="admin in filteredAdmins" :key="admin.id">
                            <tr class="table-row">
                                <td class="table-cell" x-text="admin.id"></td>
                                <td class="table-cell">
                                    <span class="admin-name" x-text="admin.name"></span>
                                </td>
                                <td class="table-cell" x-text="admin.username"></td>
                                <td class="table-cell">
                                    <template x-for="club_id in admin.club_ids" :key="club_id">
                                        <span class="selected-tag">
                                            <span x-text="getClubName(club_id)"></span>
                                        </span>
                                    </template>
                                </td>


                                <td class="table-cell">
                                    <span class="admin-role"
                                        :class="admin.role === 'superadmin' ? 'role-superadmin' : 'role-admin'"
                                        x-text="admin.role === 'superadmin' ? 'Супер админ' : 'Администратор'"></span>
                                </td>
                                <td class="table-cell" x-text="admin.created_at"></td>
                                <td class="table-cell">
                                    <div class="action-buttons">
                                        @if ($admin->role == 'superadmin' || in_array('administrators.edit', $permissions))
                                            <button class="btn-action btn-edit" @click="openEditModal(admin)">
                                                <i class="fas fa-edit"></i> Изменить
                                            </button>
                                        @endif
                                        @if ($admin->role == 'superadmin' || in_array('administrators.delete', $permissions))
                                            <button class="btn-action btn-delete" @click="deleteAdmin(admin.id)">
                                                <i class="fas fa-trash"></i> Удалить
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        </template>
                        <tr x-show="filteredAdmins.length === 0">
                            <td colspan="7" class="table-cell" style="text-align: center; color: #95a5a6;">
                                Администраторы не найдены
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>


        <!-- Create/Edit Modal -->
        <div class="modal" :class="{ active: modal.show }" @click.self="closeModal()">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 x-text="modal.isEdit ? 'Редактировать администратора' : 'Создать администратора'"></h3>
                    <button class="close" @click="closeModal()">&times;</button>
                </div>
                <div class="modal-body">
                    <form @submit.prevent="saveAdmin()">
                        <div class="form-group">
                            <label class="form-label">Имя</label>
                            <input type="text" class="form-control" x-model="modal.data.name" placeholder="Введите имя"
                                required>
                        </div>


                        <div class="form-group">
                            <label class="form-label">Пароль</label>
                            <input type="password" class="form-control" x-model="modal.data.password"
                                :placeholder="modal.isEdit ? 'Оставьте пустым, чтобы не менять' : 'Введите пароль'"
                                :required="!modal.isEdit">
                        </div>

                        <div class="form-group">
                            <label class="form-label">Telegram ID</label>
                            <input type="password" class="form-control" x-model="modal.data.telegram_id"
                                :placeholder="modal.isEdit ? 'Оставьте пустым, чтобы не менять' : 'Введите telegram id'"
                                :required="!modal.isEdit">
                        </div>

                        <div class="form-group">
                            <label class="form-label">Клубы</label>
                            <select class="form-select" x-model="modal.data.selected_club_ids" multiple size="4">
                                <option value="">Выберите клубы</option>
                                <template x-for="club in clubs" :key="club.id">
                                    <option :value="club.id" x-text="club.title"></option>
                                </template>
                            </select>
                        </div>



                        <div class="form-group">
                            <label class="form-label">Роль</label>
                            <select class="form-select" x-model="modal.data.role" required>
                                <option value="admin">Администратор</option>
                                @if ($admin->role == 'superadmin')
                                    <option value="superadmin">Супер админ</option>
                                @endif
                                <option value="hoster">Ведущий</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Права доступа к вкладкам</label>
                            <div class="permissions-grid">
                                <template x-for="permission in availablePermissions" :key="permission.value">
                                    <div class="permission-item">
                                        <input type="checkbox" :id="'perm-' + permission.value" :value="permission.value"
                                            x-model="modal.data.permissions">
                                        <label :for="'perm-' + permission.value" x-text="permission.label"></label>
                                    </div>
                                </template>
                            </div>
                        </div>

                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-secondary" @click="closeModal()">Отмена</button>
                    <button type="button" class="btn-primary" @click="saveAdmin()">
                        <i class="fas fa-save"></i>
                        <span x-text="modal.isEdit ? 'Сохранить' : 'Создать'"></span>
                    </button>
                </div>
            </div>
        </div>

    </div>

    <script>
        function adminManagement() {
            return {
                // Admins data
                admins: @json($admins),

                filteredAdmins: [],

                // Clubs list
                clubs: @json($clubs),

                // Available permissions
                availablePermissions: [{
                        value: 'clubs.show',
                        label: 'Просмотр клубов'
                    },
                    {
                        value: 'clubs.edit',
                        label: 'Редактирование клубов'
                    },
                    {
                        value: 'clubs.delete',
                        label: 'Удаление клубов'
                    },
                    {
                        value: 'players.show',
                        label: 'Просмотр игроков'
                    },
                    {
                        value: 'players.edit',
                        label: 'Редактирование игроков'
                    },
                    {
                        value: 'players.delete',
                        label: 'Удаление игроков'
                    },
                    {
                        value: 'games.show',
                        label: 'Просмотр игр',
                    },
                    {
                        value: 'games.edit',
                        label: 'Редактирование игр',
                    },
                    {
                        value: 'games.create',
                        label: 'Создание игр',
                    },
                    {
                        value: 'games.delete',
                        label: 'Удаление игр',
                    },
                    {
                        value: 'payments.show',
                        label: 'Просмотр оплат',
                    },
                    {
                        value: 'payments.approve',
                        label: 'Одобрение оплат',
                    },
                    {
                        value: 'reports.show',
                        label: 'Просмотр отчетов',
                    },
                    {
                        value: 'day-reports.fill',
                        label: 'Просмотр дневных отчетов',
                    },
                    {
                        value: 'administrators.show',
                        label: 'Просмотр администраторов',
                    },
                    {
                        value: 'administrators.edit',
                        label: 'Редактирование администраторов',
                    },
                    {
                        value: 'administrators.create',
                        label: 'Создание администраторов',
                    },
                    {
                        value: 'administrators.delete',
                        label: 'Удаление администраторов',
                    },
                    {
                        value: 'broadcasts.show',
                        label: 'Просмотр рассылок',
                    },
                    {
                        value: 'broadcasts.create',
                        label: 'Создание рассылок',
                    },
                    {
                        value: 'broadcasts.mail-club',
                        label: 'Отправка рассылок по клубу',
                    },
                    {
                        value: 'broadcasts.mail-all',
                        label: 'Отправка рассылок всем',
                    },
                    {
                        value: 'broadcasts.delete',
                        label: 'Удаление рассылок',
                    },
                    {
                        value: 'invites.create',
                        label: 'Возможность приглашать в клуб',
                    }
                ].filter(permission => {
                    // 1. Источник истины: права текущего админа из PHP переменной $permissions
                    const loggedInAdminPermissions = @json($permissions);
                    const loggedInAdminRole = '{{ $admin->role }}';

                    // 2. Если вошел суперадмин — ему доступны все чекбоксы
                    if (loggedInAdminRole === 'superadmin') {
                        return true;
                    }

                    // 3. Обычный админ видит только те чекбоксы, которые есть у него самого
                    return loggedInAdminPermissions.includes(permission.value);
                }),


                // Filters
                filters: {
                    club: '',
                    role: '',
                    search: ''
                },

                // Modal state
                modal: {
                    show: false,
                    isEdit: false,
                    data: {
                        id: null,
                        name: '',
                        username: '',
                        password: '',
                        selected_club_ids: [],
                        role: 'admin',
                        permissions: [] // Обязательно как массив!
                    }
                },



                // Alert
                alert: {
                    show: false,
                    type: 'success',
                    message: ''
                },

                init() {
                    // Преобразуем club_ids в строки, если нужно
                    this.admins = this.admins.map(admin => ({
                        ...admin,
                        club_ids: admin.clubs ? admin.clubs.map(club => String(club.id)) : [],
                        // Гарантируем, что permissions — массив
                        permissions: admin.permissions || []
                    }));
                    this.filteredAdmins = this.admins;
                },


                filterAdmins() {
                    this.filteredAdmins = this.admins.filter(admin => {
                        const matchesClub = !this.filters.club ||
                            (admin.club_ids && admin.club_ids.includes(parseInt(this.filters.club)));
                        const matchesRole = !this.filters.role || admin.role === this.filters.role;
                        const matchesSearch = !this.filters.search ||
                            admin.name.toLowerCase().includes(this.filters.search.toLowerCase()) ||
                            admin.username.toLowerCase().includes(this.filters.search.toLowerCase());
                        return matchesClub && matchesRole && matchesSearch;
                    });
                },



                // Open create modal
                openCreateModal() {
                    this.modal = {
                        show: true,
                        isEdit: false,
                        data: {
                            id: null,
                            name: '',
                            username: '',
                            password: '',
                            club: '',
                            role: 'admin',
                            permissions: []
                        }
                    };
                },

                // Open edit modal
                openEditModal(admin) {
                    this.modal = {
                        show: true,
                        isEdit: true,
                        data: {
                            id: admin.id,
                            name: admin.name,
                            username: admin.username,
                            password: admin.password,
                            selected_club_ids: (admin.club_ids || []).map(id => String(id)),
                            role: admin.role,
                            permissions: admin.permissions || [], // Гарантируем массив,
                            telegram_id: admin.telegram_id
                        }
                    };
                },



                // Close modal
                closeModal() {
                    this.modal.show = false;
                    this.modal.data = {
                        id: null,
                        name: '',
                        username: '',
                        password: '',
                        club: '',
                        role: 'admin',
                        permissions: [],
                        telegram_id: ''
                    };
                },
                getClubName(clubId) {
                    const club = this.clubs.find(c => c.id == clubId);
                    return club ? club.title : 'Не указан';
                },


                // Save admin (create or update)
                async saveAdmin() {
                    // Validation
                    // if (!this.modal.data.name || !this.modal.data.username ) {
                    //     this.showAlert('error', 'Заполните все обязательные поля');
                    //     return;
                    // }


                    if (!this.modal.isEdit && !this.modal.data.password) {
                        this.showAlert('error', 'Введите пароль');
                        return;
                    }

                    const formData = new FormData();
                    formData.append('name', this.modal.data.name);
                    formData.append('username', this.modal.data.username);
                    formData.append('id', this.modal.data.id);
                    formData.append('telegram_id', this.modal.data.telegram_id);
                    if (this.modal.data.password) {
                        formData.append('password', this.modal.data.password);
                    }
                    // Отправляем массив ID клубов
                    // Проверяем, существует ли переменная, и приводим её к гарантированному массиву
                    const clubIds = Array.isArray(this.modal.data.selected_club_ids) ?
                        this.modal.data.selected_club_ids :
                        (this.modal.data.selected_club_ids ? [this.modal.data.selected_club_ids] : []);

                    // Теперь безопасно перебираем
                    clubIds.forEach(clubId => {
                        // Дополнительно страхуемся от отправки пустых строк/null в массиве
                        if (clubId) {
                            formData.append('club_ids[]', clubId);
                        }
                    });

                    formData.append('role', this.modal.data.role);
                    this.modal.data.permissions.forEach(perm => {
                        formData.append('permissions[]', perm);
                    });

                    let method = 'POST';
                    let url = '{{ route('admin.admin.store') }}';
                    if (this.modal.isEdit) {
                        url = `{{ route('admin.admin.update', '') }}/${this.modal.data.id}`;
                        method = 'POST';
                    }

                    try {
                        const response = await fetch(url, {
                            method: method,
                            body: formData,
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            }
                        });

                        if (!response.ok) {
                            throw new Error('Ошибка сервера');
                        }

                        const result = await response.json();

                        // ЕДИНСТВЕННЫЙ источник истины — данные от сервера
                        this.admins = result.data?.admins || result.admins || [];

                        // Обновляем отфильтрованный список
                        this.filterAdmins();
                        this.closeModal();

                        // Показываем уведомление
                        if (this.modal.isEdit) {
                            this.showAlert('success', 'Администратор успешно обновлен');
                        } else {
                            this.showAlert('success', 'Администратор успешно создан');
                        }
                    } catch (error) {
                        console.error('Ошибка при сохранении администратора:', error);
                        this.showAlert('error', 'Не удалось сохранить администратора');
                    }
                },


                // Delete admin
                async deleteAdmin(adminId) {
                    if (!confirm('Вы уверены, что хотите удалить этого администратора?')) {
                        return;
                    }


                    // API call (uncomment when ready)
                    const response = await fetch('{{ route('admin.admin.delete') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            id: adminId,
                            _method: 'POST'
                        })

                    });

                    if (!response.ok) {
                        throw new Error('Ошибка сервера');
                    }


                    const result = await response.json()

                    this.admins = result.admins;
                    // Mock success

                    this.filterAdmins();
                    this.showAlert('success', 'Администратор успешно удален');


                },

                // Show alert
                showAlert(type, message) {
                    this.alert = {
                        show: true,
                        type: type,
                        message: message
                    };

                    setTimeout(() => {
                        this.alert.show = false;
                    }, 5000);
                }
            };
        }
    </script>
@endsection
