<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Клуб Мафия</title>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

<script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/persist@3.x.x/dist/cdn.min.js"></script>

    <link rel="stylesheet" href="/css/club/style.css?v={{ time() }}">

    <script src="https://telegram.org/js/telegram-web-app.js"></script>
<script>
  const tg = window.Telegram.WebApp;
  tg.ready();
  // Опционально: растянуть на весь экран
  tg.expand();
</script>
    <base href="{{ URL('/') }}">

    <style>
        /* ─── ВАШИ СУЩЕСТВУЮЩИЕ СТИЛИ (ОБЪЕДИНЕННЫЕ) ─── */
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

        }



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

        .schedule-subtabs {
            display: flex;
            gap: 8px;
            margin-bottom: 16px;
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

        /* ─── СТИЛИЗАЦИЯ МОДАЛЬНОГО ОКНА В ДИЗАЙНЕ .CARD ─── */
        .policy-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.9);
            /* Нуарное затемнение фона */
            backdrop-filter: blur(10px);
            z-index: 9999;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 16px;
        }

        .policy-modal {
            position: relative;
            /* Для позиционирования красного круга */
            background: #21242f;
            /* В цвет вашей .card */
            border-radius: 14px;
            /* В радиус вашей .card */
            overflow: hidden;
            /* Чтобы круг красиво обрезался */
            box-shadow: 0 6px 28px rgba(0, 0, 0, 0.55);
            /* Фирменная тень */
            max-width: 420px;
            width: 100%;
            padding: 32px 24px;
            color: #ffffff;
        }

        /* Тот самый красный мафиозный круг в углу модалки */
        .policy-modal::before {
            content: '';
            position: absolute;
            top: -22px;
            right: -22px;
            width: 52px;
            height: 52px;
            border-radius: 50%;
            background: #ff1900;
            /* Ваш цвет #ff1900 */
            z-index: 2;
        }

        .policy-modal h2 {
            margin-top: 0;
            margin-bottom: 6px;
            font-size: 21px;
            /* Как у .card__title */
            font-weight: 700;
            color: #ffffff;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .policy-modal .subtitle {
            font-size: 12px;
            /* Как у .card__subtitle */
            color: #ff1900;
            font-weight: 400;
            margin-bottom: 20px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .policy-modal p {
            font-size: 13.5px;
            /* Как у .card__desc */
            line-height: 1.5;
            color: #c8cad4;
            margin-bottom: 24px;
        }

        .policy-checkboxes {
            display: flex;
            flex-direction: column;
            gap: 12px;
            margin-bottom: 26px;
            text-align: left;
        }

        .policy-label {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            cursor: pointer;
            font-size: 13.5px;
            line-height: 1.4;
            color: #c8cad4;
            user-select: none;
            padding: 10px;
            background: rgba(255, 255, 255, 0.02);
            border-radius: 8px;
            border: 1px solid rgba(255, 255, 255, 0.05);
            transition: background 0.15s;
        }

        .policy-label:hover {
            background: rgba(255, 255, 255, 0.05);
        }

        .policy-label input[type="checkbox"] {
            margin-top: 1px;
            width: 18px;
            height: 18px;
            accent-color: #ff1900;
            /* В фирменный цвет */
            cursor: pointer;
            flex-shrink: 0;
        }

        .policy-label a {
            color: #ff1900;
            text-decoration: underline;
            font-weight: 500;
        }

        .policy-label a:hover {
            color: #ffffff;
        }

        /* Кнопка в концепции субтабов */
        .policy-btn {
            width: 100%;
            padding: 12px 0;
            border-radius: 8px;
            border: 1px solid #374151;
            background: transparent;
            color: #9ca3af;
            font-size: 14px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            cursor: pointer;
            transition: all 0.15s ease;
        }

        /* Когда нажаты обе галочки — становится активной, как .schedule-subtab.active */
        .policy-btn.ready {
            background: #ff1900;
            border-color: #ff1900;
            color: #ffffff;
            box-shadow: 0 4px 15px rgba(255, 25, 0, 0.35);
        }

        .policy-btn.ready:hover {
            background: #d61500;
            border-color: #d61500;
        }

        .policy-btn:disabled {
            cursor: not-allowed;
            opacity: 0.5;
        }

        .no-scroll {
            overflow: hidden !important;
        }
    </style>
</head>

<body x-data="mafiaMain()" x-ref="mafiaApp" :class="{ 'no-scroll': !policyAccepted }">

    <!-- МОДАЛЬНОЕ ОКНО СОГЛАСИЯ -->
    <template x-if="!policyAccepted">
        <div class="policy-overlay">
            <div class="policy-modal">
                <h2>Проверка роли</h2>
                <div class="subtitle">Вход в систему</div>
                <p>Перед тем как получить карту игрока и войти в клуб, подтвердите ваше согласие с документацией:</p>

                <div class="policy-checkboxes">
                    <!-- Чекбокс 1 -->
                    <label class="policy-label">
                        <input type="checkbox" x-model="checkPrivacy">
                        <span>Я ознакомлен(а) и принимаю условия <a href="/Пользовательское_соглашение_публичная_оферты.pdf" target="_blank"
                                @click.stop>политики конфиденциальности</a></span>
                    </label>

                    <!-- Чекбокс 2 -->
                    <label class="policy-label">
                        <input type="checkbox" x-model="checkData">
                        <span>Я даю согласие на <a href="/ПОЛИТИКА_ОБРАБОТКИ_ПЕРСОНАЛЬНЫХ_ДАННЫХ.pdf" target="_blank" @click.stop>обработку
                                персональных данных</a></span>
                    </label>
                </div>

                <!-- Кнопка меняет стиль на активный при проставлении галочек -->
                <button class="policy-btn" :class="{ 'ready': checkPrivacy && checkData }"
                    :disabled="!checkPrivacy || !checkData" @click="acceptPolicy()">
                    Принять соглашение
                </button>
            </div>
        </div>
    </template>

    <!-- ОСНОВНОЙ КОНТЕНТ -->
    <div class="container">
        @yield('content')
    </div>

</body>

<script>
    let mafiaApp;

    function mafiaMain() {
        return {
            menuOpen: false,
            showSearch: false,
            activeTab: (new URLSearchParams(window.location.search)).get('tab') || (window.location.pathname.includes('/events') ? 'schedule' : 'about'),

            currentSlide: 0,
            autoplayInterval: null,

            policyAccepted: false,
            checkPrivacy: false,
            checkData: false,

            user: {
                name: '{{ !empty($account->nickname) ? $account->nickname . (!empty($account->name) ? ' (' . $account->name . ')' : '') : $account->name ?? '' }}',
                avatar: '{{ $account->avatar ?? '' }}',
                points: {{ $account->rating ?? 0 }},
                balance: {{ $account->balance ?? 0 }}
            },
            slides: @json($club->slides),
            schedule: @json($club->games),

            init() {
                // Проверяем куку "policy_accepted=true" стандартными средствами JS
                this.policyAccepted = document.cookie.split('; ').includes('policy_accepted=true');

                console.log(this.slides);
                this.startAutoplay();
                mafiaApp = this;
            },

            acceptPolicy() {
                if (this.checkPrivacy && this.checkData) {
                    // Записываем куку на 365 дней вперед стандартным JS
                    const date = new Date();
                    date.setTime(date.getTime() + (365 * 24 * 60 * 60 * 1000));
                    document.cookie = `policy_accepted=true; expires=${date.toUTCString()}; path=/; SameSite=Strict`;

                    this.policyAccepted = true;
                }
            },


            startAutoplay() {
                this.autoplayInterval = setInterval(() => {
                    this.currentSlide = (this.currentSlide + 1) % this.slides.length;
                }, 4000);
            },
            destroy() {
                if (this.autoplayInterval) {
                    clearInterval(this.autoplayInterval);
                }
            }
        }
    }
</script>

</html>
