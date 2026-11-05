@extends('layouts.club')
@section('content')
    <style>
        .page-header {
            text-align: center;
            margin-bottom: 32px;
        }

        .page-title {
            font-size: 28px;
            font-weight: bold;
            color: #f8fafc;
            margin-bottom: 8px;
        }

        .page-subtitle {
            color: #9ca3af;
            font-size: 16px;
        }

        .filters-container {
            margin-bottom: 24px;
        }

        .filters-toggle-btn {
            display: flex;
            align-items: center;
            gap: 8px;
            background: #3b82f6;
            color: white;
            border: none;
            padding: 12px 16px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 500;
            transition: background-color 0.2s;
            width: 100%;
            justify-content: center;
        }

        .filters-toggle-btn:hover {
            background: #2563eb;
        }

        .toggle-arrow {
            transition: transform 0.3s ease;
            width: 16px;
            height: 16px;
        }

        .toggle-arrow.rotated {
            transform: rotate(180deg);
        }

        .filters-panel {
            display: flex;
            flex-wrap: wrap;
            gap: 16px;
            margin-top: 16px;
            padding: 16px;
            background: #1e293b;
            border-radius: 8px;
            border: 1px solid #334155;
        }

        .dropdown-filter {
            display: flex;
            align-items: center;
            gap: 8px;
            flex: 1;
            min-width: 0;
            /* Предотвращает переполнение */
        }

        .filter-label {
            color: #9ca3af;
            font-size: 14px;
            white-space: nowrap;
        }

        .filter-select {
            padding: 8px 12px;
            background: #1e293b;
            border: 1px solid #334155;
            border-radius: 8px;
            color: #f8fafc;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.2s;
            flex: 1;
            min-width: 0;
        }

        .filter-select:hover {
            border-color: #3b82f6;
        }

        .filter-select:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.2);
        }

        /* Стили для стрелки выпадающего списка */
        .filter-select::-ms-expand {
            display: none;
        }

        .filter-select {
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
            background-image: url("data:image/svg+xml;charset=UTF-8,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20' fill='%239ca3af'><path fill-rule='evenodd' d='M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z' clip-rule='evenodd'/></svg>");
            background-repeat: no-repeat;
            background-position: right 12px center;
            background-size: 12px;
            padding-right: 32px;
        }

        .members-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
            margin-bottom: 32px;
            padding: 0 16px;
            /* Отступы по бокам на мобильных */
            box-sizing: border-box;
        }

        /* Для очень узких экранов (например, в портретной ориентации смартфона) */
        @media (max-width: 360px) {
            .members-grid {
                padding: 0 8px;
                /* Уменьшаем отступы */
                gap: 16px;
                /* Уменьшаем расстояние между карточками */
            }
        }


        .member-card {
            background: #1e293b;
            border-radius: 12px;
            padding: 16px;
            /* Уменьшили отступы для мобильных */
            border: 1px solid #334155;
            transition: all 0.3s ease;
            min-width: 0;

            /* Предотвращает переполнение из‑за длинных имён */
        }

        @media (min-width: 768px) {
            .member-card {
                padding: 20px;
                /* Возвращаем отступы на планшетах и десктопах */
            }
        }


        .member-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 12px -3px rgba(0, 0, 0, 0.1);
        }

        /* Основная информация — всегда видна */
        .member-main {
            display: flex;
            align-items: center;
            gap: 16px;

            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            margin-bottom: 16px;
        }

        .member-avatar {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            overflow: hidden;
            border: 3px solid #3b82f6;
        }

        .member-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .member-placeholder {
            width: 100%;
            height: 100%;
            background: #334155;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            color: #9ca3af;
            font-size: 32px;
        }

        .member-info {
            flex: 1;
            text-align: left;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        .member-name {
            font-size: 18px;
            font-weight: 600;
            color: #f8fafc;
            margin-bottom: 4px;
            text-align: center;
        }

        .member-city {
            color: #9ca3af;
            font-size: 14px;
            margin-bottom: 4px;
        }

        .member-club {
            color: #64748b;
            font-size: 12px;
            padding: 4px 8px;
            background: rgba(59, 130, 246, 0.1);
            border-radius: 4px;
            display: inline-block;
        }

        .rating-badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 4px 8px;
            border-radius: 20px;
            font-size: 12px;
            margin-top: 8px;
        }

        .high-rating {
            background: rgba(16, 180, 128, 0.2);
            color: #10b981;
        }

        .medium-rating {
            background: rgba(234, 179, 8, 0.2);
            color: #ea580c;
        }

        .low-rating {
            background: rgba(244, 63, 94, 0.2);
            color: #f43f5e;
        }

        /* Кнопка раскрытия деталей */
        .toggle-details-btn {
            width: 36px;
            height: 36px;
            border: none;
            background: #334155;
            border-radius: 50%;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s;
            flex-shrink: 0;
        }

        .toggle-details-btn:hover {
            background: #475569;
        }

        .toggle-details-btn.expanded {
            transform: rotate(180deg);
        }

        /* Дополнительная информация — раскрывается по клику */
        .member-details {
            border-top: 1px dashed #334155;
            padding-top: 16px;
            margin-top: 16px;
        }

        .stat-item {
            display: flex;
            justify-content: space-between;
            font-size: 14px;
            color: #cbd5e1;
            margin-bottom: 8px;
        }

        .stat-label {
            color: #9ca3af;
        }

        .stat-value {
            font-weight: 500;
        }




        .rating-badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 4px 8px;
            border-radius: 20px;
            font-size: 12px;
            margin-top: 8px;
        }

        .high-rating {
            background: rgba(16, 180, 128, 0.2);
            color: #10b981;
        }

        .medium-rating {
            background: rgba(234, 179, 8, 0.2);
            color: #ea580c;
        }

        .low-rating {
            background: rgba(244, 63, 94, 0.2);
            color: #f43f5e;
        }

        /* Состояние загрузки */
        .loading {
            text-align: center;
            padding: 60px;
            color: #9ca3af;
        }

        .loading-spinner {
            width: 40px;
            height: 40px;
            border: 3px solid rgba(255, 255, 255, 0.1);
            border-top-color: #3b82f6;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin: 0 auto 16px;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        /* Сообщение об отсутствии результатов */
        .no-results {
            text-align: center;
            padding: 60px;
            color: #9ca3af;
        }

        .no-results svg {
            width: 80px;
            height: 80px;
            color: #64748b;
            margin-bottom: 16px;
        }
    </style>

    @include('include.club.header')

    <div class="content" x-data="allMembers()">

        <div class="page-header">
            <h1 class="page-title">Все участники клуба</h1>
            <p class="page-subtitle">Более 500 активных игроков со всей страны</p>
        </div>

        <div class="filters-container" x-data="{ isFiltersOpen: false }">
            <!-- Кнопка открытия меню -->
            <button class="filters-toggle-btn" @click="isFiltersOpen = !isFiltersOpen">
                <span x-text="isFiltersOpen ? 'Скрыть фильтры' : 'Показать фильтры'"></span>
                <svg class="toggle-arrow" :class="{ 'rotated': isFiltersOpen }" width="16" height="16"
                    viewBox="0 0 24 24" fill="none">
                    <path stroke="currentColor" stroke-width="2" d="M6 9l6 6 6-6" />
                </svg>
            </button>

            <!-- Панель фильтров (скрывается/показывается) -->
            <div class="filters-panel" x-show="isFiltersOpen" x-cloak>
                <!-- Фильтр по городам -->
                <div class="dropdown-filter">
                    <label class="filter-label">Город:</label>
                    <select class="filter-select" x-model="filterBy" @change="$dispatch('filter-changed')">
                        <option value="all">Все города</option>
                        <option value="moscow">Москва</option>
                        <option value="spb">Санкт‑Петербург</option>
                        <option value="other">Другие города</option>
                    </select>
                </div>

                <!-- Фильтр по клубам -->
                <div class="dropdown-filter">
                    <label class="filter-label">Клуб:</label>
                    <select class="filter-select" x-model="filterBy" @change="$dispatch('filter-changed')">
                        <option value="all-clubs">Все клубы</option>
                        <option value="club1">Клуб «Стратегия»</option>
                        <option value="club2">Клуб «Тактика»</option>
                    </select>
                </div>

                <!-- Сортировка -->
                <div class="dropdown-filter">
                    <label class="filter-label">Сортировка:</label>
                    <select class="filter-select" x-model="sortBy" @change="$dispatch('sort-changed')">
                        <option value="rating">По рейтингу</option>
                        <option value="games">По играм</option>
                        <option value="wins">По победам</option>
                        <option value="date">По дате регистрации</option>
                    </select>
                </div>
            </div>
        </div>



        <!-- Состояние загрузки -->
        <div class="loading" x-show="loading">
            <div class="loading-spinner"></div>
            <p>Загрузка участников...</p>
        </div>

        <!-- Сообщение об отсутствии результатов -->
        <div class="no-results" x-show="!loading && filteredMembers.length === 0">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9.172 16.172a4 4 0 015.656 0M9.172 7.828a4 4 0 005.656 0m0 9.172V7.828m0 0a5 5 0 110-10 5 5 0 010 10z">
                </path>
            </svg>
            <h3>Участники не найдены</h3>
            <p>Попробуйте изменить параметры фильтрации или сортировки</p>
        </div>

        <!-- Сетка участников -->
        <div class="members-grid" x-data="{
            expandedMember: null,
            toggleMemberDetails(id) {
                this.expandedMember = this.expandedMember === id ? null : id;
            }
        }">
            <template x-for="member in members" :key="member.id">
                <div class="member-card" x-on:click="window.location.href = '/account/show/' + member.id">
                    <!-- Основная информация (всегда видна) -->
                    <div class="member-main">
                        <div class="member-avatar">
                            <template x-if="member.avatar">
                                <img :src="member.avatar" :alt="member.name">
                            </template>
                            <template x-if="!member.avatar">
                                <div class="member-placeholder" x-text="member.name.charAt(0)"></div>
                            </template>
                        </div>
                        <div class="member-info">
                            <div class="member-name" x-text="member.name"></div>
                            <div class="member-city" x-text="member.city"></div>

                            <div class="rating-badge"
                                :class="member.rating >= 80 ? 'high-rating' :
                                    member.rating >= 60 ? 'medium-rating' : 'low-rating'"
                                x-text="'Рейтинг: ' + member.rating + '★'">
                            </div>
                        </div>

                        <!-- Кнопка раскрытия дополнительной информации -->
                        <button class="toggle-details-btn" @click="toggleMemberDetails(member.id)"
                            :class="{ 'expanded': expandedMember === member.id }">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none">
                                <path stroke="currentColor" stroke-width="2" d="M6 9l6 6 6-6" />
                            </svg>
                        </button>
                    </div>

                    <!-- Дополнительная информация (раскрывается по клику) -->
                    <div class="member-details" x-show="expandedMember === member.id" x-cloak>
                        <div class="stat-item">
                            <span class="stat-label">Игр:</span>
                            <span class="stat-value" x-text="member.games"></span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-label">Побед:</span>
                            <span class="stat-value" x-text="member.wins"></span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-label">Винрейт:</span>
                            <span class="stat-value" x-text="member.winRate + '%'"></span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-label">Регистрация:</span>
                            <span class="stat-value" x-text="member.registrationDate"></span>
                        </div>
                    </div>
                </div>
            </template>
        </div>

    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('allMembers', () => ({
                members: [],
                filterBy: 'all', // 'all', 'moscow', 'spb', 'other', 'club1', 'club2'
                sortBy: 'rating', // 'rating', 'games', 'wins', 'date'
                loading: true,

                init() {
                    this.loadMembers();
                },

                async loadMembers() {
                    // Тестовые данные участников
                    this.members = [{
                            id: 1,
                            name: 'Дмитрий Иванов',
                            avatar: '../../uploads/avatars/dima.jpg',
                            city: 'Москва',
                            club: 'Клуб «Стратегия»',
                            games: 42,
                            wins: 28,
                            winRate: 66.7,
                            rating: 85,
                            registrationDate: '15.03.2023'
                        },
                        {
                            id: 2,
                            name: 'Анна Петрова',
                            avatar: null,
                            city: 'Санкт‑Петербург',
                            club: 'Клуб «Тактика»',
                            games: 19,
                            wins: 12,
                            winRate: 63.2,
                            rating: 72,
                            registrationDate: '22.05.2023'
                        },
                        {
                            id: 3,
                            name: 'Максим Сидоров',
                            avatar: '../../uploads/avatars/max.jpg',
                            city: 'Екатеринбург',
                            club: 'Клуб «Стратегия»',
                            games: 67,
                            wins: 45,
                            winRate: 67.2,
                            rating: 91,
                            registrationDate: '08.01.2022'
                        },
                        {
                            id: 4,
                            name: 'Елена Козлова',
                            avatar: null,
                            city: 'Новосибирск',
                            club: 'Клуб «Тактика»',
                            games: 33,
                            wins: 21,
                            winRate: 63.6,
                            rating: 68,
                            registrationDate: '12.07.2023'
                        }
                    ];
                    this.loading = false;
                },

                get filteredMembers() {
                    let filtered = this.members;

                    // Фильтрация по городам
                    if (this.filterBy === 'moscow') {
                        filtered = filtered.filter(member => member.city === 'Москва');
                    } else if (this.filterBy === 'spb') {
                        filtered = filtered.filter(member => member.city === 'Санкт‑Петербург');
                    } else if (this.filterBy === 'other') {
                        filtered = filtered.filter(member =>
                            member.city !== 'Москва' && member.city !== 'Санкт‑Петербург'
                        );
                    }

                    // Фильтрация по клубам
                    if (this.filterBy === 'club1') {
                        filtered = filtered.filter(member => member.club === 'Клуб «Стратегия»');
                    } else if (this.filterBy === 'club2') {
                        filtered = filtered.filter(member => member.club === 'Клуб «Тактика»');
                    }
                    // Сортировка
                    switch (this.sortBy) {
                        case 'games':
                            filtered.sort((a, b) => b.games - a.games);
                            break;
                        case 'wins':
                            filtered.sort((a, b) => b.wins - a.wins);
                            break;
                        case 'date':
                            filtered.sort((a, b) =>
                                new Date(b.registrationDate.split('.').reverse().join('-')) -
                                new Date(a.registrationDate.split('.').reverse().join('-'))
                            );
                            break;
                        case 'rating':
                            filtered.sort((a, b) => b.rating - a.rating);
                            break;
                        default:
                            filtered.sort((a, b) => a.name.localeCompare(b.name));
                    }

                    return filtered;
                },

                // Вспомогательные методы для удобства
                getRatingClass(rating) {
                    if (rating >= 80) return 'high-rating';
                    if (rating >= 60) return 'medium-rating';
                    return 'low-rating';
                }
            }));
        });
    </script>
@endsection
