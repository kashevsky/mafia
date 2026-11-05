<header class="header">
    <div class="header__inner">
        <div class="header__item">
            Игроков: <span x-text="`${users.length}`"></span>
        </div>
        <div class="header__item">
            Живых: <span class="--green" x-text="`${aliveUsers.length}`"></span>
        </div>
        <div class="header__item">
            Убитых: <span class="--red" x-text="`${users.length - aliveUsers.length}`"></span>
        </div>
        <div class="header__item">
            Cчетчик дней: <span x-text="`${countDays}`"></span>
        </div>
        <div class="header__item">
            Cчетчик ночей: <span x-text="`${countNights}`"></span>
        </div>
        <div class="header__item">
            Фаза суток: <span x-text="`${whatDay}`"></span>
        </div>
        <div class="header-btns">
            <!-- Кнопка сброса игры -->
            <button class="btn btn-primary" onclick="openResetGameModal()">
                Сбросить игру
            </button>

            <!-- Скорректированное модальное окно (изначально скрыто через display: none) -->
            <div id="resetGameModal" class="game-modal-overlay" style="display: none;">
                <div class="game-modal-content">
                    <div class="game-modal-header">
                        <h3><i class="fas fa-exclamation-triangle"
                                style="color: #e74c3c; margin-right: 8px;"></i>Подтверждение</h3>
                        <button class="game-modal-close" onclick="closeResetGameModal()">&times;</button>
                    </div>
                    <div class="game-modal-body">
                        <p>Вы уверены, что хотите сбросить игру? Все текущие игровые данные таблицы будут безвозвратно
                            удалены.</p>
                    </div>
                    <div class="game-modal-footer">
                        <button class="btn-game-secondary" onclick="closeResetGameModal()">Отмена</button>
                        <button id="btnConfirmReset" class="btn-game-danger" onclick="confirmResetGame()">
                            <span id="btnResetText">Да, сбросить</span>
                            <span id="btnResetSpinner" style="display: none;"><i
                                    class="fas fa-spinner fa-spin"></i></span>
                        </button>
                    </div>
                </div>
            </div>

            <style>
                /* ИСПРАВЛЕНО: Убран display: flex !important, чтобы окно не открывалось само */
                .game-modal-overlay {
                    position: fixed !important;
                    top: 0 !important;
                    left: 0 !important;
                    width: 100vw !important;
                    height: 100vh !important;
                    background: rgba(10, 15, 24, 0.9) !important;
                    align-items: center !important;
                    justify-content: center !important;
                    z-index: 999999 !important;
                    backdrop-filter: blur(5px) !important;
                }

                .game-modal-content {
                    position: relative !important;
                    background-color: #1a2436 !important;
                    border: 1px solid #2c3e50 !important;
                    border-radius: 12px !important;
                    width: 90% !important;
                    max-width: 440px !important;
                    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.6) !important;
                    color: #f5f6fa !important;
                    font-family: system-ui, -apple-system, sans-serif;
                    overflow: hidden !important;
                    animation: fadeInModal 0.2s ease-out !important;
                }

                @keyframes fadeInModal {
                    from {
                        transform: scale(0.92);
                        opacity: 0;
                    }

                    to {
                        transform: scale(1);
                        opacity: 1;
                    }
                }

                .game-modal-header {
                    padding: 16px 20px !important;
                    background-color: #131b26 !important;
                    border-bottom: 1px solid #233145 !important;
                    display: flex !important;
                    justify-content: space-between !important;
                    align-items: center !important;
                }

                .game-modal-header h3 {
                    margin: 0 !important;
                    font-size: 16px !important;
                    font-weight: 600 !important;
                    text-transform: uppercase !important;
                    letter-spacing: 0.5px !important;
                }

                .game-modal-close {
                    background: none !important;
                    border: none !important;
                    color: #7f8c8d !important;
                    font-size: 24px !important;
                    cursor: pointer !important;
                    padding: 0 !important;
                }

                .game-modal-close:hover {
                    color: #fff !important;
                }

                .game-modal-body {
                    padding: 24px 20px !important;
                    font-size: 15px !important;
                    line-height: 1.5 !important;
                    color: #cbd5e1 !important;
                    text-align: center !important;
                }

                .game-modal-footer {
                    padding: 14px 20px !important;
                    background-color: #131b26 !important;
                    border-top: 1px solid #233145 !important;
                    display: flex !important;
                    justify-content: center !important;
                    gap: 12px !important;
                }

                .btn-game-secondary {
                    background-color: #2c3e50 !important;
                    color: #fff !important;
                    border: 1px solid #34495e !important;
                    padding: 10px 20px !important;
                    border-radius: 6px !important;
                    cursor: pointer !important;
                    font-weight: 500 !important;
                    min-width: 100px !important;
                }

                .btn-game-secondary:hover {
                    background-color: #34495e !important;
                }

                .btn-game-danger {
                    background-color: #c0392b !important;
                    color: #fff !important;
                    border: 1px solid #d63031 !important;
                    padding: 10px 20px !important;
                    border-radius: 6px !important;
                    cursor: pointer !important;
                    font-weight: 500 !important;
                    min-width: 120px !important;
                }

                .btn-game-danger:hover {
                    background-color: #e74c3c !important;
                }
            </style>

            <script>
                function openResetGameModal() {
                    document.getElementById('resetGameModal').style.setProperty('display', 'flex', 'important');
                }


                function closeResetGameModal() {
                    document.getElementById('resetGameModal').style.setProperty('display', 'none', 'important');
                }

                async function confirmResetGame() {
                    const btnConfirm = document.getElementById('btnConfirmReset');
                    const textSpan = document.getElementById('btnResetText');
                    const spinnerSpan = document.getElementById('btnResetSpinner');

                    btnConfirm.disabled = true;
                    textSpan.style.display = 'none';
                    spinnerSpan.style.display = 'inline-block';

                    try {
                        const response = await fetch('{{ route('game.skip', $table->id) }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            }
                        });

                        if (!response.ok) throw new Error('Ошибка сервера');

                        localStorage.removeItem('gameState');
                        localStorage.removeItem('tableId');
                        window.location.reload();

                    } catch (error) {
                        console.error('Ошибка:', error);
                        alert('Ошибка сервера при сбросе игры.');
                        btnConfirm.disabled = false;
                        textSpan.style.display = 'inline-block';
                        spinnerSpan.style.display = 'none';
                    }
                }

                document.getElementById('resetGameModal').addEventListener('click', function(e) {
                    if (e.target.id === 'resetGameModal') closeResetGameModal();
                });
            </script>

            <button class="btn btn-danger" data-href="/modal/{{ $table->id }}" data-vbtype="ajax" data-vbox>Оплата
            {{-- </button>
                        <button class="btn btn-danger" data-href="/modal2/{{ $table->game_id }}" data-vbtype="ajax" data-vbox>Оплата2
            </button> --}}
        </div>
    </div>

</header>
