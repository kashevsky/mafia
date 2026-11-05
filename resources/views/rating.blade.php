@extends('layouts.club')
@section('content')
@include('include.club.header')

    <style>


        .container {
            max-width: 448px;
            margin: 0 auto;
            background: #1e293b;
            min-height: 100vh;
        }



        .back-btn {
            background: none;
            border: none;
            color: white;
            cursor: pointer;
            padding: 8px;
            display: flex;
            align-items: center;
        }

        .back-btn:hover {
            opacity: 0.8;
        }

        .header-title {
            font-size: 18px;
            font-weight: 600;
            flex: 1;
        }

        .filter-btn {
            background: rgba(255, 255, 255, 0.2);
            border: none;
            color: white;
            cursor: pointer;
            padding: 8px 12px;
            border-radius: 8px;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .filter-btn:hover {
            background: rgba(255, 255, 255, 0.3);
        }

        /* Tabs */
        .tabs-container {
            background: #1e293b;
            border-bottom: 2px solid #334155;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        .tabs-container::-webkit-scrollbar {
            display: none;
        }

        .tabs {
            display: flex;
            min-width: max-content;
        }

        .tab-button {
            padding: 14px 16px;
            border: none;
            background: none;
            cursor: pointer;
            color: #94a3b8;
            font-size: 13px;
            font-weight: 500;
            border-bottom: 3px solid transparent;
            transition: all 0.2s;
            white-space: nowrap;
        }

        .tab-button.active {

          background-color: #334155;
            opacity: 1;

        }

        .tab-button:hover {
            background-color: #334155;
        }

        /* Content */
        .content {
            padding: 16px;
        }

        /* Top Players Banner */
        .top-banner {
            background: linear-gradient(135deg, #1a1b26 0%, #2d3748 100%);
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 20px;
            text-align: center;
            border: 1px solid #475569;
        }

        .top-banner-title {
            font-size: 16px;
            font-weight: 600;
            color: #fbbf24;
            margin-bottom: 16px;
        }

        .top-three {
            display: flex;
            justify-content: center;
            align-items: flex-end;
            gap: 12px;
        }

        .top-player {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .top-player.second {
            order: 1;
        }

        .top-player.first {
            order: 2;
        }

        .top-player.third {
            order: 3;
        }

        .medal {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            margin-bottom: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.3);
        }

        .medal.gold {
            background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%);
        }

        .medal.silver {
            background: linear-gradient(135deg, #d1d5db 0%, #9ca3af 100%);
        }

        .medal.bronze {
            background: linear-gradient(135deg, #fb923c 0%, #f97316 100%);
        }

        .top-avatar {
            width: 56px;
            height: 56px;
            border-radius: 50%;
            background: #334155;
            border: 3px solid #dc2626;
            overflow: hidden;
            margin-bottom: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.3);
        }

        .top-player.first .top-avatar {
            width: 64px;
            height: 64px;
        }

        .top-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .top-avatar svg {
            width: 60%;
            height: 60%;
            color: #9ca3af;
        }

        .top-name {
            font-size: 12px;
            font-weight: 600;
            color: #f1f5f9;
            margin-bottom: 4px;
            text-align: center;
        }

        .top-points {
            font-size: 14px;
            font-weight: bold;
            color: #fbbf24;
        }

        /* Players Grid */
        .players-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 12px;
        }

        .player-card {
            background: #2d3748;
            border: 1px solid #475569;
            border-radius: 12px;
            padding: 16px;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.3);
            cursor: pointer;
            transition: all 0.2s;
            position: relative;
        }

        .player-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.4);
        }

        .rank-badge {
            position: absolute;
            top: 8px;
            left: 8px;
            background: #dc2626;
            color: white;
            width: 24px;
            height: 24px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            font-weight: bold;
        }

        .player-avatar {
            width: 72px;
            height: 72px;
            border-radius: 50%;
            background: #334155;
            margin: 8px 0 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        .player-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .player-avatar svg {
            width: 36px;
            height: 36px;
            color: #9ca3af;
        }

        .player-name {
            font-size: 14px;
            font-weight: 600;
            color: #f1f5f9;
            margin-bottom: 4px;
        }

        .player-points {
            font-size: 12px;
            color: #94a3b8;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 4px;
        }

        .player-points svg {
            width: 14px;
            height: 14px;
            color: #fbbf24;
        }

        .player-stats {
            display: flex;
            justify-content: center;
            gap: 12px;
            margin-top: 8px;
            font-size: 11px;
            color: #94a3b8;
        }

        .stat-item {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .stat-value {
            font-weight: 600;
            color: #f1f5f9;
        }

        /* Club card (вкладка по клубам) */
        .club-card {
            background: #2d3748;
            border: 1px solid #475569;
            border-radius: 12px;
            padding: 16px;
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 12px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.3);
            position: relative;
        }

        .club-rank {
            background: #dc2626;
            color: white;
            width: 28px;
            height: 28px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 13px;
            font-weight: bold;
            flex-shrink: 0;
        }

        .club-avatar {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            background: #334155;
            overflow: hidden;
            flex-shrink: 0;
        }

        .club-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .club-info {
            flex: 1;
            min-width: 0;
        }

        .club-name {
            font-size: 14px;
            font-weight: 600;
            color: #f1f5f9;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .club-meta {
            font-size: 12px;
            color: #94a3b8;
            margin-top: 2px;
        }

        .club-rating {
            font-size: 16px;
            font-weight: bold;
            color: #fbbf24;
            flex-shrink: 0;
        }

        /* Select клуба */
        .club-select-wrap {
            margin-bottom: 16px;
        }

        .club-select-label {
            font-size: 12px;
            color: #94a3b8;
            margin-bottom: 6px;
        }

        .club-select {
            width: 100%;
            background: #2d3748;
            border: 1px solid #475569;
            border-radius: 10px;
            color: #f1f5f9;
            font-size: 14px;
            padding: 10px 14px;
            appearance: none;
            -webkit-appearance: none;
            cursor: pointer;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='none' stroke='%2394a3b8' viewBox='0 0 24 24'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 12px center;
            padding-right: 36px;
        }

        .club-select:focus {
            outline: none;
            border-color: #fbbf24;
        }

        .club-players-title {
            font-size: 14px;
            font-weight: 600;
            color: #94a3b8;
            margin-bottom: 12px;
            margin-top: 20px;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 48px 16px;
        }

        .empty-icon {
            width: 64px;
            height: 64px;
            color: #475569;
            margin: 0 auto 16px;
        }

        .empty-title {
            font-size: 18px;
            font-weight: 600;
            color: #94a3b8;
            margin-bottom: 8px;
        }

        .empty-text {
            font-size: 14px;
            color: #64748b;
        }
    </style>

    <div class="container" x-data="ratingPage()">
        <!-- Header -->
        

        <!-- Tabs -->
        <div class="tabs-container">
            <div class="tabs">
                <button class="tab-button" :class="{ 'active': activeTab === 'players' }" @click="activeTab = 'players'">
                    Рейтинг игроков
                </button>
                <button class="tab-button" :class="{ 'active': activeTab === 'clubs' }" @click="activeTab = 'clubs'">
                    Рейтинг по клубу
                </button>
            </div>
        </div>

        <!-- Content -->
        <div class="content">

            <!-- ТОП-3 баннер (только на вкладке игроков) -->
            <template x-if="activeTab === 'players' && getTopThree().length >= 3">
                <div class="top-banner">
                    <div class="top-banner-title">🏆 ТОП-3 Лучших игроков</div>
                    <div class="top-three">
                        <template x-for="(player, index) in getTopThree()" :key="player.id">
                            <div class="top-player"
                                :class="{ 'first': index === 0, 'second': index === 1, 'third': index === 2 }">
                                <div class="medal"
                                    :class="{ 'gold': index === 0, 'silver': index === 1, 'bronze': index === 2 }">
                                    <span x-text="index === 0 ? '🥇' : index === 1 ? '🥈' : '🥉'"></span>
                                </div>
                                <div class="top-avatar">
                                    <template x-if="player.avatar">
                                        <img :src="player.avatar" :alt="player.name">
                                    </template>
                                    <template x-if="!player.avatar">
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:100%;height:100%;padding:10px;">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z">
                                            </path>
                                        </svg>
                                    </template>
                                </div>
                                <div class="top-name" x-text="player.name"></div>
                                <div class="top-points" x-text="player.rating + ' б'"></div>
                            </div>
                        </template>
                    </div>
                </div>
            </template>

            <!-- Сетка игроков -->
            <template x-if="activeTab === 'players'">
                <div>
                    <div class="players-grid">
                        <template x-for="(player, index) in getCurrentPlayers()" :key="player.id">
                            <div class="player-card">
                                <div class="rank-badge" x-text="index + 1"></div>
                                <div class="player-avatar">
                                    <template x-if="player.avatar">
                                        <img :src="player.avatar" :alt="player.name">
                                    </template>
                                    <template x-if="!player.avatar">
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z">
                                            </path>
                                        </svg>
                                    </template>
                                </div>
                                <div class="player-name" x-text="player.nickname || player.name"></div>
                                <div class="player-points">
                                    <svg fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M12 2L15.09 8.26L22 9.27L17 14.14L18.18 21.02L12 17.77L5.82 21.02L7 14.14L2 9.27L8.91 8.26L12 2Z" />
                                    </svg>
                                    <span x-text="player.rating + ' баллов'"></span>
                                </div>
                                <div class="player-stats">
                                    <div class="stat-item">
                                        <div class="stat-value" x-text="player.role || '—'"></div>
                                        <div>роль</div>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>

                    <div x-show="getCurrentPlayers().length === 0" class="empty-state">
                        <svg class="empty-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                            </path>
                        </svg>
                        <div class="empty-title">Рейтинг пока пуст</div>
                        <div class="empty-text">В этом периоде ещё нет данных о результатах игроков</div>
                    </div>
                </div>
            </template>

            <!-- Вкладка По клубам -->
            <template x-if="activeTab === 'clubs'">
                <div>
                    <!-- Рейтинг клубов -->
                    <template x-for="(club, index) in clubsData" :key="club.club_id">
                        <div class="club-card">
                            <div class="club-rank" x-text="index + 1"></div>
                            <div class="club-avatar">
                                <template x-if="club.avatar">
                                    <img :src="club.avatar" :alt="club.title">
                                </template>
                                <template x-if="!club.avatar">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:100%;height:100%;padding:10px;color:#9ca3af;">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z">
                                        </path>
                                    </svg>
                                </template>
                            </div>
                            <div class="club-info">
                                <div class="club-name" x-text="club.title"></div>
                                <div class="club-meta" x-text="club.members + ' игроков'"></div>
                            </div>
                            <div class="club-rating" x-text="club.total_rating + ' б'"></div>
                        </div>
                    </template>

                    <div x-show="clubsData.length === 0" class="empty-state">
                        <svg class="empty-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                            </path>
                        </svg>
                        <div class="empty-title">Нет данных по клубам</div>
                        <div class="empty-text">Рейтинг клубов будет доступен позже</div>
                    </div>

                    <!-- Выбор клуба и игроки -->
                    <div class="club-players-title">Игроки по клубу</div>

                    <div class="club-select-wrap">
                        <div class="club-select-label">Выберите клуб</div>
                        <select class="club-select" x-model="selectedClubId">
                            <option value="">— Все клубы —</option>
                            <template x-for="club in clubsData" :key="club.club_id">
                                <option :value="club.club_id" x-text="club.title"></option>
                            </template>
                        </select>
                    </div>

                    <div class="players-grid">
                        <template x-for="(player, index) in getClubPlayers()" :key="player.id">
                            <div class="player-card">
                                <div class="rank-badge" x-text="index + 1"></div>
                                <div class="player-avatar">
                                    <template x-if="player.avatar">
                                        <img :src="player.avatar" :alt="player.name">
                                    </template>
                                    <template x-if="!player.avatar">
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z">
                                            </path>
                                        </svg>
                                    </template>
                                </div>
                                <div class="player-name" x-text="player.nickname || player.name"></div>
                                <div class="player-points">
                                    <svg fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M12 2L15.09 8.26L22 9.27L17 14.14L18.18 21.02L12 17.77L5.82 21.02L7 14.14L2 9.27L8.91 8.26L12 2Z" />
                                    </svg>
                                    <span x-text="player.rating + ' баллов'"></span>
                                </div>
                                <div class="player-stats">
                                    <div class="stat-item">
                                        <div class="stat-value" x-text="player.role || '—'"></div>
                                        <div>роль</div>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>

                    <div x-show="getClubPlayers().length === 0" class="empty-state">
                        <svg class="empty-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                            </path>
                        </svg>
                        <div class="empty-title">Нет игроков</div>
                        <div class="empty-text">В этом клубе пока нет игроков с рейтингом</div>
                    </div>
                </div>
            </template>

        </div>
    </div>

    <script>

        // В Blade-шаблоне эти строки должны выглядеть так:
        //   const allAccounts = @json($accounts);
        //   const byClub = @json($byClub);
        // Для демонстрации используем тестовые данные из dd():
        const allAccounts = @json($accounts);
        const byClub = @json($byClub);

        function ratingPage() {
            return {
                activeTab: 'players',
                selectedClubId: '',

                // Все игроки, уже отсортированы по rating DESC контроллером
                players: allAccounts,

                // Данные по клубам из контроллера
                clubsData: byClub.map(function(item) {
                    return {
                        club_id: item.club_id,
                        title: item.club ? item.club.title : 'Клуб #' + item.club_id,
                        avatar: item.club ? item.club.avatar : null,
                        members: item.members,
                        total_rating: item.total_rating || 0
                    };
                }),

                // Топ-10 всех игроков по рейтингу
                getCurrentPlayers() {
                    return this.players
                        .slice()
                        .sort(function(a, b) { return b.rating - a.rating; })
                        .slice(0, 10);
                },

                // Игроки выбранного клуба (или всех клубов)
                getClubPlayers() {
                    var list = this.players.slice();
                    if (this.selectedClubId !== '') {
                        var id = parseInt(this.selectedClubId);
                        list = list.filter(function(p) { return p.club_id === id; });
                    }
                    return list
                        .sort(function(a, b) { return b.rating - a.rating; });
                },

                // Топ-3 для баннера
                getTopThree() {
                    return this.getCurrentPlayers().slice(0, 3);
                }
            };
        }
    </script>
    @include('include.club.bottom-nav')
@endsection
