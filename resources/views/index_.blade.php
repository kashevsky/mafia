@extends('layouts.club')
@section('content')
    <!-- Header -->
    @include('include.club.header')
    @include('include.club.video-line')
    <!-- Slider -->
    <div class="slider-container">
        <div class="slider" :style="`transform: translateX(-${currentSlide * 100}%)`">
            <template x-for="(slide, index) in slides" :key="index">
                <div :class="`slide slide-bg-${index + 1}`">
                    <div class="slide-content">
                        <div class="slide-title" x-text="slide.title"></div>
                        <div class="slide-desc" x-text="slide.desc"></div>
                        <div class="slide-date" x-text="slide.date"></div>
                    </div>
                </div>
            </template>

        </div>
        <div class="slider-dots">
            <template x-for="(slide, index) in slides" :key="index">
                <div class="dot" :class="{ 'active': currentSlide === index }" @click="currentSlide = index"></div>
            </template>
        </div>
    </div>

    <!-- Tabs -->
    <div class="tabs-header">
        <button class="tab-button" :class="{ 'active': activeTab === 'about1' }" @click="activeTab = 'about'">О
            клубе</button>
        <button class="tab-button" class="active"
            @click="activeTab = 'schedule'">Расписание</button>
        <button class="tab-button" :class="{ 'active': activeTab === 'rules' }"
            @click="activeTab = 'rules'">Правила</button>
        <button class="tab-button" :class="{ 'active': activeTab === 'roles' }"
            @click="activeTab = 'roles'">Роли</button>
    </div>

    <!-- Content -->
    <div class="content">
        <!-- О клубе -->
        <div x-show="activeTab === 'about'" x-transition>
            <div class="about-section">
                <h2 class="about-title">Добро пожаловать в клуб Мафия!</h2>
                <p class="about-text">
                    Мы — сообщество любителей психологической игры "Мафия". У нас вы найдете регулярные игры, турниры,
                    обучение для новичков и дружескую атмосферу.
                </p>
                <p class="about-text">
                    Наш клуб существует с 2020 года и объединяет более 200 активных игроков. Мы проводим игры каждую
                    неделю, организуем чемпионаты и мастер-классы от опытных ведущих.
                </p>
            </div>

            <div class="stats-grid">
                <div class="stat-box">
                    <div class="stat-number">200+</div>
                    <div class="stat-label gray">Активных игроков</div>
                </div>
                <div class="stat-box">
                    <div class="stat-number">450+</div>
                    <div class="stat-label gray">Проведено игр</div>
                </div>
                <div class="stat-box">
                    <div class="stat-number">15</div>
                    <div class="stat-label gray">Турниров в год</div>
                </div>
                <div class="stat-box">
                    <div class="stat-number">4</div>
                    <div class="stat-label gray">Года работы</div>
                </div>
            </div>

            <div class="about-section" style="margin-top: 24px;">
                <h3 class="about-title">Наши преимущества</h3>
                <div style="display: flex; flex-direction: column; gap: 12px;">
                    <div style="display: flex; gap: 12px; align-items: start;">
                        <div
                            style="width: 32px; height: 32px; background: #dc2626; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0; color: white; font-weight: bold;">
                            1</div>
                        <div><strong>Профессиональные ведущие</strong><br><span style="color: #6b7280; font-size: 14px;">Все
                                наши модераторы имеют опыт более 100
                                игр</span></div>
                    </div>
                    <div style="display: flex; gap: 12px; align-items: start;">
                        <div
                            style="width: 32px; height: 32px; background: #dc2626; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0; color: white; font-weight: bold;">
                            2</div>
                        <div><strong>Уютная площадка</strong><br><span style="color: #6b7280; font-size: 14px;">Комфортное
                                помещение в центре города с отличной
                                акустикой</span></div>
                    </div>
                    <div style="display: flex; gap: 12px; align-items: start;">
                        <div
                            style="width: 32px; height: 32px; background: #dc2626; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0; color: white; font-weight: bold;">
                            3</div>
                        <div><strong>Система рейтинга</strong><br><span
                                style="color: #6b7280; font-size: 14px;">Отслеживайте свой прогресс и соревнуйтесь с
                                другими игроками</span></div>
                    </div>
                </div>
            </div>
        </div>
        <style>
            .flex {
                display: flex;
                justify-content: space-between;
                gap: 12px;
                align-items: center;
            }
        </style>
        <!-- Расписание -->
        <div x-show="activeTab === 'schedule'" x-transition>
            <template x-for="event in schedule" :key="event.id">
                <div class="schedule-item">
                    <div class="flex">
                        <div>
                            <div class="schedule-date" x-text="event.date"></div>
                            <div class="schedule-title" x-text="event.title"></div>
                            <div class="schedule-time">
                                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span x-text="event.time"></span>
                            </div>
                            <div class="schedule-location">
                                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                    </path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                <span x-text="event.address"></span>
                            </div>
                            <a href="/event" class="btn_">Подробнее</a>
                        </div>
                        <div>
                            <img :src="event.avatar" alt="Фото игры1" style="width: 12px; border-radius: 6px;">
                        </div>
                    </div>
            </template>
        </div>

        <!-- Правила -->
        <div x-show="activeTab === 'rules'" x-transition>
            <div class="rules-section">
                <h3 class="about-title">Основные правила игры</h3>
                <div class="rule-item">
                    <div class="rule-title">1. Фазы игры</div>
                    <div class="rule-text">Игра делится на дневную и ночную фазы. Днем все обсуждают и голосуют, ночью
                        активны особые роли.</div>
                </div>
                <div class="rule-item">
                    <div class="rule-title">2. Голосование</div>
                    <div class="rule-text">Во время голосования каждый игрок поднимает руку за одного подозреваемого.
                        Игрок с наибольшим количеством голосов выбывает.</div>
                </div>
                <div class="rule-item">
                    <div class="rule-title">3. Запрещено</div>
                    <div class="rule-text">Показывать свою карту другим игрокам, использовать метаинформацию, выходить
                        из образа после выбывания.</div>
                </div>
                <div class="rule-item">
                    <div class="rule-title">4. Последнее слово</div>
                    <div class="rule-text">Игрок, выбывающий по итогам голосования, получает минуту на последнее слово
                        перед выходом из игры.</div>
                </div>
                <div class="rule-item">
                    <div class="rule-title">5. Уважение</div>
                    <div class="rule-text">Относитесь с уважением ко всем участникам. Игра не должна переходить на
                        личности.</div>
                </div>
            </div>

            <div class="rules-section">
                <h3 class="about-title">Условия победы</h3>
                <div class="rule-item">
                    <div class="rule-title">Победа мирных</div>
                    <div class="rule-text">Мирные жители побеждают, когда все члены мафии выбывают из игры.</div>
                </div>
                <div class="rule-item">
                    <div class="rule-title">Победа мафии</div>
                    <div class="rule-text">Мафия побеждает, когда количество мафиози становится равным или превышает
                        количество мирных жителей.</div>
                </div>
            </div>
        </div>

        <!-- Роли -->
        <div x-show="activeTab === 'roles'" x-transition>
            <div class="roles-grid">
                <div class="role-card">
                    <div class="role-header">
                        <div class="role-icon mafia">
                            <img src="/images/roles/det.png" alt="Детектив">
                        </div>

                        <div class="role-desc">Детектив — роль за мирных жителей. Каждую ночь он может проверить одного
                            игрока и узнать его роль или принадлежность к мафии.</div>
                    </div>

                </div>

                <div class="role-card">
                    <div class="role-header">
                        <div class="role-icon mafia">
                            <img src="/images/roles/med.png" alt="Детектив">
                        </div>

                        <div class="role-desc">Доктор — роль за мирных жителей. Каждую ночь он может вылечить одного игрока
                            (в т. ч. себя — один раз за игру), чтобы спасти его от «выстрела» мафии.</div>
                    </div>

                </div>

                <div class="role-card">
                    <div class="role-header">
                        <div class="role-icon mafia">
                            <img src="/images/roles/she.png" alt="Детектив">
                        </div>

                        <div class="role-desc">Шериф — роль за мирных жителей. Каждую ночь он может проверить одного игрока
                            и узнать, принадлежит ли тот к мафии (да/нет).</div>
                    </div>

                </div>

                <div class="role-card">
                    <div class="role-header">
                        <div class="role-icon mafia">
                            <img src="/images/roles/str.png" alt="Детектив">
                        </div>

                        <div class="role-desc">Стрелочник — роль за мирных жителей. Его задача — спровоцировать мафию и/или маньяка выстрелить в него ночью, чтобы после «пробуждения» раскрыть свою роль и предоставить городу информацию о том, кто в него целился.</div>
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
                    <div class="menu-item">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        <span>Мой профиль</span>
                    </div>
                    <div class="menu-item">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                            </path>
                        </svg>
                        <span>Рейтинг игроков</span>
                    </div>
                    <div class="menu-item">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                            </path>
                        </svg>
                        <span>Участники клуба</span>
                    </div>
                    <div class="menu-item">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span>История игр</span>
                    </div>
                    <div class="menu-item">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z">
                            </path>
                        </svg>
                        <span>Достижения</span>
                    </div> --}}
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
        </div>
    </template>

    @include('include.club.bottom-nav')
@endsection
