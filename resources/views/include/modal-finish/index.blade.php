<template x-if="showModalFinishGame">
    <div class="modal-layout">
        <!-- Главное окно выбора результата -->
        <div class="modal" @click.outside="showModalFinishGame = false" x-data="{
            showConfirm: false,
            winnerName: '',
            winnerKey: '',
            isConfirmed: false,

            selectWinner(key, name) {
                this.winnerKey = key;
                this.winnerName = name;
                this.isConfirmed = false;
                this.showConfirm = true;
            },

            submitResult() {
                if (!this.isConfirmed) return;

                // Вызываем ваше событие завершения игры
                eventFinishGame(this.winnerKey);

                // Очищаем хранилище
                localStorage.removeItem('gameState');
                localStorage.removeItem('tableId');

                // Закрываем модалку
                showModalFinishGame = false;
            }
        }">

            <!-- ШАГ 1: Выбор победителя (скрывается, когда открыто подтверждение) -->
            <div class="modal__inner" x-show="!showConfirm" x-transition>
                <div class="modal__head">
                    <h2>Результат игры</h2>
                </div>
                <div class="modal__body">
                    <button class="btn" @click.stop="selectWinner('мафия', 'МАФИЯ')">Победила мафия</button>
                    <button class="btn" @click.stop="selectWinner('мирный', 'МИРНЫЕ')">Победили мирные</button>
                    <button class="btn" @click.stop="selectWinner('ничья', 'НИЧЬЯ')">Ничья</button>
                </div>
            </div>

            <!-- ШАГ 2: Окно подтверждения в игровом стиле -->
            <div class="modal__inner confirm-box" x-show="showConfirm" x-cloak x-transition>
                <div class="modal__head">
                    <h2><i class="fas fa-exclamation-triangle" style="color: #e74c3c; margin-right: 8px;"></i> Подтверждение</h2>
                </div>
                <div class="modal__body">
                    <p class="confirm-text">Вы уверены, что победила команда <span class="winner-highlight" x-text="winnerName"></span>?</p>

                    <!-- Чекбокс верификации -->
                    <label class="game-checkbox-container">
                        <input type="checkbox" x-model="isConfirmed">
                        <span class="checkbox-custom"></span>
                        <span class="checkbox-label">Я проверил результат игры и данные верны</span>
                    </label>
                </div>
                <div class="modal__footer">
                    <button class="btn btn-secondary" @click.stop="showConfirm = false">Назад</button>
                    <button class="btn btn-danger" :disabled="!isConfirmed" @click.stop="submitResult()">
                        Подтвердить
                    </button>
                </div>
            </div>

        </div>
    </div>
</template>

<!-- Стили для красивого отображения элементов чекбокса и окна подтверждения -->
<style>
    /* Предотвращение мигания Alpine элементов */
    [x-cloak] { display: none !important; }

    /* Оформление блока подтверждения */
    .confirm-box {
        border: 1px solid #c0392b !important;
        box-shadow: 0 0 15px rgba(192, 57, 43, 0.3);
    }

    .confirm-text {
        font-size: 16px;
        margin-bottom: 20px;
        color: #f5f6fa;
    }

    .winner-highlight {
        color: #e74c3c;
        font-weight: bold;
        text-shadow: 0 0 10px rgba(231, 76, 60, 0.4);
    }

    /* Футер для кнопок управления */
    .modal__footer {
        padding: 15px 20px;
        background-color: #131b26;
        border-top: 1px solid #233145;
        display: flex;
        justify-content: center;
        gap: 12px;
    }

    /* Игровой кастомный чекбокс */
    .game-checkbox-container {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        cursor: pointer;
        user-select: none;
        margin: 20px 0;
        padding: 10px;
        background: rgba(0, 0, 0, 0.2);
        border-radius: 6px;
    }

    .game-checkbox-container input {
        display: none;
    }

    .checkbox-custom {
        width: 20px;
        height: 20px;
        border: 2px solid #34495e;
        border-radius: 4px;
        position: relative;
        transition: all 0.2s;
        background: #151d2a;
    }

    .game-checkbox-container input:checked + .checkbox-custom {
        background: #2ecc71;
        border-color: #2ecc71;
    }

    .game-checkbox-container input:checked + .checkbox-custom::after {
        content: '✓';
        position: absolute;
        color: #fff;
        font-size: 14px;
        font-weight: bold;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
    }

    .checkbox-label {
        color: #b2bec3;
        font-size: 14px;
    }

    /* Кнопки */
    .btn-danger {
        background-color: #c0392b !important;
        color: white;
    }
    .btn-danger:disabled {
        background-color: #7f8c8d !important;
        cursor: not-allowed;
        opacity: 0.6;
    }
    .btn-secondary {
        background-color: #2c3e50 !important;
        color: white;
    }
</style>
