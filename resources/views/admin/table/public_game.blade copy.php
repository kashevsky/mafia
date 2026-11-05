{{-- game-winners.blade.php --}}
{{-- Переменные: $winners (Collection of GameWinner) --}}
{{-- winner->id, winner->total, winner->position --}}
{{-- winner->account->name, ->nickname, ->role, ->avatar, ->rating, ->number --}}

<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="utf-8">
    <title>Победители</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link
        href="https://fonts.googleapis.com/css2?family=Cinzel:wght@700;900&family=Inter:wght@400;500;600;700&display=swap"
        rel="stylesheet">
</head>

<body>

    <style>
        :root {
            --bg-dark: #0f172a;
            --bg-card: #1f2937;
            --bg-row-alt: #253042;
            --border: #374151;
            --text-main: #f1f5f9;
            --text-muted: #9ca3af;
            --secondary: #64748b;
            --gold: #d4a849;
            --gold-dim: rgba(212, 168, 73, 0.12);
            --gold-border: rgba(212, 168, 73, 0.35);
            --silver: #94a3b8;
            --bronze: #c2845a;
        }

        *,
        *::before,
        *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            background-color: var(--bg-dark);
            color: var(--text-main);
            min-height: 100vh;
        }

        .winners-page {
            max-width: 560px;
            margin: 0 auto;
            padding: 32px 16px 48px;
        }

        /* ── Decorative heading ── */
        .page-title {
            font-family: 'Cinzel', serif;
            font-size: clamp(1.5rem, 6vw, 2.1rem);
            font-weight: 900;
            letter-spacing: 0.08em;
            text-align: center;
            color: var(--gold);
            text-shadow: 0 0 28px rgba(212, 168, 73, 0.45);
            margin-bottom: 4px;
            line-height: 1.1;
        }

        .page-subtitle {
            text-align: center;
            font-size: 0.85rem;
            color: var(--text-muted);
            letter-spacing: 0.06em;
            text-transform: uppercase;
            margin-bottom: 28px;
        }

        /* ── Top-3 podium cards ── */
        .podium {
            display: flex;
            align-items: flex-end;
            justify-content: center;
            gap: 10px;
            margin-bottom: 28px;
        }

        .podium-card {
            flex: 1;
            max-width: 150px;
            display: flex;
            flex-direction: column;
            align-items: center;
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 16px 10px 14px;
            text-align: center;
            position: relative;
        }

        .podium-card.place-1 {
            border-color: var(--gold-border);
            background: var(--gold-dim);
            order: 2;
            padding-top: 20px;
        }

        .podium-card.place-2 {
            border-color: rgba(148, 163, 184, 0.3);
            order: 1;
        }

        .podium-card.place-3 {
            border-color: rgba(194, 132, 90, 0.3);
            order: 3;
        }

        .podium-medal {
            font-size: 1.4rem;
            margin-bottom: 8px;
            line-height: 1;
        }

        .podium-avatar {
            width: 52px;
            height: 52px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid var(--border);
            margin-bottom: 8px;
        }

        .podium-avatar-placeholder {
            width: 52px;
            height: 52px;
            border-radius: 50%;
            background: var(--border);
            border: 2px solid var(--secondary);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.85rem;
            font-weight: 700;
            color: var(--text-muted);
            margin-bottom: 8px;
            flex-shrink: 0;
        }

        .podium-card.place-1 .podium-avatar,
        .podium-card.place-1 .podium-avatar-placeholder {
            width: 60px;
            height: 60px;
            border-color: var(--gold);
        }

        .podium-name {
            font-weight: 700;
            font-size: 0.85rem;
            color: var(--text-main);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 100%;
            margin-bottom: 2px;
        }

        .podium-nickname {
            font-size: 0.72rem;
            color: var(--secondary);
            margin-bottom: 4px;
        }

        .podium-role {
            font-size: 0.7rem;
            color: var(--text-muted);
            border: 1px solid var(--border);
            border-radius: 999px;
            padding: 1px 7px;
            margin-bottom: 8px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 100%;
        }

        .podium-score {
            font-family: 'Inter', monospace;
            font-weight: 700;
            font-size: 1rem;
            color: var(--gold);
        }

        .podium-card.place-2 .podium-score {
            color: var(--silver);
        }

        .podium-card.place-3 .podium-score {
            color: var(--bronze);
        }

        /* ── Rest of the list ── */
        .list-card {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.2);
        }

        .list-header {
            display: flex;
            padding: 10px 16px;
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: var(--secondary);
            border-bottom: 1px solid var(--border);
        }

        .list-header .col-rank {
            width: 36px;
        }

        .list-header .col-info {
            flex: 1;
        }

        .list-header .col-role {
            width: 110px;
            text-align: center;
        }

        .list-header .col-score {
            width: 56px;
            text-align: right;
        }

        .list-row {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 9px 16px;
            background: var(--bg-card);
            border-bottom: 1px solid rgba(55, 65, 81, 0.5);
            transition: background 0.15s;
        }

        .list-row:nth-child(even) {
            background: var(--bg-row-alt);
        }

        .list-row:last-child {
            border-bottom: none;
        }

        .list-rank {
            width: 26px;
            font-size: 0.8rem;
            color: var(--secondary);
            font-variant-numeric: tabular-nums;
            flex-shrink: 0;
            text-align: right;
        }

        .list-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            object-fit: cover;
            border: 1px solid var(--border);
            flex-shrink: 0;
        }

        .list-avatar-placeholder {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: var(--border);
            border: 1px solid var(--secondary);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.7rem;
            font-weight: 700;
            color: var(--text-muted);
            flex-shrink: 0;
        }

        .list-info {
            flex: 1;
            min-width: 0;
        }

        .list-name {
            font-weight: 600;
            font-size: 0.9rem;
            color: var(--text-main);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .list-nickname {
            font-size: 0.75rem;
            color: var(--secondary);
        }

        .list-role {
            width: 110px;
            flex-shrink: 0;
            font-size: 0.72rem;
            color: var(--text-muted);
            text-align: center;
            border: 1px solid var(--border);
            border-radius: 999px;
            padding: 2px 8px;
            background: rgba(55, 65, 81, 0.3);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .list-score {
            width: 56px;
            text-align: right;
            font-family: 'Inter', monospace;
            font-weight: 700;
            font-size: 0.95rem;
            color: var(--gold);
            flex-shrink: 0;
        }

        .empty {
            padding: 24px;
            text-align: center;
            color: var(--text-muted);
            font-size: 0.9rem;
        }

        /* ── Screenshot block (NEW) ── */
        .screenshot-controls {
            margin-top: 32px;
            text-align: center;
        }

        .btn-screenshot {
            display: inline-block;
            padding: 12px 24px;
            background: linear-gradient(135deg, var(--gold), #b38f3e);
            color: #0f172a;
            font-weight: 700;
            font-size: 0.95rem;
            border-radius: 8px;
            text-decoration: none;
            box-shadow: 0 4px 12px rgba(212, 168, 73, 0.3);
            transition: transform 0.15s, box-shadow 0.15s;
            cursor: pointer;
        }

        .btn-screenshot:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 16px rgba(212, 168, 73, 0.4);
        }

        .btn-screenshot:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }

        #status-message {
            margin-top: 14px;
            font-size: 0.85rem;
            min-height: 1.2em;
        }

        .status-ok {
            color: var(--success) !important;
        }

        .status-error {
            color: var(--danger) !important;
        }
    </style>

    <div class="winners-page">

        <h1 class="page-title">Победители</h1>
        <p class="page-subtitle">Общий зачёт игры</p>

        <!-- Область для скриншота: всё, что внутри этого блока, будет захвачено -->
        <div id="winners-panel" style="background: #0f172a;">

            @if ($winners->isEmpty())
                <div class="empty">Победители не определены</div>
            @else
                {{-- ── TOP-3 PODIUM ── --}}
                @php
                    $top3 = $winners->take(3);
                    $rest = $winners->skip(3);
                @endphp

                @if ($top3->isNotEmpty())
                    <div class="podium">
                        @foreach ($top3 as $i => $winner)
                            @php
                                $acc = $winner->account;
                                $place = $i + 1;
                                $medal = match ($place) {
                                    1 => '🥇',
                                    2 => '🥈',
                                    3 => '🥉',
                                    default => '',
                                };
                                $cls = 'place-' . $place;
                            @endphp
                            <div class="podium-card {{ $cls }}">
                                <div class="podium-medal">{{ $medal }}</div>

                                @if (!empty($acc->avatar))
                                    <img class="podium-avatar" src="{{ $acc->avatar }}" alt="{{ $acc->name }}">
                                @else
                                    <div class="podium-avatar-placeholder">{{ mb_substr($acc->name, 0, 2) }}</div>
                                @endif

                                <div class="podium-name">{{ $acc->name }}</div>
                                @if (!empty($acc->nickname))
                                    <div class="podium-nickname">({{ $acc->nickname }})</div>
                                @endif
                                <div class="podium-role">{{ $acc->role ?? 'Без роли' }}</div>
                                <div class="podium-score">{{ $winner->total }}</div>
                            </div>
                        @endforeach
                    </div>
                @endif

                {{-- ── ОСТАЛЬНЫЕ (4+) ── --}}
                @if ($rest->isNotEmpty())
                    <div class="list-card">
                        <div class="list-header">
                            <span class="col-rank">#</span>
                            <span class="col-info" style="padding-left: 46px;">Игрок</span>
                            <span class="col-role">Роль</span>
                            <span class="col-score">Очки</span>
                        </div>

                        @foreach ($rest as $i => $winner)
                            @php
                                $acc = $winner->account;
                                $place = $i + 4;
                            @endphp
                            <div class="list-row">
                                <span class="list-rank">{{ $place }}</span>

                                @if (!empty($acc->avatar))
                                    <img class="list-avatar" src="{{ $acc->avatar }}" alt="{{ $acc->name }}">
                                @else
                                    <div class="list-avatar-placeholder">{{ mb_substr($acc->name, 0, 2) }}</div>
                                @endif

                                <div class="list-info">
                                    <div class="list-name">{{ $acc->name }}</div>
                                    @if (!empty($acc->nickname))
                                        <div class="list-nickname">({{ $acc->nickname }})</div>
                                    @endif
                                </div>

                                <span class="list-role">{{ $acc->role ?? 'Без роли' }}</span>
                                <span class="list-score">{{ $winner->total }}</span>
                            </div>
                        @endforeach
                    </div>
                @endif
            @endif
        </div> <!-- #winners-panel -->

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

            const element = document.getElementById('winners-panel');

            try {
                const canvas = await html2canvas(element, {
                    scale: 2,
                    backgroundColor: '#0f172a', // под цвет фона страницы
                    useCORS: true,
                    logging: false,
                    width: element.offsetWidth,
                    height: element.offsetHeight
                });

                const dataUrl = canvas.toDataURL('image/png');

                // Отправляем на сервер
                const formData = new FormData();
                formData.append('screenshot', dataUrl);
                formData.append('_token', '{{ csrf_token() }}');
                formData.append('game_id', '{{ $game->id }}');

                const response = await fetch('{{ route('admin.game.screenshot_finish.store') }}', {
                    method: 'POST',
                    body: formData
                    // Content-Type не ставим вручную — браузер сам поставит multipart/form-data
                });

                const result = await response.json();

                if (response.ok) {
                    status.className = 'status-ok';
                    status.textContent = '✅ Скриншот сохранён! Ссылка: ' + result.url;
                    window.location.href = '/admin';
                    // При желании можно открыть ссылку: window.open(result.url, '_blank');
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
