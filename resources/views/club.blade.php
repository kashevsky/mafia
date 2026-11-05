{{-- <!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Клуб Мафия</title>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background-color: #f9fafb;
            color: #1f2937;
            line-height: 1.5;
        }

        .container {
            max-width: 448px;
            margin: 0 auto;
            background: white;
            min-height: 100vh;
        }

        /* Header */
        .header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 16px;
            background: #dc2626;
            color: white;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

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

        /* User Bar */
        .user-bar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 16px;
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            color: white;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .user-avatar {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            background: white;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            border: 2px solid white;
        }

        .user-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .user-avatar svg {
            width: 24px;
            height: 24px;
            color: #dc2626;
        }

        .user-details {
            display: flex;
            flex-direction: column;
        }

        .user-name {
            font-weight: 600;
            font-size: 16px;
        }

        .user-points {
            display: flex;
            align-items: center;
            gap: 8px;
            background: rgba(255, 255, 255, 0.2);
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 600;
        }

        /* Slider */
        .slider-container {
            position: relative;
            overflow: hidden;
            background: #1f2937;
            margin: 0;
        }

        .slider {
            display: flex;
            transition: transform 0.5s ease-in-out;
        }

        .slide {
            min-width: 100%;
            height: 200px;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            background: linear-gradient(135deg, #1f2937 0%, #374151 100%);
        }

        .slide-content {
            text-align: center;
            color: white;
            padding: 20px;
            z-index: 1;
        }

        .slide-title {
            font-size: 22px;
            font-weight: bold;
            margin-bottom: 8px;
        }

        .slide-desc {
            font-size: 14px;
            opacity: 0.9;
            margin-bottom: 16px;
        }

        .slide-date {
            display: inline-block;
            background: #dc2626;
            padding: 6px 16px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .slider-dots {
            position: absolute;
            bottom: 12px;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            gap: 8px;
            z-index: 2;
        }

        .dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.5);
            cursor: pointer;
            transition: all 0.3s;
        }

        .dot.active {
            background: white;
            width: 24px;
            border-radius: 4px;
        }

        /* Tabs */
        .tabs-header {
            display: flex;
            background: white;
            border-bottom: 2px solid #e5e7eb;
            position: sticky;
            top: 0;
            z-index: 10;
        }

        .tab-button {
            flex: 1;
            padding: 14px 8px;
            border: none;
            background: none;
            cursor: pointer;
            color: #6b7280;
            font-size: 14px;
            font-weight: 500;
            border-bottom: 3px solid transparent;
            transition: all 0.2s;
        }

        .tab-button.active {
            color: #dc2626;
            border-bottom-color: #dc2626;
        }

        .tab-button:hover {
            background-color: #f9fafb;
        }

        /* Content */
        .content {
            padding: 16px;
        }

        /* О клубе */
        .about-section {
            margin-bottom: 24px;
        }

        .about-title {
            font-size: 20px;
            font-weight: bold;
            color: #1f2937;
            margin-bottom: 12px;
        }

        .about-text {
            color: #6b7280;
            line-height: 1.6;
            margin-bottom: 16px;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 12px;
            margin-top: 16px;
        }

        .stat-box {
            background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);
            padding: 16px;
            border-radius: 12px;
            text-align: center;
        }

        .stat-number {
            font-size: 32px;
            font-weight: bold;
            color: #dc2626;
        }

        .stat-label {
            font-size: 12px;
            color: #6b7280;
            margin-top: 4px;
        }

        /* Расписание */
        .schedule-item {
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            padding: 16px;
            margin-bottom: 12px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .schedule-date {
            display: inline-block;
            background: #dc2626;
            color: white;
            padding: 4px 12px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 600;
            margin-bottom: 8px;
        }

        .schedule-title {
            font-size: 16px;
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 4px;
        }

        .schedule-time {
            font-size: 14px;
            color: #6b7280;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .schedule-location {
            font-size: 14px;
            color: #6b7280;
            display: flex;
            align-items: center;
            gap: 6px;
            margin-top: 4px;
        }

        /* Правила */
        .rules-section {
            margin-bottom: 24px;
        }

        .rule-item {
            background: #f9fafb;
            border-left: 4px solid #dc2626;
            padding: 12px 16px;
            margin-bottom: 12px;
            border-radius: 4px;
        }

        .rule-title {
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 4px;
        }

        .rule-text {
            font-size: 14px;
            color: #6b7280;
        }

        /* Роли */
        .roles-grid {
            display: grid;
            gap: 12px;
        }

        .role-card {
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            padding: 16px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .role-header {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 8px;
        }

        .role-icon {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
        }

        .role-icon.mafia {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        }

        .role-icon.civilian {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        }

        .role-icon.special {
            background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
        }

        .role-name {
            font-size: 18px;
            font-weight: 600;
            color: #1f2937;
        }

        .role-desc {
            font-size: 14px;
            color: #6b7280;
            line-height: 1.5;
        }

        /* Menu Sidebar */
        .menu-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 100;
        }

        .menu-sidebar {
            position: fixed;
            top: 0;
            right: 0;
            bottom: 0;
            width: 280px;
            background: white;
            box-shadow: -2px 0 8px rgba(0, 0, 0, 0.1);
            z-index: 101;
            transform: translateX(100%);
            transition: transform 0.3s ease;
        }

        .menu-sidebar.open {
            transform: translateX(0);
        }

        .menu-header {
            background: #dc2626;
            color: white;
            padding: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .menu-title {
            font-size: 20px;
            font-weight: bold;
        }

        .menu-close {
            background: none;
            border: none;
            color: white;
            cursor: pointer;
            padding: 8px;
        }

        .menu-items {
            padding: 0;
        }

        .menu-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 16px 20px;
            border-bottom: 1px solid #e5e7eb;
            cursor: pointer;
            transition: background 0.2s;
        }

        .menu-item:hover {
            background: #f9fafb;
        }

        .menu-item svg {
            width: 20px;
            height: 20px;
            color: #6b7280;
        }

        .menu-item span {
            color: #1f2937;
            font-size: 15px;
        }
    </style>
</head>

<body>
    <div class="container" x-data="mafiaMain()">
        <!-- Slider -->
        <div class="slider-container">
            <div class="slider" :style="`transform: translateX(-${currentSlide * 100}%)`">
                <template x-for="(slide, index) in slides" :key="index">
                    <div class="slide">
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
                    <div class="dot" :class="{ 'active': currentSlide === index }" @click="currentSlide = index">
                    </div>
                </template>
            </div>
        </div>

        <!-- Tabs -->
        <div class="tabs-header">
            <button class="tab-button" :class="{ 'active': activeTab === 'about' }" @click="activeTab = 'about'">О
                клубе</button>
            <button class="tab-button" :class="{ 'active': activeTab === 'schedule' }"
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
                        Мы — сообщество любителей психологической игры "Мафия". У нас вы найдете регулярные игры,
                        турниры, обучение для новичков и дружескую атмосферу.
                    </p>
                    <p class="about-text">
                        Наш клуб существует с 2020 года и объединяет более 200 активных игроков. Мы проводим игры каждую
                        неделю, организуем чемпионаты и мастер-классы от опытных ведущих.
                    </p>
                </div>











                <div class="stats-grid">
                    <div class="stat-box ">
                        <div class="stat-number gray">200+</div>
                        <div class="stat-label">Активных игроков</div>
                    </div>
                    <div class="stat-box">
                        <div class="stat-number">450+</div>
                        <div class="stat-label">Проведено игр</div>
                    </div>
                    <div class="stat-box">
                        <div class="stat-number">15</div>
                        <div class="stat-label">Турниров в год</div>
                    </div>
                    <div class="stat-box">
                        <div class="stat-number">4</div>
                        <div class="stat-label">Года работы</div>
                    </div>
                </div>

                <div class="about-section" style="margin-top: 24px;">
                    <h3 class="about-title">Наши преимущества</h3>
                    <div style="display: flex; flex-direction: column; gap: 12px;">
                        <div style="display: flex; gap: 12px; align-items: start;">
                            <div
                                style="width: 32px; height: 32px; background: #dc2626; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0; color: white; font-weight: bold;">
                                1</div>
                            <div><strong>Профессиональные ведущие</strong><br><span
                                    style="color: #6b7280; font-size: 14px;">Все наши модераторы имеют опыт более 100
                                    игр</span></div>
                        </div>
                        <div style="display: flex; gap: 12px; align-items: start;">
                            <div
                                style="width: 32px; height: 32px; background: #dc2626; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0; color: white; font-weight: bold;">
                                2</div>
                            <div><strong>Уютная площадка</strong><br><span
                                    style="color: #6b7280; font-size: 14px;">Комфортное помещение в центре города с
                                    отличной акустикой</span></div>
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

            <!-- Расписание -->
            <div x-show="activeTab === 'schedule'" x-transition>
                <template x-for="event in schedule" :key="event.id">
                    <div class="schedule-item">
                        <div class="flex">
                            <div>
                                <div class="schedule-date" x-text="event.date"></div>
                                <div class="schedule-title" x-text="event.title"></div>
                                <div class="schedule-time">
                                    <svg width="16" height="16" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span x-text="event.time"></span>
                                </div>
                                <div class="schedule-location">
                                    <svg width="16" height="16" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                        </path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    <span x-text="event.address"></span>
                                </div>
                            </div>
                        </div>
                        <div class="flex-img">
                            <img :src="event.avatar" alt="Фото игры" style="width: 50px">
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
                        <div class="rule-text">Игра делится на дневную и ночную фазы. Днем все обсуждают и голосуют,
                            ночью активны особые роли.</div>
                    </div>
                    <div class="rule-item">
                        <div class="rule-title">2. Голосование</div>
                        <div class="rule-text">Во время голосования каждый игрок поднимает руку за одного
                            подозреваемого. Игрок с наибольшим количеством голосов выбывает.</div>
                    </div>
                    <div class="rule-item">
                        <div class="rule-title">3. Запрещено</div>
                        <div class="rule-text">Показывать свою карту другим игрокам, использовать метаинформацию,
                            выходить из образа после выбывания.</div>
                    </div>
                    <div class="rule-item">
                        <div class="rule-title">4. Последнее слово</div>
                        <div class="rule-text">Игрок, выбывающий по итогам голосования, получает минуту на последнее
                            слово перед выходом из игры.</div>
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
                        <div class="rule-text">Мафия побеждает, когда количество мафиози становится равным или
                            превышает количество мирных жителей.</div>
                    </div>
                </div>
            </div>

            <!-- Роли -->
            <div x-show="activeTab === 'roles'" x-transition>
                <div class="roles-grid">
                    <div class="role-card">
                        <div class="role-header">
                            <div class="role-icon mafia">🎭</div>
                            <div class="role-name">Мафия</div>
                        </div>
                        <div class="role-desc">Члены мафии знают друг друга и каждую ночь выбирают жертву для
                            устранения. Их цель — остаться в большинстве.</div>
                    </div>

                    <div class="role-card">
                        <div class="role-header">
                            <div class="role-icon mafia">👑</div>
                            <div class="role-name">Дон мафии</div>
                        </div>
                        <div class="role-desc">Главарь мафии. Ночью может проверить одного игрока, чтобы узнать,
                            является ли он комиссаром.</div>
                    </div>

                    <div class="role-card">
                        <div class="role-header">
                            <div class="role-icon civilian">👤</div>
                            <div class="role-name">Мирный житель</div>
                        </div>
                        <div class="role-desc">Обычные горожане без особых способностей. Днем участвуют в обсуждениях и
                            голосовании, чтобы вычислить мафию.</div>
                    </div>

                    <div class="role-card">
                        <div class="role-header">
                            <div class="role-icon civilian">🕵️</div>
                            <div class="role-name">Комиссар</div>
                        </div>
                        <div class="role-desc">Каждую ночь может проверить одного игрока и узнать, является ли он
                            членом мафии или мирным жителем.</div>
                    </div>

                    <div class="role-card">
                        <div class="role-header">
                            <div class="role-icon special">⚕️</div>
                            <div class="role-name">Доктор</div>
                        </div>
                        <div class="role-desc">Ночью может вылечить одного игрока, спасая его от устранения мафией. Не
                            может лечить одного игрока дважды подряд.</div>
                    </div>

                    <div class="role-card">
                        <div class="role-header">
                            <div class="role-icon special">💋</div>
                            <div class="role-name">Любовница</div>
                        </div>
                        <div class="role-desc">Ночью посещает одного игрока, блокируя его способности на эту ночь. Если
                            посещает мафию во время убийства — погибает.</div>
                    </div>

                    <div class="role-card">
                        <div class="role-header">
                            <div class="role-icon special">🐺</div>
                            <div class="role-name">Оборотень</div>
                        </div>
                        <div class="role-desc">Играет за себя. Каждую вторую ночь может убить игрока. Побеждает, если
                            остается последним выжившим.</div>
                    </div>

                    <div class="role-card">
                        <div class="role-header">
                            <div class="role-icon special">🎪</div>
                            <div class="role-name">Адвокат</div>
                        </div>
                        <div class="role-desc">Один раз за игру может спасти любого игрока от голосования днем, включая
                            себя.</div>
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
                            <svg width="24" height="24" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                    <div class="menu-items">
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
                        </div>
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
    </div>

</body>

</html> --}}
