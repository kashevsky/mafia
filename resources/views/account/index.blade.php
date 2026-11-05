@extends('layouts.club')
@section('content')

    <link rel="stylesheet" href="css/club/style.css">

    <style>
        .container {
            max-width: 448px;
            margin: 0 auto;
            background: #111827;
            min-height: 100vh;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.3);
            border-radius: 12px;
        }

        .tabs-header {
            position: sticky;
            top: 0;
            background: #1f2937;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.2);
            z-index: 10;
            display: flex;
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
        }

        .tab-button {
            flex: 1;
            padding: 16px;
            border: none;
            background: none;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            color: #9ca3af;
            border-bottom: 2px solid transparent;
            transition: all 0.2s;
            font-size: 14px;
            font-weight: 500;
        }

        .tab-button.active {
            color: #ffffff;
            border-bottom-color: #ffffff;
            background-color: #b91c1c !important;
        }

        .tab-button:hover {
            background-color: #1e293b;
            color: #f8fafc;
        }

        .tab-icon {
            width: 20px;
            height: 20px;
        }

        .content {
            padding: 16px;
        }

        .profile-header {
            text-align: center;
            margin-bottom: 24px;
        }

        .avatar {
            width: 96px;
            height: 96px;
            border-radius: 50%;
            background: #334155;
            margin: 0 auto 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            background-size: cover;
            background-position: center;
            border: 2px solid #3b82f6;
        }

        .nickname {
            font-size: 24px;
            font-weight: bold;
            color: #f8fafc;
            margin-bottom: 8px;
        }

        .contact-button {
            display: inline-block;
            background: #3b82f6;
            color: white;
            padding: 10px 16px;
            border-radius: 8px;
            text-decoration: none;
            font-size: 14px;
            transition: background 0.2s;
            text-align: center;
        }

        .contact-button:hover {
            background: #2563eb;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 16px;
            margin-bottom: 24px;
        }

        .info-card {
            background: #1e293b;
            border-radius: 8px;
            padding: 16px;
            border: 1px solid #334155;
        }

        .info-label {
            font-size: 12px;
            color: #9ca3af;
            margin-bottom: 4px;
        }

        .info-value {
            font-size: 16px;
            font-weight: 600;
            color: #f8fafc;
        }

        .bio-section {
            background: #1e293b;
            border-radius: 8px;
            padding: 16px;
            margin-bottom: 24px;
            border: 1px solid #334155;
        }

        .bio-title {
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 12px;
            color: #f8fafc;
        }

        .bio-text {
            color: #cbd5e1;
            line-height: 1.4;
        }

        .badges-container {
            text-align: center;
            padding: 48px 0;
        }

        .large-icon {
            width: 64px;
            height: 64px;
            color: #64748b;
            margin: 0 auto 16px;
        }

        .badges-title {
            font-size: 20px;
            font-weight: 600;
            margin-bottom: 8px;
            color: #f8fafc;
        }

        .badges-subtitle {
            color: #9ca3af;
            margin-bottom: 32px;
        }

        .badges-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 16px;
        }

        .badge-item {
            background: #1e293b;
            border-radius: 12px;
            padding: 16px;
            display: flex;
            flex-direction: column;
            align-items: center;
            border: 1px solid #334155;
        }

        .badge-icon {
            width: 64px;
            height: 64px;
            background: #334155;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 8px;
        }

        .badge-icon svg {
            width: 32px;
            height: 32px;
            color: #9ca3af;
        }

        .badge-name {
            font-size: 12px;
            color: #cbd5e1;
        }
    </style>

    @include('include.club.header')
    <div class="container" x-data="publicProfile()">
        <div class="tabs-header">
            <button class="tab-button" :class="{ 'active': activeTab === 'profile' }" @click="activeTab = 'profile'">
                <svg class="tab-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
                <span>Профиль</span>
            </button>
            <button class="tab-button" :class="{ 'active': activeTab === 'badges' }" @click="activeTab = 'badges'">
                <svg class="tab-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z">
                    </path>
                </svg>
                <span>Награды</span>
            </button>
        </div>

        <div class="content">
            <!-- Вкладка ПРОФИЛЬ -->
            <div x-show="activeTab === 'profile'" x-transition>
                <div class="profile-header">
                    <div class="avatar" :style="'background-image: url(' + player.avatar + ')'"></div>
                    <div class="nickname" x-text="player.nickname"></div>
                    <a :href="player.messenger" target="_blank" class="contact-button">
                        <svg width="16" height="16" fill="currentColor" class="inline-block mr-2"
                            viewBox="0 0 24 24">
                            <path
                                d="M2.01 21L23 10c-1.22-1.4-2.84-2.3-4.61-2.3-.25 0-.51.02-.76.07l-1.88.38-1.32-1.32-.38-.19c-.17-.08-.36-.14-.56-.18L12 6l-.19.04-.38.19-1.32 1.32-1.88-.38c-.25-.05-.51-.07-.76-.07-1.77 0-3.39.9-4.61 2.3L2.01 21zM12 12c2.21 0 4 1.79 4 4s-1.79 4-4 4-4-1.79-4-4 1.79-4 4-4z">
                            </path>
                        </svg>
                        Написать
                    </a>
                </div>

                <div class="info-grid">
                    <div class="info-card">
                        <div class="info-label">Город</div>
                        <div class="info-value" x-text="player.city"></div>
                    </div>
                    <div class="info-card">
                        <div class="info-label">Клуб</div>
                        <div class="info-value" x-text="player.club"></div>
                    </div>
                    <div class="info-card">
                        <div class="info-label">Стаж в игре</div>
                        <div class="info-value" x-text="getExperience()"></div>
                    </div>
                    <div class="info-card">
                        <div class="info-label">Баллы</div>
                        <div class="info-value" x-text="player.points"></div>
                    </div>
                </div>

                <div class="bio-section">
                    <h3 class="bio-title">О себе</h3>
                    <p class="bio-text" x-text="player.bio"></p>
                </div>
            </div>

            <!-- Вкладка НАГРАДЫ -->
            <div x-show="activeTab === 'badges'" x-transition>
                <div class="badges-container">
                    <svg class="large-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z">
                        </path>
                    </svg>

                    <h3 class="badges-title">Награды игрока</h3>
                    <p class="badges-subtitle">Достижения и медали, полученные в игре</p>
                    <div class="badges-grid">
                        <!-- Пример награды 1 -->
                        <div class="badge-item">
                            <div class="badge-icon">
                                <svg class="badge-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806">
                                    </path>
                                </svg>
                            </div>
                            <span class="badge-name" x-text="badges[0]?.name || 'Новичок'">Новичок</span>
                        </div>

                        <!-- Пример награды 2 -->
                        <div class="badge-item">
                            <div class="badge-icon">
                                <svg class="badge-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11 5H6a2 2 0 00-2 2v12a2 2 0 002 2h5m5 0H16a2 2 0 002-2V7a2 2 0 00-2-2h-5">
                                    </path>
                                </svg>
                            </div>
                            <span class="badge-name" x-text="badges[1]?.name || 'Опытный игрок'">Опытный игрок</span>
                        </div>

                        <!-- Пример награды 3 -->
                        <div class="badge-item">
                            <div class="badge-icon">
                                <svg class="badge-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                            <span class="badge-name" x-text="badges[2]?.name || 'Мастер тактики'">Мастер
                                тактики</span>
                        </div>

                        <!-- Пример награды 4 -->
                        <div class="badge-item">
                            <div class="badge-icon">
                                <svg class="badge-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                </svg>
                            </div>
                            <span class="badge-name" x-text="badges[3]?.name || 'Лидер клуба'">Лидер клуба</span>
                        </div>

                        <!-- Пример награды 5 -->
                        <div class="badge-item">
                            <div class="badge-icon">
                                <svg class="badge-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <span class="badge-name" x-text="badges[4]?.name || 'Ветеран'">Ветеран</span>
                        </div>

                        <!-- Пример награды 6 -->
                        <div class="badge-item">
                            <div class="badge-icon">
                                <svg class="badge-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2v-6a2 2 0 00-2-2H5">
                                    </path>
                                </svg>
                            </div>
                            <span class="badge-name" x-text="badges[5]?.name || 'Стратег'">Стратег</span>
                        </div>
                    </div>

                </div>

                <script>
                    function publicProfile() {
                        return {
                            activeTab: 'profile',
                            player: {
                                nickname: 'Dima',
                                avatar: '../../uploads/avatars/dima.jpg',
                                city: 'Москва',
                                club: 'Клуб "Ночные волки"',
                                experience: 2, // годы
                                points: 1542,
                                bio: 'Люблю играть в Мафию с друзьями. Предпочитаю роль шерифа — нравится распутывать интриги!',
                                messenger: 'https://t.me/dima_mafia'
                            },
                            badges: [{
                                    name: 'Новичок'
                                },
                                {
                                    name: 'Опытный игрок'
                                },
                                {
                                    name: 'Мастер тактики'
                                }
                                // Можно добавить больше наград по необходимости
                            ],
                            getExperience() {
                                const years = this.player.experience;
                                if (years === 1) return `${years} год`;
                                if (years >= 2 && years <= 4) return `${years} года`;
                                return `${years} лет`;
                            }
                        }
                    }
                </script>
                  @include('include.club.bottom-nav')
            @endsection

