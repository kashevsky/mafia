<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Управление администраторами</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

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
        }

        .modal.active {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .modal-content {
            background-color: white;
            margin: 20px auto;
            padding: 0;
            border-radius: 8px;
            width: 90%;
            max-width: 600px;
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
</head>

<body>
    <div class="admin-wrapper" x-data="adminManagement()">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <h3><i class="fas fa-store"></i> Админ панель</h3>
                <p>Панель управления</p>
            </div>
            <ul class="sidebar-menu">
                <li>
                    <a href="/admin">
                        <i class="fas fa-home"></i>
                        <span>Главная</span>
                    </a>
                </li>
                <li>
                    <a href="/admin/clubs">
                        <i class="fas fa-shield-alt"></i>
                        <span>Клубы</span>
                    </a>
                </li>
                <li>
                    <a href="/admin/accounts">
                        <i class="fas fa-user-friends"></i>
                        <span>Игроки</span>
                    </a>
                </li>
                <li>
                    <a href="/admin/games">
                        <i class="fas fa-gamepad"></i>
                        <span>Игры</span>
                    </a>
                </li>
                <li>
                    <a href="/admin/merch">
                        <i class="fas fa-shopping-bag"></i>
                        <span>Мерч</span>
                    </a>
                </li>
                <li>
                    <a href="/admin/probabilities">
                        <i class="fas fa-percent"></i>
                        <span>Вероятности</span>
                    </a>
                </li>
                <li>
                    <a href="/admin/payments">
                        <i class="fas fa-credit-card"></i>
                        <span>Оплаты</span>
                    </a>
                </li>
                <li>
                    <a href="/admin/reports">
                        <i class="fas fa-chart-bar"></i>
                        <span>Отчеты</span>
                    </a>
                </li>
                <li>
                    <a href="/admin/administrators" class="active">
                        <i class="fas fa-users"></i>
                        <span>Администраторы</span>
                    </a>
                </li>
                <li>
                    <a href="/admin/settings">
                        <i class="fas fa-cog"></i>
                        <span>Настройки</span>
                    </a>
                </li>
                <li>
                    <a href="/admin/logout">
                        <i class="fas fa-sign-out-alt"></i>
                        <span>Выход</span>
                    </a>
                </li>
            </ul>
        </aside>

        <main class="main-content">
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
                <div class="section-title">
                    <span>Список администраторов</span>
                    <button class="btn-primary" @click="openCreateModal()">
                        <i class="fas fa-plus"></i> Добавить администратора
                    </button>
                </div>

                <!-- Filters -->
                <div class="filters">
                    <div class="filter-group">
                        <label class="form-label">Клуб</label>
                        <select class="form-select" x-model="filters.club" @change="filterAdmins()">
                            <option value="">Все клубы</option>
                            <template x-for="club in clubs" :key="club">
                                <option :value="club" x-text="club"></option>
                            </template>
                        </select>
                    </div>

                    <div class="filter-group">
                        <label class="form-label">Роль</label>
                        <select class="form-select" x-model="filters.role" @change="filterAdmins()">
                            <option value="">Все роли</option>
                            <option value="superadmin">Супер админ</option>
                            <option value="admin">Администратор</option>
                            <option value="hoster">Ведущий</option>
                        </select>
                    </div>

                    <div class="filter-group search-box">
                        <label class="form-label">Поиск</label>
                        <input type="text" placeholder="Поиск по имени..." x-model="filters.search"
                            @input="filterAdmins()">
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
                                    <td class="table-cell" x-text="admin.club"></td>
                                    <td class="table-cell">
                                        <span class="admin-role"
                                            :class="admin.role === 'superadmin' ? 'role-superadmin' : 'role-admin'"
                                            x-text="admin.role === 'superadmin' ? 'Супер админ' : 'Администратор'"></span>
                                    </td>
                                    <td class="table-cell" x-text="admin.created_at"></td>
                                    <td class="table-cell">
                                        <div class="action-buttons">
                                            <button class="btn-action btn-edit" @click="openEditModal(admin)">
                                                <i class="fas fa-edit"></i> Изменить
                                            </button>
                                            <button class="btn-action btn-delete" @click="deleteAdmin(admin.id)">
                                                <i class="fas fa-trash"></i> Удалить
                                            </button>
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
                                <input type="text" class="form-control" x-model="modal.data.name"
                                    placeholder="Введите имя" required>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Логин</label>
                                <input type="text" class="form-control" x-model="modal.data.username"
                                    placeholder="Введите логин" required>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Пароль</label>
                                <input type="password" class="form-control" x-model="modal.data.password"
                                    :placeholder="modal.isEdit ? 'Оставьте пустым, чтобы не менять' : 'Введите пароль'"
                                    :required="!modal.isEdit">
                            </div>

                            <div class="form-group">
                                <label class="form-label">Клуб</label>
                                <select class="form-select" x-model="modal.data.club" required>
                                    <option value="">Выберите клуб</option>
                                    <template x-for="club in clubs" :key="club">
                                        <option :value="club" x-text="club"></option>
                                    </template>
                                </select>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Роль</label>
                                <select class="form-select" x-model="modal.data.role" required>
                                    <option value="admin">Администратор</option>
                                    <option value="superadmin">Супер админ</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Права доступа к вкладкам</label>
                                <div class="permissions-grid">
                                    <template x-for="permission in availablePermissions" :key="permission.value">
                                        <div class="permission-item">
                                            <input type="checkbox" :id="'perm-' + permission.value"
                                                :value="permission.value" x-model="modal.data.permissions">
                                            <label :for="'perm-' + permission.value"
                                                x-text="permission.label"></label>
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
        </main>
    </div>

    <script>
        function adminManagement() {
            return {
                // Admins data
                admins: [{
                        id: 1,
                        name: 'Иван Иванов',
                        username: 'admin',
                        club: 'Фитнес Центр "Энергия"',
                        role: 'superadmin',
                        created_at: '2024-01-15 10:30',
                        permissions: ['home', 'clubs', 'players', 'games', 'merch', 'probabilities', 'payments',
                            'reports', 'administrators', 'settings'
                        ]
                    },
                    {
                        id: 2,
                        name: 'Мария Петрова',
                        username: 'maria',
                        club: 'Спортклуб "Олимп"',
                        role: 'admin',
                        created_at: '2024-02-10 14:20',
                        permissions: ['home', 'players', 'games', 'payments', 'reports']
                    },
                    {
                        id: 3,
                        name: 'Алексей Сидоров',
                        username: 'alexey',
                        club: 'Клуб "Атлант"',
                        role: 'admin',
                        created_at: '2024-03-05 09:15',
                        permissions: ['home', 'players', 'games']
                    },
                ],
                filteredAdmins: [],

                // Clubs list
                clubs: [
                    'Фитнес Центр "Энергия"',
                    'Спортклуб "Олимп"',
                    'Клуб "Атлант"',
                    'Фитнес Центр "Титан"',
                    'Спортклуб "Вега"'
                ],

                // Available permissions
                availablePermissions: [{
                        value: 'home',
                        label: 'Главная'
                    },
                    {
                        value: 'clubs',
                        label: 'Клубы'
                    },
                    {
                        value: 'players',
                        label: 'Игроки'
                    },
                    {
                        value: 'games',
                        label: 'Игры'
                    },
                    {
                        value: 'merch',
                        label: 'Мерч'
                    },
                    {
                        value: 'probabilities',
                        label: 'Вероятности'
                    },
                    {
                        value: 'payments',
                        label: 'Оплаты'
                    },
                    {
                        value: 'reports',
                        label: 'Отчеты'
                    },
                    {
                        value: 'administrators',
                        label: 'Администраторы'
                    },
                    {
                        value: 'settings',
                        label: 'Настройки'
                    }
                ],

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
                        club: '',
                        role: 'admin',
                        permissions: []
                    }
                },

                // Alert
                alert: {
                    show: false,
                    type: 'success',
                    message: ''
                },

                // Initialization
                init() {
                    this.filteredAdmins = this.admins;
                },

                // Filter admins
                filterAdmins() {
                    this.filteredAdmins = this.admins.filter(admin => {
                        const matchesClub = !this.filters.club || admin.club === this.filters.club;
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
                            password: '',
                            club: admin.club,
                            role: admin.role,
                            permissions: [...admin.permissions]
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
                        permissions: []
                    };
                },

                // Save admin (create or update)
                async saveAdmin() {
                    // Validation
                    if (!this.modal.data.name || !this.modal.data.username || !this.modal.data.club) {
                        this.showAlert('error', 'Заполните все обязательные поля');
                        return;
                    }

                    if (!this.modal.isEdit && !this.modal.data.password) {
                        this.showAlert('error', 'Введите пароль');
                        return;
                    }

                    try {
                        const formData = new FormData();
                        formData.append('name', this.modal.data.name);
                        formData.append('username', this.modal.data.username);
                        if (this.modal.data.password) {
                            formData.append('password', this.modal.data.password);
                        }
                        formData.append('club', this.modal.data.club);
                        formData.append('role', this.modal.data.role);
                        this.modal.data.permissions.forEach(perm => {
                            formData.append('permissions[]', perm);
                        });

                        let url = '/admin/administrators';
                        let method = 'POST';

                        if (this.modal.isEdit) {
                            url = `/admin/administrators/${this.modal.data.id}`;
                            method = 'PUT';
                            formData.append('_method', 'PUT');
                        }

                        // API call (uncomment when ready)
                        // const response = await fetch(url, {
                        //     method: method,
                        //     body: formData,
                        //     headers: {
                        //         'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                        //     }
                        // });
                        //
                        // if (!response.ok) {
                        //     throw new Error('Ошибка сервера');
                        // }
                        //
                        // const result = await response.json();

                        // Mock success
                        if (this.modal.isEdit) {
                            const index = this.admins.findIndex(a => a.id === this.modal.data.id);
                            if (index !== -1) {
                                this.admins[index] = {
                                    ...this.admins[index],
                                    name: this.modal.data.name,
                                    username: this.modal.data.username,
                                    club: this.modal.data.club,
                                    role: this.modal.data.role,
                                    permissions: [...this.modal.data.permissions]
                                };
                            }
                            this.showAlert('success', 'Администратор успешно обновлен');
                        } else {
                            const newAdmin = {
                                id: this.admins.length + 1,
                                name: this.modal.data.name,
                                username: this.modal.data.username,
                                club: this.modal.data.club,
                                role: this.modal.data.role,
                                created_at: new Date().toISOString().slice(0, 16).replace('T', ' '),
                                permissions: [...this.modal.data.permissions]
                            };
                            this.admins.push(newAdmin);
                            this.showAlert('success', 'Администратор успешно создан');
                        }

                        this.filterAdmins();
                        this.closeModal();

                    } catch (error) {
                        this.showAlert('error', 'Ошибка при сохранении: ' + error.message);
                    }
                },

                // Delete admin
                async deleteAdmin(adminId) {
                    if (!confirm('Вы уверены, что хотите удалить этого администратора?')) {
                        return;
                    }

                    try {
                        // API call (uncomment when ready)
                        // const response = await fetch(`/admin/administrators/${adminId}`, {
                        //     method: 'DELETE',
                        //     headers: {
                        //         'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                        //         'Content-Type': 'application/json'
                        //     }
                        // });
                        //
                        // if (!response.ok) {
                        //     throw new Error('Ошибка сервера');
                        // }

                        // Mock success
                        this.admins = this.admins.filter(a => a.id !== adminId);
                        this.filterAdmins();
                        this.showAlert('success', 'Администратор успешно удален');

                    } catch (error) {
                        this.showAlert('error', 'Ошибка при удалении: ' + error.message);
                    }
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
</body>

</html>
