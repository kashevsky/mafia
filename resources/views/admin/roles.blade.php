@extends('admin.layouts.main')
@section('content')
    <style>
        .admin-wrapper {
            display: flex;
            min-height: 100vh;
        }

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

        .main-content {
            flex: 1;
            margin-left: 260px;
            padding: 30px;
        }

        /* Стили для страницы мафии */
        .mafia-container {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            padding: 30px;
        }

        .section-title {
            font-size: 18px;
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #ecf0f1;
        }

        .roles-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 15px;
            margin-bottom: 30px;
        }

        .role-card {
            border: 2px solid #dfe6e9;
            border-radius: 8px;
            padding: 15px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            background: #f8f9fa;
        }

        .role-card:hover {
            border-color: #3498db;
            background: #e3f2fd;
            transform: translateY(-2px);
        }

        .role-card.selected {
            border-color: #3498db;
            background: rgba(52, 152, 219, 0.1);
        }

        .players-list {
            list-style: none;
            margin-bottom: 30px;
        }

        .player-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 15px;
            border: 1px solid #dfe6e9;
            border-radius: 6px;
            margin-bottom: 8px;
            background: #f8f9fa;
            transition: all 0.2s ease;
        }

        .player-item:hover {
            background: #e3f2fd;
        }

        .player-info {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .player-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid transparent;
            transition: all 0.3s ease;
        }

        .player-item .player-role[data-assigned="true"]~.player-avatar {
            border-color: #27ae60;
            box-shadow: 0 0 8px rgba(39, 174, 96, 0.3);
        }

        .player-item .player-role[data-assigned="false"]~.player-avatar {
            border-color: #e74c3c;
        }

        .player-role {
            padding: 6px 12px;
            border-radius: 4px;
            font-size: 13px;
            font-weight: 600;
        }

        .no-role {
            background: #f39c12;
            color: white;
        }

        .action-buttons {
            display: flex;
            gap: 15px;
            margin-top: 20px;
        }

        .btn-primary {
            background-color: #3498db;
            border: none;
            padding: 12px 30px;
            font-weight: 600;
            border-radius: 6px;
            transition: all 0.3s ease;
            cursor: pointer;
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
            cursor: pointer;
        }

        .btn-secondary:hover {
            background-color: #7f8c8d;
        }

        .combo-selector {
            margin-bottom: 20px;
        }

        .combo-option {
            display: inline-block;
            padding: 8px 16px;
            margin-right: 10px;
            border: 1px solid #dfe6e9;
            border-radius: 20px;
            cursor: pointer;
            font-size: 14px;
            transition: all 0.2s ease;
        }

        .combo-option.active {
            background-color: #3498db;
            color: white;
            border-color: #3498db;
        }

        .role-card.assigned {
            background-color: #e8f4f0;
            border-color: #27ae60;
            opacity: 0.7;
            cursor: not-allowed;
        }

        .role-card.assigned:hover {
            transform: none;
            background-color: #e8f4f0;
        }
    </style>
    </head>
    <div x-data="mafiaGame">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/admin/dashboard">Главная</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.game.index') }}">Игры</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.game.index') }}">1</a></li>
                <li class="breadcrumb-item active">Рассадка</li>
            </ol>
        </nav>
        <br>
        <div class="page-header">
            <h1>Назначение ролей — Игра в мафию</h1>
        </div>

        <div class="content-card">
            <div class="mafia-container">
                <div class="section-title">Выберите комбинацию ролей</div>

                <div class="combo-selector">
                    <template x-for="combo in Object.keys(roleCombinations)" :key="combo">
                        <div class="combo-option" :class="{ 'active': currentCombination === combo }"
                            @click="selectCombination(combo)"
                            x-text="combo === 'standard' ? 'Стандартная' :
              combo === 'no-bomb' ? 'Без бомбы' :
              combo === 'balanced' ? 'Сбалансированная' : 'Загадочная'">
                        </div>
                    </template>
                    <br><br>
                    <div class="section-title">Доступные роли</div>

                    <div class="roles-grid" id="rolesGrid">
                        <template x-for="role in roles" :key="role">
                            <div class="role-card"
                                :class="{
                                    'selected': selectedRole === role,
                                    'assigned': isRoleAssigned(role)
                                }"
                                @click="selectRole(role)" x-text="role"></div>
                        </template>
                    </div>


                    <div class="section-title">Список игроков</div>
                    <ul class="players-list" id="playersList">
                        <template x-for="(player, index) in players" :key="index">
                            <li class="player-item" @click="console.log(player)">
                                <div class="player-info">
                                    <img :src="player.avatar ? player.avatar : '/def.svg'" :alt="player.nickname"
                                        class="player-avatar">
                                    <span x-text="player.account.nickname || player.account.name"></span>
                                    <!-- Индикатор назначения роли -->
                                    <span x-show="isPlayerRoleAssigned(index)" style="color: #27ae60; margin-left: 8px;">
                                        ✓
                                    </span>
                                </div>
                                <span class="player-role" :class="isPlayerRoleAssigned(index) ? '' : 'no-role'"
                                    :data-assigned="isPlayerRoleAssigned(index)" @click="assignRoleToPlayer(index)"
                                    x-text="getPlayerRole(index)"></span>
                            </li>
                        </template>
                    </ul>


                    <!-- Контейнер для чекбокса над кнопками -->
                    <div style="margin-bottom: 15px; display: flex; align-items: center; gap: 8px;">
                        <input type="checkbox" id="sendToEmail" x-model="sendToEmail"
                            style="cursor: pointer; width: 16px; height: 16px;">
                        <label for="sendToEmail"
                            style="cursor: pointer; font-size: 14px; user-select: none; color: #333; font-weight: 500;">
                            Отправлять на почту, а не в телеграм
                        </label>
                    </div>

                    <div class="action-buttons">
                        <button class="btn-primary" style="background-color: #f39c12;"
                            @click="
            if (sendToEmail) {
                if (confirm('Вы уверены? Сообщения будут отправляться игрокам на почту')) {
                    window.location.href = '/random-roles/{{ $table->id }}?via=email';
                }
            } else {
                window.location.href = '/random-roles/{{ $table->id }}';
            }
        ">
                            Рандомно назначить роли
                        </button>
                        <button class="btn-secondary" @click="resetRoles">
                            Сбросить назначения
                        </button>
                        <button class="btn-primary" :disabled="isSaving" @click="saveRoles"
                            x-text="isSaving ? 'Сохранение...' : 'Сохранить назначения'">
                        </button>
                        <a href="/play/{{ $table->id }}" class="btn-primary"
                            style="color: white; background-color: green; text-decoration: none">
                            В игру
                        </a>
                    </div>

                    <script>
                        document.addEventListener('alpine:init', () => {
                            Alpine.data('mafiaGame', () => ({
                                roles: ['Шулер', 'Приспешник', 'Шериф', 'Доктор', 'Адвокат', 'Каратель', 'Фредди',
                                    'Детектив', 'Журналист', 'Бомба', 'Стрелочник', 'Оборотень',
                                ],
                                roleCombinations: {
                                    standard: ['Шериф', 'Доктор', 'Бомба', 'Детектив', 'Журналист'],
                                    'no-bomb': ['Шериф', 'Доктор', 'Детектив', 'Журналист', 'Адвокат'],
                                    balanced: ['Шериф', 'Доктор', 'Каратель', 'Детектив', 'Стрелочник'],
                                    mystery: ['Шулер', 'Приспешник', 'Фредди', 'Оборотень', 'Стрелочник']
                                },
                                players: @json($players),
                                currentCombination: 'standard',
                                selectedRole: null,
                                assignedRoles: {},
                                isSaving: false,
                                sendToEmail: false,

                                // Методы
                                selectCombination(combo) {
                                    this.currentCombination = combo;
                                },

                                selectRole(role) {
                                    this.selectedRole = role;
                                },

                                assignRoleToPlayer(playerId) {
                                    if (this.selectedRole) {
                                        this.assignedRoles[playerId] = this.selectedRole;
                                        this.selectedRole = null;
                                    }
                                },

                                isRoleAssigned(role) {
                                    return Object.values(this.assignedRoles).includes(role);
                                },

                                getPlayerRole(playerId) {
                                    return this.assignedRoles[playerId] || 'Не назначена';
                                },

                                isPlayerRoleAssigned(playerId) {
                                    return !!this.assignedRoles[playerId];
                                },

                                randomAssignRoles() {
                                    const combination = this.roleCombinations[this.currentCombination];
                                    // Берём только существующие индексы игроков
                                    const validPlayerIds = Object.keys(this.players)
                                        .map(Number)
                                        .filter(id => this.players[id] !== undefined);

                                    this.assignedRoles = {};

                                    combination.forEach(role => {
                                        if (validPlayerIds.length === 0) {
                                            console.warn('Нет доступных игроков для назначения роли:', role);
                                            return;
                                        }

                                        const randomIndex = Math.floor(Math.random() * validPlayerIds.length);
                                        const playerId = validPlayerIds.splice(randomIndex, 1)[0];
                                        this.assignedRoles[playerId] = role;
                                    });
                                },


                                resetRoles() {
                                    this.assignedRoles = {};
                                    this.selectedRole = null;
                                },

                                async saveRoles() {
                                    this.isSaving = true;

                                    const roleAssignments = [];

                                    Object.entries(this.assignedRoles).forEach(([playerId, role]) => {
                                        const id = parseInt(playerId, 10);

                                        // Проверка существования игрока
                                        if (!this.players[id]) {
                                            console.warn(`Игрок с ID ${id} не найден в массиве players`);
                                            return;
                                        }

                                        const player = this.players[id];

                                        // Используем nickname, если name отсутствует
                                        const playerName = player.name || player.nickname || `Игрок ${id}`;

                                        roleAssignments.push({
                                            player: playerName,
                                            role: role,
                                            avatar: player.avatar || '/def.svg',
                                            id: player.id || id
                                        });
                                    });

                                    console.log('Сохраняемые назначения:', roleAssignments);

                                    try {
                                        const response = await fetch('/admin/mafia/save-roles', {
                                            method: 'POST',
                                            headers: {
                                                'Content-Type': 'application/json',
                                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                            },
                                            body: JSON.stringify(roleAssignments)
                                        });

                                        if (response.ok) {
                                            alert('Роли успешно сохранены!');
                                        } else {
                                            throw new Error('Ошибка сервера');
                                        }
                                    } catch (error) {
                                        console.error('Ошибка сохранения:', error);
                                        alert('Произошла ошибка при сохранении ролей');
                                    } finally {
                                        this.isSaving = false;
                                    }
                                }

                            }));
                        });
                    </script>
                @endsection
