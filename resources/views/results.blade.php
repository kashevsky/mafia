@extends('layouts.club')

@section('content')
    <div class="container" x-data="resultsPage()">
        <style>
            .logo {
                font-size: 24px;
                font-weight: bold;
                display: flex;
                align-items: center;
                gap: 8px;
            }

            .header-actions {
                display: flex;
                gap: 16px;
                align-items: center;
            }

            .icon-btn {
                background: none;
                border: none;
                cursor: pointer;
                color: white;
                padding: 8px;
                display: flex;
                align-items: center;
            }

            .icon-btn:hover {
                opacity: 0.8;
            }

            /* Page Header */
            .results-header {
                padding: 32px 16px;
                text-align: center;
                background-image: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
                border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            }

            .results-title {
                font-size: 36px;
                font-weight: 700;
                color: #f9fafb;
                margin-bottom: 8px;
                line-height: 1.2;
                text-shadow: 0 2px 4px rgba(0, 0, 0, 0.6);
            }

            .results-subtitle {
                color: #9ca3af;
                font-size: 15px;
            }

            /* Leaderboard Grid */
            .leaderboard-grid {
                padding: 24px 16px;
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
                gap: 20px;
            }

            .player-card {
                background: rgba(31, 41, 55, 0.7);
                backdrop-filter: blur(8px);
                border: 1px solid rgba(255, 255, 255, 0.05);
                border-radius: 16px;
                padding: 20px;
                position: relative;
                overflow: hidden;
                transition: transform 0.3s ease, box-shadow 0.3s ease;
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: start;
            }

            .player-card:hover {
                transform: translateY(-4px);
                box-shadow: 0 10px 20px rgba(0, 0, 0, 0.3);
            }

            /* Player Rank Badge — медали */
            .rank-badge {
                position: absolute;
                top: 12px;
                right: 12px;
                width: 40px;
                height: 40px;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                font-weight: 700;
                font-size: 18px;
                color: white;
                z-index: 3;
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.4);
                border: 2px solid rgba(255, 255, 255, 0.15);
            }

            .rank-1 {
                /* Золото */
                background: linear-gradient(145deg, #fbbf24 0%, #d97706 100%);
                box-shadow: 0 0 18px rgba(251, 191, 36, 0.5);
            }

            .rank-2 {
                /* Серебро */
                background: linear-gradient(145deg, #9ca3af 0%, #6b7280 100%);
                box-shadow: 0 0 18px rgba(156, 163, 175, 0.5);
            }

            .rank-3 {
                /* Бронза */
                background: linear-gradient(145deg, #d97706 0%, #78350f 100%);
                box-shadow: 0 0 18px rgba(217, 119, 6, 0.5);
            }

            .player-avatar {
                width: 80px;
                height: 80px;
                border-radius: 50%;
                object-fit: cover;
                border: 3px solid rgba(255, 255, 255, 0.2);
                margin: 0 auto 16px auto;
                box-shadow: 0 6px 16px rgba(0, 0, 0, 0.4);
                /* Центрирование уже обеспечено через flex у родителя */
            }

            .player-name {
                font-size: 18px;
                font-weight: 600;
                color: #f9fafb;
                text-align: center;
                margin-bottom: 4px;
                max-width: 100%;
                word-break: break-word;
            }

            .player-total {
                font-size: 22px;
                font-weight: 700;
                color: #fbbf24;
                text-align: center;
                letter-spacing: 1px;
                margin-top: 4px;
            }

            .player-label {
                text-align: center;
                color: #6b7280;
                font-size: 13px;
                margin-top: 4px;
            }

            /* Empty State */
            .empty-state {
                padding: 48px 16px;
                text-align: center;
                color: #9ca3af;
            }

            .empty-icon {
                width: 64px;
                height: 64px;
                fill: #4b5563;
                margin: 0 auto 16px auto;
                opacity: 0.6;
            }

            /* Menu Sidebar */
            .menu-overlay {
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: rgba(0, 0, 0, 0.5);
                z-index: 998;
            }

            .menu-sidebar {
                position: fixed;
                top: 0;
                right: -100%;
                width: 280px;
                height: 100vh;
                background: #0f172a;
                z-index: 999;
                transition: right 0.3s ease;
                padding: 20px;
                box-shadow: -2px 0 10px rgba(0, 0, 0, 0.3);
            }

            .menu-sidebar.open {
                right: 0;
            }

            .menu-header {
                display: flex;
                justify-content: space-between;
                align-items: center;
                margin-bottom: 20px;
                padding-bottom: 12px;
                border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            }

            .menu-title {
                font-size: 18px;
                font-weight: 600;
                color: #f9fafb;
            }

            .menu-close {
                background: none;
                border: none;
                color: #9ca3af;
                cursor: pointer;
                padding: 8px;
            }

            .menu-close:hover {
                color: white;
            }

            .menu-items {
                display: flex;
                flex-direction: column;
                gap: 16px;
            }

            .menu-item {
                display: flex;
                align-items: center;
                gap: 12px;
                padding: 12px 16px;
                background: rgba(31, 41, 55, 0.5);
                border-radius: 8px;
                color: #e2e8f0;
                text-decoration: none;
                transition: all 0.2s;
            }

            .menu-item:hover {
                background: rgba(244, 63, 94, 0.2);
                color: white;
                transform: translateX(4px);
            }

            .menu-item svg {
                width: 20px;
                height: 20px;
                color: #f43f5e;
            }

            @media (max-width: 480px) {
                .container {
                    max-width: 100%;
                    padding: 0;
                }

                .results-title {
                    font-size: 28px;
                }
            }
        </style>

        @include('include.club.header')

        <!-- Header -->
        <div class="results-header">
            <h1 class="results-title">Результаты игры</h1>
            <p class="results-subtitle">Топ‑3 игрока по очкам</p>
        </div>

        <!-- Leaderboard -->
        @if (count($topPlayers) > 0)
            <div class="leaderboard-grid">
                @foreach ($topPlayers as $index => $player)
                    @php
                        $rankClass = match ($index) {
                            0 => 'rank-1',
                            1 => 'rank-2',
                            2 => 'rank-3',
                            default => '',
                        };

                        // Путь к дефолтной картинке — замени на свой
                        $defaultAvatar = asset('img/default-avatar.png');
                        $avatarSrc = $player->avatar ? $player->avatar : $defaultAvatar;
                    @endphp

                    <div class="player-card">
                        <div class="rank-badge {{ $rankClass }}">
                            {{ $index + 1 }}
                        </div>

                        <img src="{{ $avatarSrc }}" alt="{{ $player->name ?? 'Игрок' }}" class="player-avatar"
                            onerror="this.src='/def.svg'; this.onerror=null;">

                        <div class="player-name">
                            {{ !empty($player->nickname) ? $player->nickname . (!empty($player->name) ? ' (' . $player->name . ')' : '') : $player->name }}
                        </div>

                        <div class="player-total">{{ number_format($player->total, 0, '.', ' ') }}</div>
                        <div class="player-label">Очки</div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="empty-state">
                <svg class="empty-icon" viewBox="0 0 24 24">
                    <path
                        d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13C19 5.13 15.87 2 12 2zm0 11c-2.21 0-4-1.79-4-4s1.79-4 4-4 4 1.79 4 4-1.79 4-4 4z" />
                </svg>
                <h3 class="results-title" style="margin-top:0">Пока нет результатов</h3>
                <p>Игра ещё не завершена или данные не загружены.</p>
            </div>
        @endif

        <!-- Menu Sidebar -->
        <template x-if="menuOpen">
            <div>
                <div class="menu-overlay" @click="menuOpen = false"></div>
                <div class="menu-sidebar" :class="{ 'open': menuOpen }">
                    <div class="menu-header">
                        <div class="menu-title">Меню</div>
                        <button class="menu-close" @click="menuOpen = false">
                            <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    <div class="menu-items">
                        <a href="#" class="menu-item">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            <span>Мой профиль</span>
                        </a>
                        <a href="" class="menu-item">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 00-2 2v0m2-2a2 2 0 002 2v0m8-2v12m0 0H9" />
                            </svg>
                            <span>Афиша событий</span>
                        </a>
                        <a href="#" class="menu-item">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 20h5v-20h-5M7 20h-5v-20h5M12 20v-20" />
                            </svg>
                            <span>Правила игры</span>
                        </a>
                        <a href="#" class="menu-item">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                            </svg>
                            <span>Отзывы</span>
                        </a>
                    </div>
                </div>
            </div>
        </template>

        <script>
            function resultsPage() {
                return {
                    menuOpen: false,
                    toggleMenu() {
                        this.menuOpen = !this.menuOpen;
                    }
                };
            }
        </script>

        @include('include.club.bottom-nav')
    </div>
@endsection
