<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Магазин — Вероятности</title>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        *,
        *::before,
        *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            background: #0f172a;
            font-family: system-ui, -apple-system, sans-serif;
            color: #f1f5f9;
            min-height: 100vh;
        }

        .container {
            max-width: 448px;
            margin: 0 auto;
            background: #1e293b;
            min-height: 100vh;
        }

        /* ── Header ── */
        .header {
            background: #dc2626;
            padding: 10px 16px;
            display: flex;
            align-items: center;
            gap: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, .4);
            position: sticky;
            top: 0;
            z-index: 20;
        }

        .header-title {
            font-size: 18px;
            font-weight: 600;
            flex: 1;
        }

        .balance-chip {
            background: rgba(255, 255, 255, .15);
            border-radius: 8px;
            padding: 6px 12px;
            font-size: 14px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        /* ── Tabs ── */
        .tabs {
            display: flex;
            background: #1e293b;
            border-bottom: 2px solid #334155;
            position: sticky;
            top: 48px;
            z-index: 10;
        }

        .tab-btn {
            flex: 1;
            padding: 13px 8px;
            border: none;
            background: none;
            cursor: pointer;
            color: #64748b;
            font-size: 14px;
            font-weight: 500;
            border-bottom: 3px solid transparent;
            margin-bottom: -2px;
            transition: color .2s, border-color .2s;
        }

        .tab-btn.active {
            color: #fbbf24;
            border-bottom-color: #fbbf24;
        }

        .tab-btn:hover:not(.active) {
            background: #263348;
        }

        /* ── Content ── */
        .content {
            padding: 16px;
        }

        /* ══════════════════════════════
       PROBABILITY CARDS (vertical stack)
    ══════════════════════════════ */
        .prob-list {
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .prob-card {
            background: #2d3748;
            border: 1px solid #475569;
            border-radius: 14px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, .3);
        }

        /* Card header */
        .prob-card-header {
            padding: 14px 16px 10px;
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 10px;
        }

        .prob-card-title {
            font-size: 16px;
            font-weight: 700;
            color: #f1f5f9;
        }

        .prob-card-desc {
            font-size: 12px;
            color: #94a3b8;
            margin-top: 3px;
            line-height: 1.4;
        }

        .purchased-tag {
            background: #059669;
            color: #fff;
            font-size: 10px;
            font-weight: 700;
            padding: 3px 8px;
            border-radius: 20px;
            white-space: nowrap;
            flex-shrink: 0;
        }

        /* ── Role slider ── */
        .slider-wrap {
            position: relative;
            padding: 0 0 12px;
        }

        .slider-track {
            display: flex;
            gap: 10px;
            overflow-x: auto;
            scroll-snap-type: x mandatory;
            scrollbar-width: none;
            padding: 4px 16px 4px;
            /* smooth scroll */
            scroll-behavior: smooth;
        }

        .slider-track::-webkit-scrollbar {
            display: none;
        }

        .role-slide {
            flex-shrink: 0;
            width: 90px;
            scroll-snap-align: start;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 6px;
            cursor: pointer;
        }

        .role-img-wrap {
            width: 80px;
            height: 80px;
            border-radius: 12px;
            overflow: hidden;
            background: #1e293b;
            border: 2px solid #334155;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: border-color .2s, transform .15s;
            position: relative;
        }

        .role-slide.active .role-img-wrap {
            border-color: #fbbf24;
            transform: scale(1.05);
        }

        .role-img-wrap img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* fallback emoji when no image */
        .role-emoji {
            font-size: 36px;
            line-height: 1;
        }

        .role-name {
            font-size: 11px;
            color: #94a3b8;
            text-align: center;
            line-height: 1.3;
            transition: color .2s;
        }

        .role-slide.active .role-name {
            color: #fbbf24;
            font-weight: 600;
        }

        /* Nav arrows */
        .slider-nav {
            position: absolute;
            top: 50%;
            transform: translateY(-60%);
            background: rgba(15, 23, 42, .8);
            border: 1px solid #475569;
            color: #f1f5f9;
            width: 28px;
            height: 28px;
            border-radius: 50%;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            z-index: 2;
            transition: background .15s;
        }

        .slider-nav:hover {
            background: #dc2626;
            border-color: #dc2626;
        }

        .slider-nav.prev {
            left: 2px;
        }

        .slider-nav.next {
            right: 2px;
        }

        .slider-nav[disabled] {
            opacity: .25;
            cursor: default;
        }

        /* Selected role info */
        .selected-role-info {
            margin: 0 16px 12px;
            padding: 10px 14px;
            background: #1e293b;
            border-radius: 10px;
            border: 1px solid #334155;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .role-info-emoji {
            font-size: 24px;
        }

        .role-info-text {
            flex: 1;
        }

        .role-info-name {
            font-size: 14px;
            font-weight: 600;
            color: #f1f5f9;
        }

        .role-info-desc {
            font-size: 12px;
            color: #64748b;
            margin-top: 2px;
        }

        /* Probability bar */
        .prob-bar-wrap {
            margin: 0 16px 12px;
        }

        .prob-bar-labels {
            display: flex;
            justify-content: space-between;
            font-size: 11px;
            color: #64748b;
            margin-bottom: 4px;
        }

        .prob-bar-labels span:last-child {
            color: #fbbf24;
            font-weight: 600;
        }

        .prob-bar-track {
            height: 6px;
            background: #1e293b;
            border-radius: 3px;
            overflow: hidden;
        }

        .prob-bar-fill {
            height: 100%;
            border-radius: 3px;
            background: linear-gradient(90deg, #b91c1c, #dc2626);
            transition: width .4s ease;
        }

        /* Card footer */
        .prob-card-footer {
            padding: 10px 16px 14px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-top: 1px solid #334155;
        }

        .prob-price {
            font-size: 18px;
            font-weight: 700;
            color: #fbbf24;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .boost-btn {
            background: #dc2626;
            color: #fff;
            border: none;
            border-radius: 8px;
            padding: 9px 20px;
            font-size: 13px;
            font-weight: 700;
            cursor: pointer;
            transition: background .15s;
        }

        .boost-btn:hover {
            background: #b91c1c;
        }

        .boost-btn:disabled {
            background: #374151;
            color: #64748b;
            cursor: not-allowed;
        }

        .boost-btn.bought {
            background: #059669;
            cursor: default;
        }

        /* ── Other tabs placeholder ── */
        .placeholder {
            padding: 60px 0;
            text-align: center;
            color: #475569;
            font-size: 14px;
        }

        /* ── Modal ── */
        .modal-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, .75);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 100;
        }

        .modal {
            background: #1e293b;
            border: 1px solid #475569;
            border-radius: 16px;
            width: 88%;
            max-width: 380px;
            box-shadow: 0 12px 40px rgba(0, 0, 0, .5);
        }

        .modal-header {
            padding: 16px 20px 8px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .modal-title {
            font-size: 17px;
            font-weight: 700;
            color: #f1f5f9;
        }

        .modal-close {
            background: none;
            border: none;
            cursor: pointer;
            color: #64748b;
            font-size: 22px;
            line-height: 1;
        }

        .modal-body {
            padding: 6px 20px 16px;
            font-size: 14px;
            color: #94a3b8;
            line-height: 1.6;
        }

        .modal-body strong {
            color: #fbbf24;
        }

        .modal-footer {
            display: flex;
            gap: 10px;
            padding: 10px 20px 20px;
            border-top: 1px solid #334155;
        }

        .btn-cancel {
            flex: 1;
            padding: 11px;
            border: none;
            border-radius: 8px;
            background: #334155;
            color: #94a3b8;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
        }

        .btn-cancel:hover {
            background: #475569;
        }

        .btn-ok {
            flex: 1;
            padding: 11px;
            border: none;
            border-radius: 8px;
            background: #dc2626;
            color: #fff;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
        }

        .btn-ok:hover {
            background: #b91c1c;
        }

        [x-cloak] {
            display: none !important;
        }
    </style>
</head>

<body>
    <div class="container" x-data="shop()">

        <!-- Header -->
        <div class="header">
            <span class="header-title">🃏 Магазин</span>
            <div class="balance-chip">
                🪙 <span x-text="balance.toLocaleString('ru-RU')"></span>
            </div>
        </div>

        <!-- Tabs -->
        <div class="tabs">
            <button class="tab-btn" :class="{ active: tab === 'merch' }" @click="tab = 'merch'">Мерч</button>
            <button class="tab-btn" :class="{ active: tab === 'probabilities' }"
                @click="tab = 'probabilities'">Вероятности</button>
            <button class="tab-btn" :class="{ active: tab === 'gifts' }" @click="tab = 'gifts'">Подарки</button>
        </div>

        <!-- Content -->
        <div class="content">

            <!-- Мерч -->
            <div x-show="tab === 'merch'" x-transition>
                <div class="placeholder">🛍 Раздел мерча</div>
            </div>

            <!-- Вероятности -->
            <div x-show="tab === 'probabilities'" x-transition>
                <div class="prob-list">
                    <template x-for="prob in probabilities" :key="prob.id">
                        <div class="prob-card">

                            <!-- Card header -->
                            <div class="prob-card-header">
                                <div>
                                    <div class="prob-card-title" x-text="prob.title"></div>
                                    <div class="prob-card-desc" x-text="prob.desc"></div>
                                </div>
                                <span class="purchased-tag" x-show="prob.purchased">✓ Куплено</span>
                            </div>

                            <!-- Role slider -->
                            <div class="slider-wrap" x-data="slider(prob.roles)" x-init="init()">
                                <!-- Prev arrow -->
                                <button class="slider-nav prev" @click="prev()" :disabled="current === 0">‹</button>

                                <!-- Track -->
                                <div class="slider-track" x-ref="track" @scroll="onScroll()">
                                    <template x-for="(role, ri) in roles" :key="role.id">
                                        <div class="role-slide" :class="{ active: ri === current }"
                                            @click="scrollTo(ri)">
                                            <div class="role-img-wrap">
                                                <template x-if="role.img">
                                                    <img :src="role.img" :alt="role.name" />
                                                </template>
                                                <template x-if="!role.img">
                                                    <span class="role-emoji" x-text="role.emoji"></span>
                                                </template>
                                            </div>
                                            <span class="role-name" x-text="role.name"></span>
                                        </div>
                                    </template>
                                </div>

                                <!-- Next arrow -->
                                <button class="slider-nav next" @click="next()"
                                    :disabled="current === roles.length - 1">›</button>
                            </div>

                            <!-- Selected role info + prob bar -->
                            <template x-data="slider(prob.roles)" x-init="init()">
                                <!-- reuse slider state via separate component — instead use prob directly -->
                            </template>



                            <!-- Footer -->
                            <div class="prob-card-footer">
                                <div class="prob-price">
                                    🪙 <span x-text="prob.price"></span>
                                </div>
                                <button class="boost-btn" :class="{ bought: prob.purchased }"
                                    :disabled="prob.purchased || balance < prob.price" @click="openModal(prob)"
                                    x-text="prob.purchased ? 'Куплено' : (balance >= prob.price ? 'Купить' : 'Недостаточно')"></button>
                            </div>

                        </div>
                    </template>
                </div>
            </div>

            <!-- Подарки -->
            <div x-show="tab === 'gifts'" x-transition>
                <div class="placeholder">🎁 Раздел подарков</div>
            </div>

        </div>

        <!-- Confirm Modal -->
        <div class="modal-overlay" x-show="modal" x-cloak @click.self="modal = null">
            <div class="modal" @click.stop>
                <div class="modal-header">
                    <span class="modal-title" x-text="modal ? `Повысить «${modal.title}»?` : ''"></span>
                    <button class="modal-close" @click="modal = null">×</button>
                </div>
                <div class="modal-body" x-show="modal">
                    Будет списано <strong x-text="modal ? '🪙 ' + modal.price : ''"></strong> монет.<br>
                    Остаток: <strong
                        x-text="modal ? '🪙 ' + (balance - modal.price).toLocaleString('ru-RU') : ''"></strong>
                </div>
                <div class="modal-footer">
                    <button class="btn-cancel" @click="modal = null">Отмена</button>
                    <button class="btn-ok" @click="confirmBuy()">Повысить</button>
                </div>
            </div>
        </div>

    </div>

    <script>
        /* ── Slider component ── */
        function slider(roles) {
            return {
                roles,
                current: 0,

                init() {
                    // nothing needed on init
                },

                scrollTo(index) {
                    this.current = index;
                    const track = this.$refs.track;
                    const slide = track.children[index];
                    if (slide) {
                        track.scrollTo({
                            left: slide.offsetLeft - 16,
                            behavior: 'smooth'
                        });
                    }
                },

                next() {
                    if (this.current < this.roles.length - 1) this.scrollTo(this.current + 1);
                },

                prev() {
                    if (this.current > 0) this.scrollTo(this.current - 1);
                },

                onScroll() {
                    const track = this.$refs.track;
                    const slideWidth = track.children[0]?.offsetWidth + 10 || 100;
                    const idx = Math.round(track.scrollLeft / slideWidth);
                    this.current = Math.max(0, Math.min(idx, this.roles.length - 1));
                }
            };
        }

        /* ── Shop page ── */
        function shop() {
            return {
                tab: 'probabilities',
                balance: 2450,
                modal: null,

                probabilities: [{
                        id: 1,
                        title: 'Мирные роли',
                        desc: 'Повысить шанс получить мирную роль в игре',
                        price: 300,
                        currentValue: 42,
                        maxValue: 80,
                        purchased: false,
                        roles: [{
                                id: 1,
                                name: 'Мирный',
                                emoji: '🏠',
                                img: null
                            },
                            {
                                id: 2,
                                name: 'Доктор',
                                emoji: '💊',
                                img: null
                            },
                            {
                                id: 3,
                                name: 'Шериф',
                                emoji: '⭐',
                                img: null
                            },
                            {
                                id: 4,
                                name: 'Путана',
                                emoji: '💋',
                                img: null
                            },
                            {
                                id: 5,
                                name: 'Маньяк',
                                emoji: '🔪',
                                img: null
                            },
                        ]
                    },
                    {
                        id: 2,
                        title: 'Мафия',
                        desc: 'Повысить шанс оказаться в команде мафии',
                        price: 450,
                        currentValue: 18,
                        maxValue: 50,
                        purchased: false,
                        roles: [{
                                id: 10,
                                name: 'Мафия',
                                emoji: '🔫',
                                img: null
                            },
                            {
                                id: 11,
                                name: 'Дон мафии',
                                emoji: '👑',
                                img: null
                            },
                        ]
                    },
                    {
                        id: 3,
                        title: 'Особые роли',
                        desc: 'Повысить шанс получить редкую роль',
                        price: 600,
                        currentValue: 8,
                        maxValue: 35,
                        purchased: false,
                        roles: [{
                                id: 20,
                                name: 'Маньяк-лав',
                                emoji: '💘',
                                img: null
                            },
                            {
                                id: 21,
                                name: 'Камикадзе',
                                emoji: '💣',
                                img: null
                            },
                            {
                                id: 22,
                                name: 'Ангел',
                                emoji: '😇',
                                img: null
                            },
                            {
                                id: 23,
                                name: 'Бомж',
                                emoji: '🧟',
                                img: null
                            },
                        ]
                    },
                    {
                        id: 4,
                        title: 'Доктор',
                        desc: 'Гарантированно чаще лечить игроков',
                        price: 350,
                        currentValue: 12,
                        maxValue: 45,
                        purchased: false,
                        roles: [{
                            id: 30,
                            name: 'Доктор',
                            emoji: '💊',
                            img: null
                        }, ]
                    },
                    {
                        id: 5,
                        title: 'Шериф',
                        desc: 'Чаще вести расследование в роли шерифа',
                        price: 400,
                        currentValue: 10,
                        maxValue: 40,
                        purchased: false,
                        roles: [{
                                id: 40,
                                name: 'Шериф',
                                emoji: '⭐',
                                img: null
                            },
                            {
                                id: 41,
                                name: 'Комиссар',
                                emoji: '🔍',
                                img: null
                            },
                        ]
                    },
                ],

                openModal(prob) {
                    if (prob.purchased || this.balance < prob.price) return;
                    this.modal = prob;
                },

                confirmBuy() {
                    if (!this.modal) return;
                    this.balance -= this.modal.price;
                    this.modal.purchased = true;
                    this.modal.currentValue = Math.min(this.modal.currentValue + 5, this.modal.maxValue);
                    this.modal = null;
                }
            };
        }
    </script>
</body>

</html>
