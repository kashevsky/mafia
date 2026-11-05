@extends('layouts.club')

@section('content')
    <style>
        .container {
            overflow: hidden !important;
        }

        .flex {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 15px;
        }

        .about-logo {
            width: 100px;
            height: 100px;
            /* margin-right: 10px; */
            object-fit: cover;
        }

        @if (str_contains(url()->current(), '/events'))
            .user-bar {
                padding-bottom: 40px;
            }
        @endif
    </style>


    @include('include.club.header')



    <div class="slider-wrap">
        <div class="slider-container">

            <div class="slider" :style="`transform: translateX(-${currentSlide * 100}%)`">

                @forelse ($club->slides as $slide)
                    <div class="slide" style="background-image: url('{{ $slide->cover }}');">
                        <div class="slide-content">
                            <div class="slide-title">{{ $slide->title }}</div>
                            <div class="slide-date">{{ \Carbon\Carbon::parse($slide->date)->format('d.m.Y') }}</div>
                        </div>
                    </div>
                @empty
                    <div class="slide slide-placeholder" style="background: url('/new/banner.svg')">
                        <div class="slide-content">
                            <div class="slide-title">Ф.О.К.С.</div>
                            <div class="slide-desc">Добро пожаловать</div>
                        </div>
                    </div>
                @endforelse

            </div>

            <div class="slider-dots">
                @if ($club->slides->isNotEmpty())
                    @foreach ($club->slides as $slide)
                        <div class="dot" :class="{ 'active': currentSlide === {{ $loop->index }} }"
                            @click="currentSlide = {{ $loop->index }}"></div>
                    @endforeach
                @else
                    <div class="dot active"></div>
                @endif
            </div>

            <style>
                .tabs-header {
                    display: flex;
                    gap: 2px;
                    padding: 6px;
                    background: #ff0000;
                    border-radius: 12px;

                }

                .tab-button {
                    flex: 1;
                    padding: 10px 8px;
                    border-radius: 8px;
                    border: none;
                    background: transparent;
                    color: #ffffff;
                    font-size: 14px;
                    font-weight: 500;
                    cursor: pointer;
                    transition: background 0.15s, color 0.15s;
                }

                .tab-button.active {
                    background: #000000;
                    color: #ffffff;
                }
            </style>
        </div>
        <div class="tabs-header">

            <button class="tab-button" :class="{ 'active': activeTab === 'about' }"
                @click="activeTab = 'about'">Синклит</button>
            <button class="tab-button" :class="{ 'active': activeTab === 'schedule' }"
                @click="activeTab = 'schedule'">Расписание</button>
            <button class="tab-button" :class="{ 'active': activeTab === 'rules' }"
                @click="activeTab = 'rules'">Правила</button>
            <button class="tab-button" :class="{ 'active': activeTab === 'roles' }"
                @click="activeTab = 'roles'">Роли</button>
        </div>
    </div>




    <!-- Tabs -->


    <!-- Content -->
    <div class="content pt-40" id="events">
        <div class="flex pb-15">
            <img class="about-logo" src="{{ $club->avatar ?? '/def.svg' }}">
            <div>
                <h2 class="about-title">{{ $club->title }}</h2>
                {{-- <p class="change-club">Сменить клуб</p> --}}
            </div>
        </div>

        <!-- О клубе -->
        <div x-show="activeTab === 'about'" x-transition>
            <div class="about-section-main">
                <span class="corner-tr"></span>
                <span class="corner-bl"></span>

                <div class="about-text-main">
                    {!! $club->description !!}
                </div>

            </div>


            <div class="best-players-section" x-data="bestPlayersBlock()">
                <div class="best-players-header">
                    <a href="/КОНСТИТУЦИЯ ФЕДЕРАЦИИ Ф.pdf" class="constitution-btn">
                        Конституция Федерации
                    </a>
                </div>
                <div class="best-players-header">
                    <img src="/new/rating.svg" alt="Лучшие игроки клуба">
                    <span class="best-players-header-title">Лучшие игроки клуба</span>
                </div>

                <template x-if="getTopPlayers().length === 0">
                    <div class="best-players-empty">Нет игроков с рейтингом</div>
                </template>

                <template x-if="getTopPlayers().length > 0">
                    <div>
                        <!-- Первые 3 игрока — всегда видны -->
                        <div class="players-grid-compact">
                            <template x-for="(player, index) in getTopPlayers().slice(0, 3)" :key="player.id">
                                <div class="player-card-compact"
                                    x-on:click="window.location.href = '/account/show/' + player.id">
                                    <div class="rank-badge" x-text="index + 1"></div>
                                    <div class="player-avatar-sm">
                                        <template x-if="player.avatar">
                                            <img :src="player.avatar" :alt="player.name">
                                        </template>
                                        <template x-if="!player.avatar">
                                            <img src="/def.svg" alt="Avatar">
                                        </template>
                                    </div>
                                    <div class="player-name-sm" x-text="player.nickname || player.name"></div>
                                    <div class="player-rating-sm">
                                        <img src="/new/rating-sm.svg" alt="Рейтинг">
                                        <span x-text="player.rating"></span>
                                    </div>
                                </div>
                            </template>
                        </div>

                        <!-- Остальные игроки — скрыты под кнопкой -->
                        <template x-if="getTopPlayers().length > 3">
                            <div>
                                <div class="players-extra" :class="{ 'open': showAll }">
                                    <div class="players-grid-compact" x-on:click="window.location.href = '/account/show/' + player.id">
                                        <template x-for="(player, index) in getTopPlayers().slice(3)"
                                            :key="player.id">
                                            <div class="player-card-compact" x-on:click="window.location.href = '/account/show/' + player.id">
                                                <div class="rank-badge" x-text="index + 4"></div>
                                                <div class="player-avatar-sm">
                                                    <template x-if="player.avatar">
                                                        <img :src="player.avatar" :alt="player.name">
                                                    </template>
                                                    <template x-if="!player.avatar">
                                                        <img src="/def.svg" alt="Avatar">
                                                    </template>
                                                </div>
                                                <div class="player-name-sm" x-text="player.nickname || player.name"></div>
                                                <div class="player-rating-sm">
                                                    <img src="new/rating-sm.svg" alt="Рейтинг">
                                                    <span x-text="player.rating"></span>
                                                </div>
                                            </div>
                                        </template>
                                    </div>
                                </div>

                                <button class="show-more-btn" :class="{ 'open': showAll }" @click="showAll = !showAll">
                                    <span
                                        x-text="showAll ? 'Свернуть' : 'Смотреть остальных (' + (getTopPlayers().length - 3) + ')'"></span>
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>
                            </div>
                        </template>
                    </div>
                </template>
            </div>

            <script>
                const allAccounts = @json($accounts);

                function bestPlayersBlock() {
                    return {
                        showAll: false,
                        players: allAccounts,

                        getTopPlayers() {
                            return this.players
                                .slice()
                                .sort(function(a, b) {
                                    return b.rating - a.rating;
                                })
                                .slice(0, 12); // Ограничиваем массив строго первыми 12 элементами
                        }
                    };
                }
            </script>
            <br>



            <div class="stats-grid">
                <div class="stat-box box-1-bg">
                    <div class="stat-number">200+</div>
                    <div class="stat-label gray">Активных игроков</div>
                </div>
                <div class="stat-box box-2-bg">
                    <div class="stat-number">1,2,3</div>
                    <div class="stat-label gray">Места получают призы каждый день</div>
                </div>
                <div class="stat-box box-3-bg">
                    <div class="stat-number">4</div>
                    <div class="stat-label gray">Турнира в год</div>
                </div>
                <div class="stat-box box-4-bg">
                    <div class="stat-number">3-4</div>
                    <div class="stat-label gray">Локации центра Москвы</div>
                </div>
            </div>
            <br><br><br>

            {{-- <div class="" style="margin-top: 24px;">
                <h3 class="title-h3">Наши преимущества</h3>
                <div class="advantages-wrapper">
                    <div class="advantages-item">
                        <div class="advantages-icon">
                            1</div>
                        <div><strong>Профессиональные ведущие</strong><br><span>Все
                                наши модераторы имеют опыт более 100
                                игр</span></div>
                    </div>
                    <div class="advantages-item">
                        <div class="advantages-icon">
                            2</div>
                        <div><strong>Уютная площадка</strong><br><span>Комфортное
                                помещение в центре города с отличной
                                акустикой</span></div>
                    </div>
                    <div class="advantages-item">
                        <div class="advantages-icon">
                            3</div>
                        <div><strong>Система рейтинга</strong><br><span>Отслеживайте свой прогресс и соревнуйтесь с
                                другими игроками</span></div>
                    </div>
                </div>
            </div> --}}
        </div>
        <style>
            .flex {
                display: flex;
                align-items: center;
                justify-content: flex-start;


                display: flex;
                gap: 2px;
                align-items: center;
            }
        </style>
        <!-- Расписание -->
        <div x-show="activeTab === 'schedule'" x-transition x-data="{
            scheduleTab: 'upcoming',
            getUpcoming() {
                return schedule.filter(e => e.status === 'pending');
            },
            getPast() {
                return this.schedule
                    .filter(e => e.status === 'closed')
                    // Сортируем от самых новых дат к самым старым
                    .sort((a, b) => new Date(b.date) - new Date(a.date))
                    // Берем 7 самых первых (то есть 7 самых свежих) после сортировки
                    .slice(0, 7);
            },

            winTeamLabel(team) {
                if (team === 'mafia') return 'Победила мафия';
                if (team === 'civilians') return 'Победили мирные';
                return team;
            }
        }" style="min-height: 60vh">
            <style>
                .schedule-subtabs {
                    display: flex;
                    gap: 8px;
                    margin-bottom: 16px;
                }

                .user-bar {
                    /* padding-bottom: 40px; */
                }

                .schedule-subtab {
                    flex: 1;
                    padding: 8px 0;
                    border-radius: 8px;
                    border: 1px solid #374151;
                    background: transparent;
                    color: #9ca3af;
                    font-size: 14px;
                    font-weight: 500;
                    cursor: pointer;
                    transition: background 0.15s, color 0.15s, border-color 0.15s;
                }

                .schedule-subtab.active {
                    background: #ff0000;
                    border-color: #fe0000;
                    color: #fff;
                }

                .schedule-empty {
                    text-align: center;
                    padding: 32px 16px;
                    color: #64748b;
                    font-size: 14px;
                }

                /* ── Карточка прошедшей игры ── */
                .past-event-card {
                    background: #2B313C;
                    border-radius: 20px;
                    overflow: hidden;
                    display: flex;
                    flex-direction: column;
                    margin-bottom: 16px;
                }

                .pec-top {
                    display: flex;
                    align-items: flex-start;
                    padding: 24px 20px 20px 24px;
                    gap: 16px;
                }

                .pec-info {
                    flex: 1;
                    display: flex;
                    flex-direction: column;
                }

                .pec-date {
                    font-size: 26px;
                    font-weight: 900;
                    color: #ff0000;
                    letter-spacing: 0.3px;
                    margin-bottom: 8px;
                }

                .pec-title {
                    font-size: 20px;
                    font-weight: 700;
                    color: #f0f2f5;
                    line-height: 1.2;
                    margin-bottom: 18px;
                }

                .pec-meta {
                    display: flex;
                    flex-direction: column;
                }

                .pec-meta-row {
                    display: flex;
                    align-items: flex-start;
                    gap: 10px;
                    padding: 11px 0;
                    color: #d1d5db;
                    font-size: 14px;
                    line-height: 1.4;
                }

                .address {
                    font-size: 12px !important;
                }

                .pec-meta-row+.pec-meta-row {
                    border-top: 1px solid rgba(255, 255, 255, 0.1);
                }

                .pec-meta-icon {
                    width: 20px;
                    height: 20px;
                    flex-shrink: 0;
                    margin-top: 1px;
                }

                .pec-image {
                    width: 148px;
                    flex-shrink: 0;
                    border-radius: 12px;
                    overflow: hidden;
                    align-self: flex-start;
                }

                .pec-image img {
                    width: 100%;
                    height: 200px;
                    object-fit: cover;
                    object-position: top center;
                    display: block;
                    background: #2d3748;
                }

                .pec-divider {
                    height: 1px;
                    background: rgba(255, 255, 255, 0.08);
                    margin: 0 24px;
                }

                .pec-winners {
                    display: flex;
                    align-items: flex-start;
                    padding: 20px 16px 24px;
                }

                .pec-winner {
                    flex: 1;
                    display: flex;
                    flex-direction: column;
                    align-items: center;
                    gap: 10px;
                    position: relative;
                }

                .pec-winner+.pec-winner::before {
                    content: "";
                    position: absolute;
                    left: 0;
                    top: 10%;
                    height: 80%;
                    width: 1px;
                    background: rgba(255, 255, 255, 0.1);
                }

                .pec-medal {
                    width: 40px;
                    height: 40px;
                }

                .pec-avatar {
                    width: 72px;
                    height: 72px;
                    border-radius: 50%;
                    overflow: hidden;

                    flex-shrink: 0;
                    background: #2d3748;
                }

                .pec-avatar img {
                    width: 100%;
                    height: 100%;
                    object-fit: cover;
                }

                .pec-nickname {
                    font-size: 13px;
                    font-weight: 700;
                    color: #ff0000;
                    text-align: center;
                    text-transform: uppercase;
                    letter-spacing: 0.5px;
                }

                .pec-points {
                    font-size: 13px;
                    color: #d1d5db;
                    text-align: center;
                }

                /* ── Новая карточка события ── */
                .event-card {
                    background: #1e2433;
                    border-radius: 16px;
                    overflow: hidden;
                    display: flex;
                    flex-direction: column;
                    margin-bottom: 16px;
                    position: relative;
                }

                .event-card .card-body {
                    display: flex;
                    align-items: stretch;
                    padding: 14px 0 14px 18px;
                }

                .event-card .card-info {
                    flex: 1;
                    display: flex;
                    flex-direction: column;
                    padding-right: 16px;
                }

                .event-card .card-date {
                    font-size: 20px;
                    font-weight: 900;
                    color: #ff0000;
                    letter-spacing: 0.5px;
                    margin-bottom: 4px;
                }

                .event-card .card-title {
                    font-size: 17px;
                    font-weight: 700;
                    color: #f0f2f5;
                    line-height: 1.2;
                    margin-bottom: 5px;
                }

                .event-card .card-meta {
                    display: flex;
                    flex-direction: column;
                }

                .event-card .meta-row {
                    display: flex;
                    align-items: flex-start;
                    gap: 10px;
                    padding: 8px 0;
                    color: #d1d5db;
                    font-size: 14px;
                    line-height: 1.4;
                }

                .event-card .meta-row+.meta-row {
                    border-top: 1px solid rgba(255, 255, 255, 0.1);
                }

                .event-card .meta-icon {
                    width: 20px;
                    height: 20px;
                    flex-shrink: 0;
                    margin-top: 1px;
                }

                .event-card .card-image {
                    width: 150px;
                    flex-shrink: 0;
                    align-self: flex-start;
                    border-radius: 12px;
                    overflow: hidden;
                    margin-right: 20px;
                }

                .event-card .card-image img {
                    width: 100%;
                    height: 200px;
                    object-fit: cover;
                    object-position: top center;
                    display: block;
                    background: #2d3748;
                }

                .event-card .card-footer {
                    padding: 0 24px 24px;
                    position: absolute;
                    bottom: -25px;
                }

                .event-card .btn-details {
                    display: block;
                    width: 100%;
                    padding: 14px;
                    background: #ff0000;
                    color: #fff;
                    font-size: 14px;
                    font-weight: 200 !important;

                    text-align: center;
                    text-decoration: none;
                    border: none;
                    border-radius: 5px 5px 0 0;
                    padding: 6px 30px 10px 30px;
                    cursor: pointer;
                    transition: background 0.2s;
                }

                .event-card .btn-details:hover {
                    background: #c01515;
                }
            </style>

            <div class="schedule-subtabs">
                <button class="schedule-subtab" :class="{ 'active': scheduleTab === 'upcoming' }"
                    @click="scheduleTab = 'upcoming'">Предстоящие игры</button>
                <button class="schedule-subtab" :class="{ 'active': scheduleTab === 'past' }"
                    @click="scheduleTab = 'past'">Прошедшие игры</button>
            </div>

            <!-- Предстоящие игры -->
            <div x-show="scheduleTab === 'upcoming'">
                <template x-if="getUpcoming().length === 0">
                    <div class="schedule-empty">Нет предстоящих игр</div>
                </template>
                <template x-for="event in getUpcoming()" :key="event.id">
                    <div class="event-card">
                        <div class="card-body">

                            <!-- Левая часть -->
                            <div class="card-info">
                                <div class="card-date"
                                    x-text="new Intl.DateTimeFormat('ru-RU', { day: 'numeric', month: 'numeric', year: 'numeric' }).format(new Date(event.date))">
                                </div>

                                <div class="card-title" x-text="event.title"></div>

                                <div class="card-meta">
                                    <!-- Время -->
                                    <div class="meta-row">
                                        <img src="new/time.svg" alt="Время">
                                        <span x-text="event.time"></span>
                                    </div>

                                    <!-- Адрес -->
                                    <div class="meta-row address">
                                        <img src="new/place.svg" alt="Время">
                                        <span x-text="event.address"></span>
                                    </div>
                                </div>
                            </div>

                            <!-- Правая часть — изображение -->
                            <div class="card-image">
                                <img :src="event.avatar" :alt="event.title">
                            </div>

                        </div>

                        <!-- Кнопка -->
                        <div class="card-footer">
                            <a :href="'/event/' + event.id" class="btn-details">Подробнее</a>
                        </div>
                    </div>
                </template>
            </div>

            <!-- Прошедшие игры -->
            <div x-show="scheduleTab === 'past'">
                <template x-if="getPast().length === 0">
                    <div class="schedule-empty">Нет прошедших игр</div>
                </template>
                <template x-for="event in getPast()" :key="event.id">
                    <div class="past-event-card">

                        <!-- Верхняя часть: инфо + фото -->
                        <div class="pec-top">
                            <div class="pec-info">
                                <div class="pec-date"
                                    x-text="new Intl.DateTimeFormat('ru-RU', { day: 'numeric', month: 'numeric', year: 'numeric' }).format(new Date(event.date))">
                                </div>

                                <div class="pec-title" x-text="event.title"></div>

                                <div class="pec-meta">
                                    <div class="pec-meta-row">
                                        <svg class="pec-meta-icon" viewBox="0 0 24 24" fill="none" stroke="#e41f1f"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <circle cx="12" cy="12" r="9" />
                                            <polyline points="12 7 12 12 15.5 12" />
                                        </svg>
                                        <span x-text="event.time"></span>
                                    </div>

                                    <div class="pec-meta-row">
                                        <svg class="pec-meta-icon" viewBox="0 0 24 24" fill="none" stroke="#e41f1f"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path
                                                d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7z" />
                                            <circle cx="12" cy="9" r="2.5" />
                                        </svg>
                                        <span x-text="event.address"></span>
                                    </div>
                                </div>
                            </div>

                            <div class="pec-image">
                                <img :src="event.avatar" :alt="event.title">
                            </div>
                        </div>

                        <!-- Подиум победителей (только если есть winners) -->
                        <template x-if="event.winners && event.winners.length > 0">
                            <div>

                                <div class="pec-winners">

                                    <!-- 1 место -->
                                    <template x-if="event.winners[0]">
                                        <div class="pec-winner">
                                            <svg class="pec-medal" viewBox="0 0 40 40" fill="none">
                                                <circle cx="20" cy="24" r="13" fill="#c8960c"
                                                    opacity="0.2" />
                                                <circle cx="20" cy="24" r="10" stroke="#d4a017"
                                                    stroke-width="2" fill="none" />
                                                <text x="20" y="29" text-anchor="middle" font-size="10" font-weight="700"
                                                    fill="#d4a017">1</text>
                                                <line x1="14" y1="11" x2="11" y2="4"
                                                    stroke="#d4a017" stroke-width="2" stroke-linecap="round" />
                                                <line x1="26" y1="11" x2="29" y2="4"
                                                    stroke="#d4a017" stroke-width="2" stroke-linecap="round" />
                                                <path d="M14 11 Q20 14 26 11" stroke="#d4a017" stroke-width="2"
                                                    fill="none" stroke-linecap="round" />
                                            </svg>
                                            <div class="pec-avatar">
                                                <template
                                                    x-if="event.winners[0].account && event.winners[0].account.avatar">
                                                    <img :src="event.winners[0].account.avatar"
                                                        :alt="event.winners[0].account.nickname || event.winners[0].account
                                                            .name">
                                                </template>
                                                <template
                                                    x-if="!event.winners[0].account || !event.winners[0].account.avatar">
                                                    <img src="/new/def-ava.svg" alt="Avatar">
                                                </template>
                                            </div>
                                            <div class="pec-nickname"
                                                x-text="event.winners[0].account ? (event.winners[0].account.nickname || event.winners[0].account.name) : ''">
                                            </div>
                                            <div class="pec-points" x-text="event.winners[0].total + ' очков'"></div>
                                        </div>
                                    </template>

                                    <!-- 2 место -->
                                    <template x-if="event.winners[1]">
                                        <div class="pec-winner">
                                            <svg class="pec-medal" viewBox="0 0 40 40" fill="none">
                                                <circle cx="20" cy="24" r="13" fill="#9ca3af"
                                                    opacity="0.15" />
                                                <circle cx="20" cy="24" r="10" stroke="#9ca3af"
                                                    stroke-width="2" fill="none" />
                                                <text x="20" y="29" text-anchor="middle" font-size="10" font-weight="700"
                                                    fill="#9ca3af">2</text>
                                                <line x1="14" y1="11" x2="11" y2="4"
                                                    stroke="#9ca3af" stroke-width="2" stroke-linecap="round" />
                                                <line x1="26" y1="11" x2="29" y2="4"
                                                    stroke="#9ca3af" stroke-width="2" stroke-linecap="round" />
                                                <path d="M14 11 Q20 14 26 11" stroke="#9ca3af" stroke-width="2"
                                                    fill="none" stroke-linecap="round" />
                                            </svg>
                                            <div class="pec-avatar">
                                                <template
                                                    x-if="event.winners[1].account && event.winners[1].account.avatar">
                                                    <img :src="event.winners[1].account.avatar"
                                                        :alt="event.winners[1].account.nickname || event.winners[1].account
                                                            .name">
                                                </template>
                                                <template
                                                    x-if="!event.winners[1].account || !event.winners[1].account.avatar">
                                                    <img src="/new/def-ava.svg" alt="Avatar">
                                                </template>
                                            </div>
                                            <div class="pec-nickname"
                                                x-text="event.winners[1].account ? (event.winners[1].account.nickname || event.winners[1].account.name) : ''">
                                            </div>
                                            <div class="pec-points" x-text="event.winners[1].total + ' очков'"></div>
                                        </div>
                                    </template>

                                    <!-- 3 место -->
                                    <template x-if="event.winners[2]">
                                        <div class="pec-winner">
                                            <svg class="pec-medal" viewBox="0 0 40 40" fill="none">
                                                <circle cx="20" cy="24" r="13" fill="#b45309"
                                                    opacity="0.15" />
                                                <circle cx="20" cy="24" r="10" stroke="#cd7f32"
                                                    stroke-width="2" fill="none" />
                                                <text x="20" y="29" text-anchor="middle" font-size="10" font-weight="700"
                                                    fill="#cd7f32">3</text>
                                                <line x1="14" y1="11" x2="11" y2="4"
                                                    stroke="#cd7f32" stroke-width="2" stroke-linecap="round" />
                                                <line x1="26" y1="11" x2="29" y2="4"
                                                    stroke="#cd7f32" stroke-width="2" stroke-linecap="round" />
                                                <path d="M14 11 Q20 14 26 11" stroke="#cd7f32" stroke-width="2"
                                                    fill="none" stroke-linecap="round" />
                                            </svg>
                                            <div class="pec-avatar">
                                                <template
                                                    x-if="event.winners[2].account && event.winners[2].account.avatar">
                                                    <img :src="event.winners[2].account.avatar"
                                                        :alt="event.winners[2].account.nickname || event.winners[2].account
                                                            .name">
                                                </template>
                                                <template
                                                    x-if="!event.winners[2].account || !event.winners[2].account.avatar">
                                                    <img src="/new/def-ava.svg" alt="Avatar">
                                                </template>
                                            </div>
                                            <div class="pec-nickname"
                                                x-text="event.winners[2].account ? (event.winners[2].account.nickname || event.winners[2].account.name) : ''">
                                            </div>
                                            <div class="pec-points" x-text="event.winners[2].total + ' очков'"></div>
                                        </div>
                                    </template>

                                </div>
                            </div>
                        </template>

                    </div>
                </template>
            </div>
        </div>

        <!-- Правила -->
        <div x-show="activeTab === 'rules'" x-transition>
            <div class="rules-section">
                <h3 class="about-title-white">Основные правила игры</h3>
                <div class="advantages-item" bis_skin_checked="1">
                    <div class="advantages-icon" bis_skin_checked="1">
                        1</div>
                    <div bis_skin_checked="1"><strong>Фазы игры</strong><br><span>Игра делится на дневную и ночную фазы.
                            Днём все обсуждают и голосуют, ночью активны особые роли.</span></div>
                </div>
                <div class="advantages-item" bis_skin_checked="1">
                    <div class="advantages-icon" bis_skin_checked="1">
                        2</div>
                    <div bis_skin_checked="1"><strong>Голосование</strong><br><span>У каждого игрока есть минута на то,
                            чтобы высказать свое мнение по столу и отдать голос за подозреваемого. Тот, кто набрал больше
                            всего голосов, покидает игру.</span>
                    </div>
                </div>
                <div class="advantages-item" bis_skin_checked="1">
                    <div class="advantages-icon" bis_skin_checked="1">
                        3</div>
                    <div bis_skin_checked="1"><strong>Запрещено</strong><br><span>
                            Показывать свою карту другим игрокам, использовать метаинформацию, выходить из образа после
                            выбывания.
                        </span></div>
                </div>
                <div class="advantages-item" bis_skin_checked="1">
                    <div class="advantages-icon" bis_skin_checked="1">
                        4</div>
                    <div bis_skin_checked="1"><strong>Последнее слово</strong><br><span>Игрок, выбывающий по итогам
                            голосования, получает минуту на последнее слово перед выходом из игры.</span></div>
                </div>
                <div class="advantages-item" bis_skin_checked="1">
                    <div class="advantages-icon" bis_skin_checked="1">
                        5</div>
                    <div bis_skin_checked="1"><strong>Уважение</strong><br><span>Относитесь с уважением ко всем участникам.
                            Игра не должна переходить на личности.</span></div>
                </div>

            </div>

            <div class="rules-section">
                <h3 class="about-title">Условия победы</h3>
                <div class="advantages-item" bis_skin_checked="1">
                    <div class="advantages-icon" bis_skin_checked="1">
                        v</div>
                    <div bis_skin_checked="1"><strong>Победа мирных</strong><br><span>
                            Мирные жители побеждают, когда все члены мафии выбывают из игры.
                        </span></div>
                </div>
                <div class="advantages-item" bis_skin_checked="1">
                    <div class="advantages-icon" bis_skin_checked="1">
                        v</div>
                    <div bis_skin_checked="1"><strong>Победа мафии</strong><br><span>
                            Мафия побеждает, когда она остаётся в большинстве или выживает с мирным жителем 1 на 1.
                        </span></div>
                </div>

            </div>
        </div>

        <!-- Роли -->
        <div x-show="activeTab === 'roles'" x-transition>
            <div class="roles-grid">
                <style>
                    /* ======== КАРТОЧКА ======== */
                    .card {
                        position: relative;
                        display: flex;
                        align-items: stretch;
                        background: #21242f;
                        border-radius: 14px;
                        min-height: 190px;
                        overflow: hidden;
                        box-shadow: 0 6px 28px rgba(0, 0, 0, 0.55);
                        height: 220px;
                    }

                    /* Красный круг в верхнем правом углу, обрезанный overflow:hidden */
                    .card::before {
                        content: '';
                        position: absolute;
                        top: -22px;
                        right: -22px;
                        width: 52px;
                        height: 52px;
                        border-radius: 50%;
                        background: #ff1900;
                        z-index: 2;
                    }

                    /* ======== ЛЕВАЯ ЧАСТЬ — изображение ======== */
                    .card__left {
                        flex-shrink: 0;
                        padding: 10px 0 10px 10px;
                    }

                    .card__img-wrap {
                        position: relative;
                        width: 148px;
                        height: 100%;
                        border-radius: 10px;
                        overflow: hidden;
                        background: #2e3245;
                    }

                    .card__img {
                        width: 100%;
                        height: 100%;
                        object-fit: cover;
                        display: block;
                    }

                    /* Тёмный градиент снизу для подписи */
                    .card__img-overlay {
                        position: absolute;
                        inset: 0;
                        /* background: linear-gradient(to top,
                                                                                                                                                            rgba(0, 0, 0, 0.80) 0%,
                                                                                                                                                            rgba(0, 0, 0, 0.20) 45%,
                                                                                                                                                            transparent 70%); */

                        background-size: cover;
                        background-position: center;
                        z-index: 1;
                    }

                    /* Надпись ДЕТЕКТИВ внизу картинки */
                    .card__img-label {
                        position: absolute;
                        bottom: 9px;
                        left: 0;
                        right: 0;
                        text-align: center;
                        font-size: 10px;
                        font-weight: 800;
                        letter-spacing: 2.5px;
                        color: #ffffff;
                        text-transform: uppercase;
                        z-index: 1;
                    }

                    /* Иконка в правом верхнем углу картинки */
                    .card__badge {
                        position: absolute;
                        top: 7px;
                        right: 7px;
                        z-index: 1;
                        width: 30px;
                        height: 30px;

                    }

                    /* ======== ПРАВАЯ ЧАСТЬ — текст ======== */
                    .card__body {
                        padding: 16px 14px 10px 16px;
                        display: flex;
                        flex-direction: column;
                        justify-content: center;
                        gap: 8px;


                    }

                    .card__title {
                        font-size: 21px;
                        font-weight: 700;
                        color: #ffffff;
                        line-height: 1.2;
                    }

                    .card__subtitle {
                        font-size: 12px;
                        color: #ff1900;
                        font-weight: 400;
                        line-height: 1.3;
                    }

                    .card__desc {
                        font-size: 13px;
                        color: #c8cad4;
                        line-height: 1.2;
                        overflow-y: auto;
                        text-align: justify;

                        /* Включаем автоматический перенос слов (чтобы не было огромных дыр между словами) */
                        -webkit-hyphens: auto;
                        -ms-hyphens: auto;
                        hyphens: auto;
                    }
                </style>
                <div class="card">
                    <div class="card__left">
                        <div class="card__img-wrap">
                            <img class="card__img" src="" alt="Детектив" onerror="this.style.display='none'" />
                            <div class="card__img-overlay" style="background-image: url('/rols-b/don.png');"></div>
                        </div>
                    </div>
                    <div class="card__body">
                        <p class="card__title">Дон</p>
                        <p class="card__subtitle">Играет за мафию.</p>
                        <p class="card__desc">
                            Блокирует действие мирной роли (кроме Бомбы).
                            “Ночью” просыпается с мафией и
                            решает, использовать ли способность в одну из первых трёх ночей.
                        </p>
                    </div>
                </div>
                <div class="card">
                    <div class="card__left">
                        <div class="card__img-wrap">
                            <img class="card__img" src="" alt="Детектив" onerror="this.style.display='none'" />
                            <div class="card__img-overlay" style="background-image: url('/rols-b/pri.png');"></div>
                        </div>
                    </div>
                    <div class="card__body">
                        <p class="card__title">Приспешник</p>
                        <p class="card__subtitle">Играет за мафию.</p>
                        <p class="card__desc">
                            Указывает на одного мирного игрока (кроме Бомбы и Оборотня). Этот игрок в эту
                            же ночь будет считаться «мафией» для всех проверок (если проверит Шериф,
                            Детектив, Журналист, Мститель).


                        </p>
                    </div>
                </div>



                <div class="card">
                    <div class="card__left">
                        <div class="card__img-wrap">
                            <img class="card__img" src="" alt="Детектив" onerror="this.style.display='none'" />
                            <div class="card__img-overlay" style="background-image: url('/rols-b/she.png');"></div>
                        </div>
                    </div>
                    <div class="card__body">
                        <p class="card__title">Шериф</p>
                        <p class="card__subtitle">Играет за мирных жителей.</p>
                        <p class="card__desc">
                            Указывает на одного игрока. Ведущий покажет: «мафия» (большой палец вверх)
                            или «не мафия» (палец вниз).
                        </p>
                    </div>
                </div>
                <div class="card">
                    <div class="card__left">
                        <div class="card__img-wrap">
                            <img class="card__img" src="" alt="Детектив" onerror="this.style.display='none'" />
                            <div class="card__img-overlay" style="background-image: url('/rols-b/doc.png');"></div>
                        </div>
                    </div>
                    <div class="card__body">
                        <p class="card__title">Доктор</p>
                        <p class="card__subtitle">Играет за мирных жителей.</p>
                        <p class="card__desc">
                            Указывает на одного игрока для лечения.
                            Может лечить СЕБЯ максимум 2 раза за всю игру. Лечение других - бесконечно
                        </p>
                    </div>
                </div>
                <div class="card">
                    <div class="card__left">
                        <div class="card__img-wrap">
                            <img class="card__img" src="" alt="Детектив" onerror="this.style.display='none'" />
                            <div class="card__img-overlay" style="background-image: url('/rols-b/adv.png');"></div>
                        </div>
                    </div>
                    <div class="card__body">
                        <p class="card__title">Адвокат</p>
                        <p class="card__subtitle">Играет за мирных жителей.</p>
                        <p class="card__desc">
                            Указывает на одного игрока, которого хочет спасти на дневном суде. Если
                            “подопечный” наберет большинство голосов “днем”
                            , то не покинет стол.
                        </p>
                    </div>
                </div>
                <div class="card">
                    <div class="card__left">
                        <div class="card__img-wrap">
                            <img class="card__img" src="" alt="Детектив" onerror="this.style.display='none'" />
                            <div class="card__img-overlay" style="background-image: url('/rols-b/fer.png');"></div>
                        </div>
                    </div>
                    <div class="card__body">
                        <p class="card__title">Ферзь</p>
                        <p class="card__subtitle">Играет за мирных жителей.</p>
                        <p class="card__desc">
                            Указывает на одного игрока, которого считает мафией и убивает его (кроме
                            Стрелочника).

                        </p>
                    </div>
                </div>






                <div class="card">
                    <div class="card__left">
                        <div class="card__img-wrap">
                            <img class="card__img" src="" alt="Детектив" onerror="this.style.display='none'" />
                            <div class="card__img-overlay" style="background-image: url('/rols-b/mst.png');"></div>
                        </div>
                    </div>
                    <div class="card__body">
                        <p class="card__title">Мститель</p>
                        <p class="card__subtitle">Играет за мирных жителей.</p>
                        <p class="card__desc">
                            Указывает на одного игрока (подозреваемого). Само действие ничего не делает.
                            Способность срабатывает, только если его убивают в эту же ночь. Со Мстителем
                            умирает только мафия.

                        </p>
                    </div>
                </div>
                <div class="card">
                    <div class="card__left">
                        <div class="card__img-wrap">
                            <img class="card__img" src="" alt="Детектив" onerror="this.style.display='none'" />
                            <div class="card__img-overlay" style="background-image: url('/rols-b/sud.png');"></div>
                        </div>
                    </div>
                    <div class="card__body">
                        <p class="card__title">Судья</p>
                        <p class="card__subtitle">Играет за мирных жителей.</p>
                        <p class="card__desc">
                            Каждую ночь выбирает одного игрока. На дневном суде тот, на кого наложили Вето
                            сможет говорить, но не сможет голосовать.
                        </p>
                    </div>
                </div>
                <div class="card">
                    <div class="card__left">
                        <div class="card__img-wrap">
                            <img class="card__img" src="" alt="Детектив" onerror="this.style.display='none'" />
                            <div class="card__img-overlay" style="background-image: url('/rols-b/det.png');"></div>
                        </div>
                    </div>
                    <div class="card__body">
                        <p class="card__title">Детектив</p>
                        <p class="card__subtitle">Играет за мирных жителей.</p>
                        <p class="card__desc">
                            Проверяющая роль со способностью убивать проверенную мафию.
                            Ночью о проверяет (как Шериф). Узнаёт «мафия/не мафия». В следующую ночь он
                            может убить проверенную мафию.
                        </p>
                    </div>
                </div>
                <div class="card">
                    <div class="card__left">
                        <div class="card__img-wrap">
                            <img class="card__img" src="" alt="Детектив" onerror="this.style.display='none'" />
                            <div class="card__img-overlay" style="background-image: url('/rols-b/jur.png');"></div>
                        </div>
                    </div>
                    <div class="card__body">
                        <p class="card__title">Журналист</p>
                        <p class="card__subtitle">Играет за мирных жителей.</p>
                        <p class="card__desc">
                            Указывает на двух игроков . Ведущий показывает: «одна команда» (большой палец вверх) или «разные
                            команды» (палец вниз).

                        </p>
                    </div>
                </div>
                <div class="card">
                    <div class="card__left">
                        <div class="card__img-wrap">
                            <img class="card__img" src="" alt="Детектив" onerror="this.style.display='none'" />
                            <div class="card__img-overlay" style="background-image: url('/rols-b/bom.png');"></div>
                        </div>
                    </div>
                    <div class="card__body">
                        <p class="card__title">Бомба</p>
                        <p class="card__subtitle">Играет за мирных жителей.</p>
                        <p class="card__desc">
                            (ТОЛЬКО в первую и вторую ночь игры)
                            Эта роль выбирает нескольких игроков, кого считает мафией, и минирует (25% от
                            живых, минимум 1) — «заминированные» уйдут вместе с Бомбой при “взрыве”
                            .

                        </p>
                    </div>
                </div>
                <div class="card">
                    <div class="card__left">
                        <div class="card__img-wrap">
                            <img class="card__img" src="" alt="Детектив" onerror="this.style.display='none'" />
                            <div class="card__img-overlay" style="background-image: url('/rols-b/str.png');"></div>
                        </div>
                    </div>
                    <div class="card__body">
                        <p class="card__title">Стрелочник</p>
                        <p class="card__subtitle">Играет за мирных жителей.</p>
                        <p class="card__desc">
                            Если мафия или Ферзь стреляют в Стрелочника, ведущий его будит.
                            Стрелочник указывает на любого другого живого игрока, желательно на мафию.
                        </p>
                    </div>
                </div>
                <div class="card">
                    <div class="card__left">
                        <div class="card__img-wrap">
                            <img class="card__img" src="" alt="Детектив" onerror="this.style.display='none'" />
                            <div class="card__img-overlay" style="background-image: url('/rols-b/obo.png');"></div>
                        </div>
                    </div>
                    <div class="card__body">
                        <p class="card__title">ОБОРОТЕНЬ</p>
                        <p class="card__subtitle">Нейтральная роль.</p>
                        <p class="card__desc">
                            Пассивная роль: Неуязвим для выстрела мафии. Становится мафией когда вся
                            мафия покидает игру.

                        </p>
                    </div>
                </div>


            </div>
        </div>
    </div>

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
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                {{-- <div class="menu-items">
                    ...
                --}}
                <div class="menu-item">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z">
                        </path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    <span>Настройки</span>
                </div>
            </div>
        </div>
    </template>

    @include('include.club.bottom-nav')
@endsection
