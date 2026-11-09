<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="utf-8">
    <title>Результаты игры</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link
        href="https://fonts.googleapis.com/css2?family=Cinzel:wght@700;900&family=Inter:wght@400;500;600;700&display=swap"
        rel="stylesheet">
</head>

<body>
    <style>
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            background: black;
        }

        .results-page {
            max-width: 520px;
            margin: 0 auto;
            padding: 12px 1px 8px;
            border: 1px solid #c3c3c3;
        }


        .city {
            text-align: center;
            font-size: 23px;
            font-weight: 700;
            color: #fff;
            margin-bottom: 22px;
        }

        .players {

            margin-top: 10px;
            background: #030303;

            display: flex;
            flex-direction: column;

            gap: 8px;
        }

        .mafia {
            border-color: #ff0000;
            color: #ff0000;
        }

        /* ═══ PLAYER ROW ═══ */
        .row {
            display: flex;
            align-items: center;
            background: #2B313C;
            border-radius: 14px;
            height: 64px;
            overflow: hidden;
        }

        /* circle avatar */
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

        /* name + nick block */
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
            font-size: 14px;
            font-weight: 500;
            color: #ff0000;
            margin-top: 4px;
        }

        /* vertical dividers */
        .divider {
            width: 1px;
            height: 46px;
            background: #3e3e3e;
            flex-shrink: 0;
        }

        /* role */
        .row__role {
            font-size: 10px;
            color: #ccc;
            padding: 0 18px;
            white-space: nowrap;
            flex-shrink: 0;
        }

        /* coin */
        .row__coin {
            padding: 0 12px;
            flex-shrink: 0;
            display: flex;
            align-items: center;
        }

        .coin {
            width: 18px;
            height: 18px;
            border-radius: 50%;

            box-shadow:
                inset 0 2px 4px rgba(255, 240, 140, 0.55),
                0 3px 8px rgba(0, 0, 0, 0.55);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .coin svg {
            width: 18px;
            height: 18px;
        }

        /* score */
        .row__score {
            padding: 0 22px 0 18px;
            font-size: 20px;
            font-weight: 900;
            color: #ff0000;
            flex-shrink: 0;
        }

        /* ── Grid ── */
        .grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 8px;
            background: #000000;
        }

        /* ── Card ── */
        .card {
            background: #2c2f3a;
            border-radius: 18px;
            padding: 15px 10px 15px 10px;
            display: flex;
            flex-direction: column;
        }

        /* top: rank + avatar */
        .card__top {
            position: relative;
            display: flex;
            justify-content: center;
            min-height: 58px;
            margin-bottom: 18px;
        }

        .card__rank {
            position: absolute;
            top: 0;
            left: 0;
            font-size: 20px;
            font-weight: 900;
            color: #fff;
            line-height: 1;
        }

        .row__rank_num {

            padding-left: 15px;
            font-size: 20px;
            font-weight: 900;
            color: #fff;
            line-height: 1;
        }

        .card__avatar {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            overflow: hidden;
            flex-shrink: 0;
            align-self: flex-end;
        }

        .card__avatar img {
            display: block;
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* name + nick */
        .card__body {
            text-align: center;
            flex: 1;
            margin-bottom: 4px;
        }

        .card__name {
            font-size: 16px;
            font-weight: 400;
            color: #fff;
            line-height: 1.3;
            margin-bottom: 8px;
        }

        .card__nick {
            font-size: 12px;
            font-weight: 200;
            color: #f3f3f3;
            margin-bottom: 4px;
        }

        /* bottom: coin + divider + score */
        .card__footer {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
        }

        .coin {
            flex-shrink: 0;
            width: 24px;
            height: 24px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .coin svg {
            width: 24px;
            height: 24px;
        }

        .vdiv {
            width: 1px;
            height: 44px;
            background: #484b57;
            flex-shrink: 0;
        }

        .card__score {
            font-size: 18px;
            font-weight: 900;
            color: #ff0000;
            line-height: 1;
        }

        .upper-title {
            color: #ff0000;
            font-size: 20px;
            font-weight: 900;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 10px;
            margin: 20px 0 30px 0;
        }

        .thanks {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 10px;
            margin: 30px 0 40px 0;

        }
    </style>
    <div class="results-page">


        <div id="winners-panel" style="background: #000000; padding: 12px 10px 20px 10px;">
            <div style="text-align: center">
                         <span style="color: #fff; font-size: 20px; font-weight: 900;">{{ \Carbon\Carbon::parse($game->date)->format('d.m.Y') }}</span>
            </div>
            <div class="upper-title">
                <img src="/images/rating.svg" alt="rating">

                <span>Рейтинг по итогам всех игр</span>
            </div>

            <!-- ── Players Top 3 ── -->
            <div class="grid" bis_skin_checked="1">
                @php
                    $top3 = $winners->take(3);
                    $rest = $winners->skip(3);
                @endphp

                @if ($top3->isNotEmpty())
                    @foreach ($top3 as $i => $winner)
                        @php
                            $acc = $winner->account;
                            $place = $i + 1;
                        @endphp
                        <div class="card" bis_skin_checked="1">
                            <div class="card__top" bis_skin_checked="1">
                                <div class="card__rank">{{ $place }}</div>
                                <div class="card__avatar" bis_skin_checked="1">
                                    @if (!empty($acc->avatar))
                                        <img src="{{ $acc->avatar }}" alt="avatar">
                                    @else
                                    <img src="/def.svg" alt="avatar">
                                    @endif
                                </div>
                            </div>
                            <div class="card__body" bis_skin_checked="1">

                                <div class="card__name" bis_skin_checked="1">{{ $acc->nickname ?? 'Никнейм не указан' }}</div>
                              {{-- <div class="card__nick" bis_skin_checked="1">{{ $acc->role === 'Фредди' ? 'Мститель' : ($acc->role === 'Каратель' ? 'Ферзь' : ($acc->role ?? 'Никнейм не указан')) }}</div> --}}


                                </div>
                            <div class="card__footer" bis_skin_checked="1">
                                {{-- <div class="coin" bis_skin_checked="1">
                                    <img src="/images/ach.svg" alt="coin">
                                </div>
                                <div class="vdiv" bis_skin_checked="1"></div> --}}
                                <div class="card__score" bis_skin_checked="1">{{ $winner->total }}</div>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>

            <!-- ── Остальные игроки (4+) ── -->
            <div class="players">
                @foreach ($rest as $i => $winner)
                    @php
                        $acc = $winner->account;
                        $place = $i + 4;
                    @endphp
                    @if ($place > 10)
                        @break
                    @endif
                    <div class="row">
                        <div class="row__rank_num">{{ $i + 1}}</div>
                        <div class="row__avatar">
                            @if (!empty($acc->avatar))
                                <img src="{{ $acc->avatar ?? '/def.svg' }}" alt="avatar">
                            @else
                                <img src="/def.svg" alt="avatar">
                            @endif
                        </div>

                        <div class="row__info">
                          <div class="row__nick">{{ $acc->nickname ?? 'Никнейм не указан' }}</div>
                            <div class="row__name">{{ $acc->name ?? 'Имя не указано' }}</div>

                        </div>

                        <div class="divider"></div>
                 {{-- <div class="row__role">{{ $acc->role === 'Фредди' ? 'Мститель' : ($acc->role === 'Каратель' ? 'Ферзь' : $acc->role) }}</div> --}}

                        <div class="divider"></div>

                        {{-- <div class="row__coin">
                            <div class="coin">
                                <img src="/images/ach.svg" alt="coin">
                            </div>
                        </div> --}}

                        <div class="divider"></div>
                        <div class="row__score">{{ $winner->total }}</div>
                    </div>
                @endforeach
            </div>

            <div class="thanks">
                <img src="/images/thanks.svg" alt="rating">
            </div>

        </div>
        <!-- КОНЕЦ ОБЛАСТИ СКРИНШОТА -->


        <!-- СТИЛИ ДЛЯ КНОПКИ И СТАТУСА (Вынесены наружу) -->
        <style>
            .btn-screenshot {
                background: #ff0000;
                color: #fff;
                border: none;
                padding: 12px 24px;
                border-radius: 8px;
                cursor: pointer;
                margin: 24px auto 12px;
                display: block;
                font-weight: 700;
                font-size: 0.95rem;
                transition: opacity 0.15s;
            }

            .btn-screenshot:hover {
                opacity: 0.9;
            }

            .btn-screenshot:disabled {
                opacity: 0.5;
                cursor: not-allowed;
            }

            #status-message {
                text-align: center;
                margin-top: 10px;
                font-size: 0.85rem;
                min-height: 1.2em;
            }

            .status-ok {
                color: #00ff00 !important;
                font-weight: 600;
            }

            .status-error {
                color: #ff3333 !important;
                font-weight: 600;
            }
        </style>


        <div class="screenshot-controls">
            <button id="btn-take-screenshot" class="btn-screenshot">📸 Сделать скриншот результатов</button>
            <div id="status-message"></div>
        </div>

    </div>


    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>


    <script>
        document.getElementById('btn-take-screenshot').addEventListener('click', async () => {
            const btn = document.getElementById('btn-take-screenshot');
            const status = document.getElementById('status-message');

            btn.disabled = true;
            status.textContent = 'Делаем скриншот…';
            status.className = '';

            // Берём контейнер, внутри которого лежат все данные игроков
            const element = document.getElementById('winners-panel');

            try {
                const canvas = await html2canvas(element, {
                    scale: 2,
                    backgroundColor: '#000000', // Изменено под новый чёрный цвет вашей страницы
                    useCORS: true,
                    logging: false,
                    width: element.offsetWidth,
                    height: element.offsetHeight
                });

                const dataUrl = canvas.toDataURL('image/png', 0.8);

                // Формируем данные для отправки на сервер
                const formData = new FormData();
                formData.append('screenshot', dataUrl);
                formData.append('_token', '{{ csrf_token() }}');
                formData.append('game_id', '{{ $game->id }}');

                const response = await fetch('{{ route('admin.game.screenshot_finish.store') }}', {
                    method: 'POST',
                    body: formData
                });

                const result = await response.json();

                if (response.ok) {
                    status.className = 'status-ok';
                    status.textContent = '✅ Скриншот сохранён! Ссылка: ' + result.url;
                    window.location.href = '/admin';
                } else {
                    status.className = 'status-error';
                    status.textContent = '❌ Ошибка: ' + (result.message || 'Неизвестная ошибка');
                }
            } catch (err) {
                console.error(err);
                status.className = 'status-error';
                status.textContent = '❌ Ошибка при создании скриншота';
            } finally {
                btn.disabled = false;
            }
        });
    </script>
</body>

</html>
