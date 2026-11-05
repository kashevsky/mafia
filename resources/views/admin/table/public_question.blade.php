<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="utf-8">
    <title>Результаты игры</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="preconnect" href="https://googleapis.com">
    <link href="https://googleapis.com" rel="stylesheet">
    <script src="https://telegram.org/js/telegram-web-app.js"></script>
    <script>
        const tg = window.Telegram.WebApp;
        tg.ready();
        // Опционально: растянуть на весь экран
        tg.expand();
    </script>
    <style>
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            background: black;
            margin: 0;
            padding: 0;
            border: 1px solid #c3c3c3;

            padding: 15px;
            max-width: 520px;
            margin: 0 auto;
        }

        .alive {
            border: 3px solid #11a700;
        }

        .dead {
            border: 3px solid #ff0000;
        }

        .results-page {
            max-width: 520px;
            margin: 10px auto;


            background: #020202 !important;
        }

        .plashka {
            position: relative;
            text-align: center;
            height: 100px;
            color: #fff;
            border-radius: 38px 0 0 38px;
            max-width: 520px;
            margin: 0 auto;
            overflow: hidden;
        }

        .plashka-bg {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            z-index: 1;
        }

        .plashka-content {
            position: relative;
            z-index: 3;
            height: 100%;
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
            .results-title {
                margin-top: -10px;
            }
        }

        @media (min-width: 381px) and (max-width: 389px) {
            .results-title {
                margin-top: -2px;
            }
        }

        @media (min-width: 390px) and (max-width: 400px) {
            .results-title {
                margin-top: -8px;
            }
        }

        @media (min-width: 401px) and (max-width: 420px) {
            .results-title {
                margin-top: -6px;
            }
        }

        @media (min-width: 421px) and (max-width: 440px) {
            .results-title {
                margin-top: -2px;
            }
        }

        .plashka-circle {
            position: absolute;
            top: -2px;
            left: -11px;
            width: 110px;
            height: 110px;
            z-index: 2;
            border-radius: 100px;
        }

        .plashka-circle img {
            width: 81%;
            height: 100%;
            border-radius: 60px;
        }

        @media (min-width: 768px) {
            .results-title {
                margin-top: 8px;
            }

            .results-upper {
                padding-top: 10px;
            }

            .plashka-circle {
                /* width: 106px;
                height: 106px; */
                left: -11px;
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
            background: rgba(43, 49, 60, 0.73);
            border-radius: 14px;
            height: 64px;
            overflow: hidden;
            position: relative;
            z-index: 2;
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
            color: #ff0000;
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
            min-width: 55px;
            text-align: center;
            color: #ccc;
            padding: 0 18px;
            white-space: nowrap;
            flex-shrink: 0;
        }

        .row__score {
            padding: 0 22px 0 18px;
            font-size: 20px;
            font-weight: 900;
            color: #ff0000;
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

        #status-message {
            margin-top: 15px;
            font-size: 14px;
            text-align: center;
            font-weight: 500;
        }

        .status-ok {
            color: #11a700;
        }

        .status-error {
            color: #ff0000;
        }

        .status-loading {
            color: #9ca3af;
        }

        .is-screenshotting {
            width: 430px !important;
            max-width: 430px !important;
            margin: 0 auto !important;
            padding: 16px 8px !important;
        }

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

        .wrap-players {
            position: relative;
            overflow: hidden;
            margin: -15px;
            padding: 15px;
        }

        .mask {
            opacity: 0.3;
            position: absolute;
        }

        .mask-1 {
            top: 0;
            left: -60px;
            transform: rotate(42deg);
            width: 174px;
            height: 355px;

        }

        .mask-2 {
            width: 204px;
            height: 415px;
            bottom: -80px;
            right: -60px;
            transform: rotate(-14deg);
        }

        .mask-3 {
            width: 286px;
            height: 584px;
            top: -15px;
            left: -103px;
            transform: rotate(12deg);
        }

        .mask-4 {
            width: 143px;
            height: 292px;
            bottom: -40px;
            right: -50px;
            transform: rotate(-120deg);
        }
    </style>
</head>

<body>
    <div id="results-panel" style="background: #000000;">

        <div class="plashka">
            <!-- Безопасный вывод декораций через теги img -->
            <img src="{{ asset('images/plashka.svg') }}" class="plashka-bg" alt="">
            <div class="plashka-circle">
                <img src="{{ $game->avatar ?? '/images/elipse.svg' }}" alt="">
            </div>

            <div class="plashka-content">
                <div class="results-header">
                    <div class="results-upper">{{ \Carbon\Carbon::parse($game->date)->format('d.m.Y') }}</div>
                </div>
                <div class="results-footer">
                    <div class="results-title">{{ $winnerIsMafia ? 'Мафия' : 'Мирные' }} - победили</div>
                </div>
            </div>
        </div>

        @if (!$winnerIsMafia)
            <!-- ПОБЕДИЛИ МИРНЫЕ -->
            <div class="results-page">
                <div class="city">Мирный город: {{ $peaceResults->count() }}</div>
                <div class="wrap-players">
                    <div class="players">
                        <img class="mask mask-1" src="{{ asset('images/mask-1.png') }}" alt="1">
                        <img class="mask mask-2" src="{{ asset('images/mask-2.png') }}" alt="2">
                        @forelse($peaceResults as $rank => $result)
                            @php $acc = $result->account; @endphp
                            <div class="row">
                                <div class="row__avatar {{ $acc->is_alive ? 'alive' : 'dead' }}">
                                    <img src="{{ $acc && $acc->avatar ? asset($acc->avatar) : asset('/def.svg') }}"
                                        onerror="this.onerror=null; this.src='{{ asset('/def.svg') }}';" alt="avatar">
                                </div>
                                <div class="row__info">
                                    <div class="row__name">{{ $acc->nickname ?? 'Никнейм не указан' }}</div>
                                    <div class="row__nick">{{ $acc->name ?? 'Имя не указано' }}</div>
                                </div>
                                <div class="divider"></div>
                                <div class="row__role">
                                    @php
                                        $currentRole = $acc->role ?? 'Мирный';

                                        if ($currentRole === 'Фредди') {
                                            $displayRole = 'Мститель';
                                        } elseif ($currentRole === 'Каратель') {
                                            $displayRole = 'Ферзь';
                                        } else {
                                            $displayRole = $currentRole;
                                        }
                                    @endphp

                                    {{ $displayRole }}
                                </div>

                                <div class="divider"></div>
                                <div class="row__score">{{ $result->total }}</div>
                            </div>
                        @empty
                            <div class="empty">Игроков нет</div>
                        @endforelse
                    </div>
                </div>
                <br>
                <div class="city mafia">Мафия: {{ $mafiaResults->count() }}</div>
                <div class="wrap-players">
                    <div class="players mafia">
                        <img class="mask mask-3" src="{{ asset('images/mask-3.png') }}" alt="3">
                        <img class="mask mask-4" src="{{ asset('images/mask-1.png') }}" alt="4">
                        @forelse($mafiaResults as $rank => $result)
                            @php $acc = $result->account; @endphp
                            <div class="row">
                                <div class="row__avatar {{ $acc->is_alive ? 'alive' : 'dead' }}">
                                    <img src="{{ $acc && $acc->avatar ? asset($acc->avatar) : asset('/def.svg') }}"
                                        onerror="this.onerror=null; this.src='{{ asset('/def.svg') }}';"
                                        alt="avatar">
                                </div>
                                <div class="row__info">
                                    <div class="row__name">{{ $acc->nickname ?? 'Никнейм не указан' }}</div>
                                    <div class="row__nick">{{ $acc->name ?? 'Имя не указано' }}</div>
                                </div>
                                <div class="divider"></div>
                                <div class="row__role">
                                    {{ $acc->role === 'Фредди' ? 'Мститель' : ($acc->role === 'Каратель' ? 'Ферзь' : $acc->role ?? 'Мафия') }}
                                </div>

                                <div class="divider"></div>
                                <div class="row__score">{{ $result->total }}</div>
                            </div>
                        @empty
                            <div class="empty">Игроков нет</div>
                        @endforelse
                    </div>
                </div>
            </div>
        @else
            <!-- ПОБЕДИЛА МАФИЯ -->
            <div class="results-page">
                <div class="city mafia">Мафия: {{ $mafiaResults->count() }}</div>
                <div class="wrap-players">
                    <div class="players mafia">
                        <img class="mask mask-1" src="{{ asset('images/mask-1.png') }}" alt="1">
                        <img class="mask mask-2" src="{{ asset('images/mask-2.png') }}" alt="2">
                        @forelse($mafiaResults as $rank => $result)
                            @php $acc = $result->account; @endphp

                            {{-- Добавляем условие: если это Оборотень И он победил (например, статус стола равен 3) --}}
                            <div class="row"
                                @if ($acc && $acc->role == 'Оборотень' && $table->is_mafia_win == 2) style="border: 4px solid #11a700;" @endif>

                                <div class="row__avatar {{ $acc && $acc->is_alive ? 'alive' : 'dead' }}">
                                    <img src="{{ $acc && $acc->avatar ? asset($acc->avatar) : asset('/def.svg') }}"
                                        onerror="this.onerror=null; this.src='{{ asset('/def.svg') }}';"
                                        alt="avatar">
                                </div>

                                <div class="row__info">
                                    <div class="row__name">{{ $acc->nickname ?? 'Никнейм не указан' }}</div>
                                    <div class="row__nick">{{ $acc->name ?? 'Имя не указано' }}</div>
                                </div>

                                <div class="divider"></div>
                                @php
                                    $currentRole = $acc->role ?? 'Мафия';
                                    if ($currentRole === 'Фредди') {
                                        $displayRole = 'Мститель';
                                    } elseif ($currentRole === 'Каратель') {
                                        $displayRole = 'Ферзь';
                                    } else {
                                        $displayRole = $currentRole;
                                    }
                                @endphp
                                <div class="row__role">{{ $displayRole }}</div>


                                <div class="divider"></div>
                                <div class="row__score">{{ $result->total }}</div>
                            </div>
                        @empty
                            <div class="empty">Игроков нет</div>
                        @endforelse

                    </div>
                </div>
                <br>
                <div class="city">Мирный город: {{ $peaceResults->count() }}</div>
                <div class="wrap-players">
                    <div class="players">
                        <img class="mask mask-3" src="{{ asset('images/mask-3.png') }}" alt="4">
                        <img class="mask mask-4" src="{{ asset('images/mask-1.png') }}" alt="1">
                        @forelse($peaceResults as $rank => $result)
                            @php $acc = $result->account; @endphp
                            <div class="row">
                                <div class="row__avatar {{ $acc->is_alive ? 'alive' : 'dead' }}">
                                    <img src="{{ $acc && $acc->avatar ? asset($acc->avatar) : asset('/def.svg') }}"
                                        onerror="this.onerror=null; this.src='{{ asset('/def.svg') }}';"
                                        alt="avatar">
                                </div>
                                <div class="row__info">
                                    <div class="row__name">{{ $acc->nickname ?? 'Никнейм не указан' }}</div>
                                    <div class="row__nick">{{ $acc->name ?? 'Имя не указано' }}</div>
                                </div>
                                <div class="divider"></div>
                                <div class="row__role">
                                    {{ $acc->role === 'Фредди' ? 'Мститель' : ($acc->role === 'Каратель' ? 'Ферзь' : $acc->role ?? 'Мирный') }}
                                </div>

                                <div class="divider"></div>
                                <div class="row__score">{{ $result->total }}</div>
                            </div>
                        @empty
                            <div class="empty">Игроков нет</div>
                        @endforelse
                    </div>
                </div>
            </div>
        @endif
    </div>

    @if (empty($isSnapshot) || !$isSnapshot)
        <div class="screenshot-controls" data-html2canvas-ignore>
            <button id="btn-take-screenshot" class="btn-screenshot">Опубликовать результаты</button>
        </div>
    @endif
    <div id="status-message" data-html2canvas-ignore></div>

    <script src="https://cdn.jsdelivr.net/npm/html2canvas@1.4.1/dist/html2canvas.min.js"></script>

    <script>
        document.getElementById('btn-take-screenshot').addEventListener('click', async () => {
            const btn = document.getElementById('btn-take-screenshot');
            const status = document.getElementById('status-message');
            const element = document.getElementById('results-panel');

            btn.disabled = true;
            status.className = 'status-loading';
            status.textContent = '⏳ Оптимизируем формат и генерируем снимок...';
            element.classList.add('is-screenshotting');

            const playerSections = element.querySelectorAll('.players');
            const totalRows = element.querySelectorAll('.players .row').length;
            const compactified = [];



            // Задержка, чтобы браузер успел применить CSS-классы
            await new Promise(resolve => setTimeout(resolve, 300));

            try {
                const canvas = await html2canvas(element, {
                    scale: window.devicePixelRatio * 3,
                    useCORS: true,
                    allowTaint: false,
                    backgroundColor: '#000000',
                    logging: false,
                    ignoreElements: el => el.hasAttribute('data-html2canvas-ignore')
                });
                const dataUrl = canvas.toDataURL('image/png');

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
            } catch (err) {
                console.error(err);
                status.className = 'status-error';
                status.textContent = '❌ Сбой сборщика изображения.';
                element.classList.remove('is-screenshotting');
                compactified.forEach(s => s.classList.remove('compact'));
                btn.disabled = false;
            }
        });
    </script>
</body>

</html>
