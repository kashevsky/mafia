<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="utf-8">
    <title>Результаты игры</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="preconnect" href="https://googleapis.com">
    <link href="https://googleapis.com/css2?family=Cinzel:wght@700;900&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            background: black;
            margin: 0;
            padding: 0;
        }

        .results-page {
            max-width: 520px;
            margin: 0 auto;
            padding: 12px 1px 8px;
            background: #020202 !important;
        }

        .plashka {
            background-image: url('/images/plashka.svg');
            position: relative;
            text-align: center;
            background-size: 100% auto;
            background-repeat: no-repeat;
            height: 100px;
            color: #fff;
            border-radius: 64px 0 0 135px;
            max-width: 520px;
            margin: 0 auto;
        }

        .results-header {
            height: 50%;
        }

        .results-footer {
            height: 50%;
        }

        .results-title {
            font-size: clamp(16px, 1.2rem + 1vw, 20px);
            font-weight: 600;
            color: #fff;
            margin-top: -7px;
            padding-left: 60px;
        }

        .results-upper {
            padding-left: 60px;
            font-size: 18px;
            font-weight: 600;
            color: #fff;
            padding-top: 4px;
        }

        @media (min-width: 320px) and (max-width: 380px) {
            .results-title { margin-top: -10px; }
        }
        @media (min-width: 381px) and (max-width:389px) {
            .results-title { margin-top: -2px; }
        }
        @media (min-width: 390px) and (max-width:400px) {
            .results-title { margin-top: -8px; }
        }
        @media (min-width: 401px) and (max-width:420px) {
            .results-title { margin-top: -6px; }
        }
        @media (min-width: 421px) and (max-width:440px) {
            .results-title { margin-top: -2px; }
        }

        .plashka-circle {
            position: absolute;
            top: -2px;
            left: -4px;
            width: 86px;
            height: 86px;
            border-radius: 50%;
            background-image: url('/images/elipse.svg');
            background-size: 100% auto;
            background-repeat: no-repeat;
            background-position: center;
        }

        @media (min-width: 768px) {
            .results-title { margin-top: 8px; }
            .results-upper { padding-top: 10px; }
            .plashka-circle {
                width: 106px;
                height: 106px;
                left: -6px;
                top: -6px;
            }
        }

        .city {
            text-align: center;
            font-size: 23px;
            font-weight: 700;
            color: #fff;
            margin-bottom: 22px;
        }

        .players {
            border: 1.5px solid #ffffff;
            border-radius: 20px;
            background: #030303;
            padding: 10px;
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .mafia {
            border-color: #ff0000;
            color: #ff0000;
        }

        .row {
            display: flex;
            align-items: center;
            background: #2B313C;
            border-radius: 14px;
            height: 64px;
            overflow: hidden;
        }

        .row__avatar {
            flex-shrink: 0;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            margin: 0 14px;
            overflow: hidden;
            background: #1a0000;
        }

        .row__avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .row__info {
            flex: 1 1 0;
            min-width: 0;
            padding-right: 20px;
        }

        .row__name {
            font-size: 12px;
            font-weight: 400;
            color: #fff;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .row__nick {
            font-size: 10px;
            font-weight: 300;
            color: #cc0000;
            margin-top: 4px;
        }

        .divider {
            width: 1px;
            height: 46px;
            background: #3e3e3e;
            flex-shrink: 0;
        }

        .row__role {
            font-size: 10px;
            color: #ccc;
            padding: 0 18px;
            white-space: nowrap;
            flex-shrink: 0;
        }

        .row__score {
            padding: 0 22px 0 18px;
            font-size: 18px;
            font-weight: 900;
            color: #cc0000;
            flex-shrink: 0;
        }

        .screenshot-controls {
            display: flex;
            gap: 10px;
            justify-content: center;
            align-items: center;
            margin-top: 20px;
        }

        .btn-screenshot {
            all: unset;
            cursor: pointer;
            padding: 10px 20px;
            background: #11a700;
            color: #fff;
            border-radius: 5px;
            font-weight: 500;
            font-size: 14px;
            transition: opacity 0.15s;
        }

        .btn-screenshot:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .red {
            background: #ff0000;
        }

        #status-message {
            margin-top: 15px;
            font-size: 14px;
            text-align: center;
            font-weight: 500;
        }
        .status-ok { color: #11a700; }
        .status-error { color: #ff0000; }
        .status-loading { color: #9ca3af; }

        .is-screenshotting {
            width: 430px !important;
            max-width: 430px !important;
            margin: 0 auto !important;
            padding: 16px 8px !important;
        }

        /* Компактный двухколоночный вид для больших игр */
        .players.compact {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 5px;
        }

        .players.compact .row {
            height: 38px;
            border-radius: 10px;
        }

        .players.compact .row__avatar {
            width: 28px;
            height: 28px;
            margin: 0 6px;
            flex-shrink: 0;
        }

        .players.compact .row__info {
            padding-right: 2px;
        }

        .players.compact .row__name {
            font-size: 9px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .players.compact .row__nick {
            font-size: 8px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            margin-top: 2px;
        }

        /* Скрываем роль и разделители — экономим ширину в 2-колонках */
        .players.compact .divider,
        .players.compact .row__role {
            display: none;
        }

        .players.compact .row__score {
            padding: 0 10px;
            font-size: 15px;
            font-weight: 900;
            flex-shrink: 0;
        }

        /* Плотная компоновка страницы в режиме скриншота */
        .is-screenshotting .results-page {
            padding: 4px 1px 4px !important;
        }

        .is-screenshotting .city {
            font-size: 18px !important;
            margin-bottom: 10px !important;
        }

        .is-screenshotting br {
            display: none;
        }
    </style>
</head>

<body>

    <div id="results-panel" style="background: #000000;">

        <div class="plashka">
            <div class="plashka-circle"></div>
            <div class="results-header">
                <div class="results-upper">{{ $game->date }}</div>
            </div>
            <div class="results-footer">
                <div class="results-title">{{ $winnerIsMafia ? 'Мафия' : 'Мирные' }} - победили</div>
            </div>
        </div>

        @if (!$winnerIsMafia)
            <!-- ========================================================= -->
            <!-- ПОБЕДИЛИ МИРНЫЕ (Сначала Мирные, затем Мафия)             -->
            <!-- ========================================================= -->
            <div class="results-page">
                <div class="city">Мирный город: {{ $peaceResults->count() }}</div>

                <div class="players">
                    @forelse($peaceResults as $rank => $result)
                        @php $acc = $result->account; @endphp
                        <div class="row">
                            <div class="row__avatar">
                                <img src="{{ $acc->avatar ?? '/images/user.svg' }}" alt="avatar">
                            </div>
                            <div class="row__info">
                                <div class="row__name">{{ $acc->name ?? 'Имя не указано' }}</div>
                                <div class="row__nick">{{ $acc->nickname ?? 'Никнейм не указан' }}</div>
                            </div>
                            <div class="divider"></div>
                            <div class="row__role">{{ $result->role ?? 'Мирный' }}</div>
                            <div class="divider"></div>
                            <div class="row__score">{{ $result->total }}</div>
                        </div>
                    @empty
                        <div class="empty">Игроков нет</div>
                    @endforelse
                </div>

                <br>

                <div class="city mafia">Мафия: {{ $mafiaResults->count() }}</div>

                <div class="players mafia">
                    @forelse($mafiaResults as $rank => $result)
                        @php $acc = $result->account; @endphp
                        <div class="row">
                            <div class="row__avatar">
                                <img src="{{ $acc->avatar ?? '/images/user.svg' }}" alt="avatar">
                            </div>
                            <div class="row__info">
                                <div class="row__name">{{ $acc->name ?? 'Имя не указано' }}</div>
                                <div class="row__nick">{{ $acc->nickname ?? 'Никнейм не указан' }}</div>
                            </div>
                            <div class="divider"></div>
                            <div class="row__role">{{ $result->role ?? 'Мафия' }}</div>
                            <div class="divider"></div>
                            <div class="row__score">{{ $result->total }}</div>
                        </div>
                    @empty
                        <div class="empty">Игроков нет</div>
                    @endforelse
                </div>
            </div>
        @else
            <!-- ========================================================= -->
            <!-- ПОБЕДИЛА МАФИЯ (Сначала Мафия, затем Мирные)             -->
            <!-- ========================================================= -->
            <div class="results-page">
                <div class="city mafia">Мафия: {{ $mafiaResults->count() }}</div>

                <div class="players mafia">
                    @forelse($mafiaResults as $rank => $result)
                        @php $acc = $result->account; @endphp
                        <div class="row">
                            <div class="row__avatar">
                                <img src="{{ $acc->avatar ?? '/images/user.svg' }}" alt="avatar">
                            </div>
                            <div class="row__info">
                                <div class="row__name">{{ $acc->name ?? 'Имя не указано' }}</div>
                                <div class="row__nick">{{ $acc->nickname ?? 'Никнейм не указан' }}</div>
                            </div>
                            <div class="divider"></div>
                            <div class="row__role">{{ $result->role ?? 'Мафия' }}</div>
                            <div class="divider"></div>
                            <div class="row__score">{{ $result->total }}</div>
                        </div>
                    @empty
                        <div class="empty">Игроков нет</div>
                    @endforelse
                </div>

                <br>

                <div class="city">Мирный город: {{ $peaceResults->count() }}</div>

                <div class="players">
                    @forelse($peaceResults as $rank => $result)
                        @php $acc = $result->account; @endphp
                        <div class="row">
                            <div class="row__avatar">
                                <img src="{{ $acc->avatar ?? '/images/user.svg' }}" alt="avatar">
                            </div>
                            <div class="row__info">
                                <div class="row__name">{{ $acc->name ?? 'Имя не указано' }}</div>
                                <div class="row__nick">{{ $acc->nickname ?? 'Никнейм не указан' }}</div>
                            </div>
                            <div class="divider"></div>
                            <div class="row__role">{{ $result->role ?? 'Мирный' }}</div>
                            <div class="divider"></div>
                            <div class="row__score">{{ $result->total }}</div>
                        </div>
                    @empty
                        <div class="empty">Игроков нет</div>
                    @endforelse
                </div>
            </div>
        @endif

    </div> <!-- #results-panel -->

    <!-- КНОПКИ УПРАВЛЕНИЯ -->
    <div class="screenshot-controls" data-html2canvas-ignore>
        <button id="btn-take-screenshot" class="btn-screenshot">Опубликовать результаты</button>
        {{-- <a href="/admin/game/skip/{{ $table->id }}" class="btn-screenshot red">Пропустить игру</a> --}}
    </div>

    <!-- ТЕКСТ СТАТУСА -->
    <div id="status-message" data-html2canvas-ignore></div>

    <script src="https://cdn.jsdelivr.net/npm/html-to-image@1.11.11/dist/html-to-image.min.js"></script>

    <script>
        document.getElementById('btn-take-screenshot').addEventListener('click', async () => {
            const btn = document.getElementById('btn-take-screenshot');
            const status = document.getElementById('status-message');
            const element = document.getElementById('results-panel');

            btn.disabled = true;
            status.className = 'status-loading';
            status.textContent = '⏳ Оптимизируем формат и генерируем снимок...';

                // 1. Переключаем панель результатов в мобильный вид
                element.classList.add('is-screenshotting');

                // 2. Двухколоночный компактный режим: применяем когда суммарно > 8 игроков
                const playerSections = element.querySelectorAll('.players');
                const totalRows = element.querySelectorAll('.players .row').length;
                const compactified = [];
                if (totalRows > 8) {
                    playerSections.forEach(section => {
                        if (section.querySelectorAll('.row').length > 0) {
                            section.classList.add('compact');
                            compactified.push(section);
                        }
                    });
                }

                // 3. Пауза для корректного перерасчета стилей браузером
                await new Promise(resolve => setTimeout(resolve, 200));

                // 4. html-to-image: значительно лучше рендерит шрифты и CSS чем html2canvas
                const dataUrl = await htmlToImage.toJpeg(element, {
                    quality: 0.96,
                    pixelRatio: 3,
                    backgroundColor: '#000000',
                    skipFonts: true,      // не пытаться инлайнить внешние шрифты (Google Fonts) — блокируются CORS
                    filter: node => !node.hasAttribute || !node.hasAttribute('data-html2canvas-ignore')
                });

                // 5. Возвращаем исходный вид экрана для администратора
                element.classList.remove('is-screenshotting');
                compactified.forEach(s => s.classList.remove('compact'));

                const formData = new FormData();
                formData.append('screenshot', dataUrl);
                formData.append('table_id', {{ $table->id }});
                formData.append('_token', '{{ csrf_token() }}');

                const response = await fetch('{{ route('admin.game.screenshot.store') }}', {
                    method: 'POST',
                    body: formData
                });

                const result = await response.json();

                if (response.ok) {
                    status.className = 'status-ok';
                    status.textContent = '✅ Скриншот сохранён! Перенаправление...';
                    window.location.href = '/admin/game/edit/{{ $game->id }}';
                } else {
                    status.className = 'status-error';
                    status.textContent = '❌ Ошибка сервера: ' + (result.message || 'Неизвестная ошибка');
                    btn.disabled = false;
                }

        });
    </script>
</body>
</html>
