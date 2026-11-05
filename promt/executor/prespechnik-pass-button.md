# Задача: Добавить отдельную кнопку "Пас" для мафии в footer

## Описание задачи
Кнопка "Пас" в footer (строка 12) использует метод `eventBtn()`, который не работает для пропуска хода Приспешника. Нужно добавить отдельную кнопку "Пас" для мафии (включая Приспешника) в footer, которая будет вызывать специальный метод для пропуска хода.

## Анализ проблемы
1. Кнопка в footer строка 12: `<button class="btn btn-green" x-text="nameBtn" x-show="nameBtn" @click.stop="eventBtn"></button>`
2. Эта кнопка вызывает `eventBtn()`, который обрабатывает выбор игрока, а не пропуск хода
3. Для Приспешника мы установили `nameBtn = 'Пас'` и добавили проверку `if (!this.targetUser)` в `eventBtn()`, но это не работает
4. Нужно добавить отдельную кнопку для мафии с отдельным методом `eventPasMafia()`

## Решение
1. Добавить отдельную кнопку "Пас" для мафии в footer
2. Добавить метод `eventPasMafia()` в game.js для обработки пропуска хода мафии
3. Показывать эту кнопку когда голосует мафия (включая Приспешника)

## Файлы для изменения

### 1. `resources/views/include/footer/index.blade.php`
Добавить отдельную кнопку Пас для мафии после строки 12:

```blade
<footer class="footer">

    <div class="footer-container">
        <div class="footer__btns">
            <template x-if="countDays <= 1 && countNights < 1">
                <button class="btn btn-green" x-show="pasBtn && countDays <= 1" @click.stop="eventPasBtn">Пас</button>
            </template>
            <template x-if="stageGame == 10">
                <button class="btn btn-green"  @click.stop="eventOpenNightKnowing()">Мафия знакомится</button>
            </template>
            <br>
            <button class="btn btn-green" x-text="nameBtn" x-show="nameBtn" @click.stop="eventBtn"></button>
            
            <!-- Кнопка Пас для мафии (включая Приспешника) -->
            <template x-if="stageGame >= 6 && stageGame < 7">
                <button class="btn btn-green" @click.stop="eventPasMafia()">Пас</button>
            </template>
            
            <br>
            <button class="btn btn-green" x-show="showBtnModalNight" x-text="`открыть доску`" x-show="nameBtn"
                @click.stop="showModalNight = true"></button>


        </div>
        <div class="footer__btns">
            <button class="btn btn-primary" @click="eventToggleSidebarMenu()">
                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-layout-grid-circles preview-icon"><circle cx="17.5" cy="17.5" r="3.5"/><circle cx="17.5" cy="6.5" r="3.5"/><circle cx="6.5" cy="17.5" r="3.5"/><circle cx="6.5" cy="6.5" r="3.5"/></svg>                Меню управления
            </button>

            <button class="btn btn-primary" @click="hideRoles()"
                x-text="isHiddenRoles ? 'Спрятать роли' : 'Показать роли'"></button>
        </div>
    </div>

    <div class="timer" x-ref='timer' x-show="stageGame === 1">00:00</div>
</footer>
```

### 2. `resources/js/game/game.js`
Добавить метод `eventPasMafia()`:

```javascript
eventPasMafia() {
    this.toUser = null;
    this._sendDataGame();

    // Определяем текущую роль мафии
    let currentRole = '';
    if (this.stageGame === GAME_STAGES.MAFIA_KILL) {
        currentRole = 'Мафия';
    } else if (this.stageGame === GAME_STAGES.MAFIA_DON) {
        currentRole = 'Дон';
    } else if (this.stageGame === GAME_STAGES.MAFIA_PRESPECHNIK) {
        currentRole = 'Приспешник';
    }

    // отмечаем что роль проголосовала
    this.users.map(user => {
        if (user.role == currentRole) {
            user.is_vote = 1;
        }
    });

    this._resetUsersActive();
    this._resetUserTarget();
    this._toggleNextRoleMafia();
    this._saveGameState();
},
```

Также нужно вернуть изменения в `_outputAlertBoard()` для Приспешника - убрать `pasBtn = true`:

```javascript
case GAME_STAGES.MAFIA_PRESPECHNIK:
    //  роль (Приспешник)
    this.boardMess = 'Голосует Приспешник';
    this.nameBtn = '';
    this.messBoardAlert = `Приспешник - компромитирует`;
    break;
```

И убрать проверку `if (!this.targetUser)` из `eventBtn()` для `MAFIA_PRESPECHNIK`:

```javascript
case GAME_STAGES.MAFIA_PRESPECHNIK:
    // проверка на имунитет d/n   d/n/d
    if (this.checkImmunities(targetUser)) return;
    this._sendDataGame();
    // ... остальной код без проверки if (!this.targetUser)
```

## Что сделать
1. Открой файл `resources/views/include/footer/index.blade.php`
2. После строки 12 добавь кнопку Пас для мафии как указано в Шаге 1
3. Открой файл `resources/js/game/game.js`
4. Добавь метод `eventPasMafia()` как указано в Шаге 2
5. В `_outputAlertBoard()` для `MAFIA_PRESPECHNIK` убери `this.pasBtn = true;` и верни `this.nameBtn = '';`
6. В `eventBtn()` для `MAFIA_PRESPECHNIK` убери проверку `if (!this.targetUser)`
7. Проверь:
   - Кнопка "Пас" для мафии отображается в footer когда голосует мафия/дон/приспешник
   - При нажатии "Пас" текущая роль мафии пропускает ход и переходит к следующей роли

## Контекст
- `GAME_STAGES.MAFIA_KILL = 6` - стадия убийства мафии
- `GAME_STAGES.MAFIA_DON = 6.1` - стадия Дона
- `GAME_STAGES.MAFIA_PRESPECHNIK = 6.2` - стадия Приспешника
- Условие `stageGame >= 6 && stageGame < 7` охватывает все стадии мафии
- Метод `_toggleNextRoleMafia()` переключает на следующую роль мафии
