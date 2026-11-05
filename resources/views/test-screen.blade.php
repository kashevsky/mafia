

@push('styles')
<style>
    .screenshot-area {
        background: #fff;
        padding: 30px;
        border: 1px solid #ddd;
        box-shadow: 0 4px 12px rgba(0,0,0,.08);
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
        max-width: 800px;
        margin: 20px auto;
    }
    .game-header {
        border-bottom: 2px solid #333;
        padding-bottom: 10px;
        margin-bottom: 20px;
    }
    .player-row {
        display: flex;
        justify-content: space-between;
        padding: 8px 0;
        border-bottom: 1px dashed #eee;
    }
    .winner-row {
        font-weight: bold;
        color: #2e7d32;
        font-size: 1.1em;
        background: #f1f8e9;
        padding: 12px;
        border-radius: 6px;
        margin-top: 15px;
    }
    .action-buttons {
        margin-top: 25px;
        text-align: center;
    }
    .btn {
        padding: 10px 20px;
        background: #1867c0;
        color: #fff;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        font-size: 1rem;
    }
    .btn:disabled {
        background: #ccc;
        cursor: not-allowed;
    }
    #status-message {
        margin-top: 15px;
        text-align: center;
        min-height: 1.5em;
    }

    :root {
        --bg-dark: #0f172a;
        --bg-card: #1f2937;
        --border: #374151;
        --text-main: #f1f5f9;
        --text-muted: #9ca3af;
        --danger: #ef4444;
        --danger-hover: #dc2626;
        --success: #16a34a;
        --success-hover: #15803d;
        --secondary: #64748b;
    }

    body {
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
        color: var(--text-main);
        background-color: var(--bg-dark);
    }

    .admin-page-container {
        padding: 24px;
        max-width: 768px;
        margin: 0 auto;
    }

    h2, h3 { color: var(--text-main); }
    h2 { font-size: 1.75rem; font-weight: 700; margin-bottom: 24px; }
    h3 { font-size: 1.25rem; font-weight: 600; margin-top: 20px; margin-bottom: 16px; }

    .card-block {
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: 12px;
        padding: 24px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    }

    .text-lg { font-size: 1.125rem; }
    .mb-5 { margin-bottom: 1.25rem; }

    .flex { display: flex; }
    .flex-col { flex-direction: column; }
    @media (min-width: 640px) { .sm:flex-row { flex-direction: row; } }
    .gap-4 { gap: 1rem; }

    button { cursor: pointer; border: none; outline: none; }

    .btn-danger {
        background-color: var(--danger);
        color: white;
        padding: 0.625rem 1.5rem;
        border-radius: 8px;
        font-weight: 500;
        transition: background-color 0.2s, border-color 0.2s;
    }
    .btn-danger:hover { background-color: var(--danger-hover); }

    .btn-secondary {
        background-color: transparent;
        color: var(--text-muted);
        padding: 0.625rem 1.5rem;
        border: 1px solid var(--border);
        border-radius: 8px;
        font-weight: 500;
        transition: all 0.2s;
    }
    .btn-secondary:hover {
        border-color: var(--text-muted);
        background-color: #334155;
        color: white;
    }

    /* Таблица */
    table { border-collapse: collapse; width: 100%; }
    th, td { padding: 12px 16px; text-align: left; border-bottom: 1px solid var(--border); }
    th { color: var(--text-muted); font-weight: 600; font-size: 0.875rem; text-transform: uppercase; letter-spacing: 0.05em; }
    tr:hover { background-color: #374151; }
    td.font-mono { font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace; }

    /* Модалка */
    .modal-overlay {
        position: fixed;
        inset: 0;
        z-index: 9999;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(0, 0, 0, 0.6);
        backdrop-filter: blur(4px);
    }

    .modal-content {
        width: 100%;
        max-width: 480px;
        background: #2d3748;
        border: 1px solid #475569;
        border-radius: 12px;
        padding: 20px;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.5), 0 8px 10px -6px rgba(0, 0, 0, 0.3);
        transform: scale(1);
        transition: transform 0.2s ease;
    }

    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 16px;
    }

    .modal-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: #f1f5f9;
    }

    .close-btn {
        color: var(--text-muted);
        padding: 4px;
        line-height: 1;
    }
    .close-btn:hover { color: #fff; }

    .modal-body {
        color: var(--text-muted);
        font-size: 0.875rem;
        margin-bottom: 20px;
        line-height: 1.5;
    }

    .modal-footer {
        display: flex;
        justify-content: flex-end;
        gap: 10px;
    }

    .btn-sm {
        padding: 0.5rem 0.75rem;
        font-size: 0.875rem;
        border-radius: 6px;
    }

    .btn-outline {
        background: transparent;
        color: var(--text-muted);
        border: 1px solid #475569;
    }
    .btn-outline:hover {
        background: #334155;
        color: #fff;
        border-color: #64748b;
    }

    [x-cloak] { display: none !important; }
</style>


@endpush

@section('content')
    <div class="screenshot-area" id="game-results-panel">
        <div class="game-header">
            <h2>Результаты игры в мафию</h2>
            <p>Дата: 14.07.2026 · Стол №3 · Статус: завершена</p>
        </div>

        <div>
            <div class="player-row">
                <span>🎭 Колобок (мирный)</span>
                <span>+12 очков</span>
            </div>
            <div class="player-row">
                <span>🎭 Чебурашка (мафия)</span>
                <span>+8 очков</span>
            </div>
            <div class="player-row">
                <span>🎭 Гена (Дон)</span>
                <span>+15 очков</span>
            </div>
            <div class="player-row">
                <span>🎭 Дима (мирный)</span>
                <span>+5 очков</span>
            </div>
        </div>

        <div class="winner-row">
            🏆 Победитель: Гена (дон) — 15 очков
        </div>
    </div>

    <div class="action-buttons">
        <button id="btn-take-screenshot" class="btn">Отправить результаты</button>
    </div>

    <div id="status-message"></div>


    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

    <script>
        document.getElementById('btn-take-screenshot').addEventListener('click', async () => {
            const btn = document.getElementById('btn-take-screenshot');
            const status = document.getElementById('status-message');
            btn.disabled = true;
            status.textContent = 'Делаем скриншот…';

            const element = document.getElementById('game-results-panel');

            try {
                const canvas = await html2canvas(element, {
                    scale: 2, // выше чёткость
                    backgroundColor: '#ffffff',
                    useCORS: true, // если будут внешние картинки
                    logging: false
                });

                const dataUrl = canvas.toDataURL('image/png');

                // Отправляем на сервер
                const formData = new FormData();
                formData.append('screenshot', dataUrl);
                formData.append('_token', '{{ csrf_token() }}');

                const response = await fetch('{{ route('admin.game.screenshot.store') }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        // Не ставим Content-Type вручную — браузер сам поставит multipart/form-data с boundary
                    }
                });

                const result = await response.json();

                if (response.ok) {
                    status.style.color = 'green';
                    status.textContent = '✅ Скриншот сохранён и отправлен! Ссылка: ' + result.url;
                    // Тут можно открыть result.url или показать картинку
                } else {
                    status.style.color = 'red';
                    status.textContent = '❌ Ошибка: ' + (result.message || 'Неизвестная ошибка');
                }
            } catch (err) {
                console.error(err);
                status.style.color = 'red';
                status.textContent = '❌ Ошибка при создании скриншота';
            } finally {
                btn.disabled = false;
            }
        });
    </script>
