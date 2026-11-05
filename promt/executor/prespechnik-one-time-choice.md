# Задача: Приспешник выбирает только один раз из указанных ночей

## Описание задачи
Приспешник должен иметь возможность выбрать (запачкать) игрока только один раз из указанных первых ночей (countPrespechnik). Если Приспешник уже отыграл в одну из ночей, то для остальных ночей в пределах countPrespechnik нужно показывать ведущему сообщение "Приспешник работает", чтобы другие игроки не заподозрили, что Приспешник уже голосовал.

## Анализ проблемы
1. Сейчас Приспешник может выбирать в каждую ночь до тех пор, пока не закончатся шансы (`_isActiveSkillPrespechnik`)
2. Переменная `countPrespechnik` из `this.$store.globalData.countPrespechnik` указывает количество ночей, в которые Приспешник может действовать
3. В строке 3415 есть проверка: если `countNights > countPrespechnik`, Приспешник не может голосовать
4. Нужно добавить ограничение: Приспешник может реально выбрать только один раз, а в остальные ночи имитировать работу

## Решение
1. Добавить переменную для отслеживания, выбрал ли Приспешник игрока
2. В методе `_toggleNextRoleMafia()` для Приспешника добавить проверку:
   - Если Приспешник уже выбрал и текущая ночь в пределах countPrespechnik - показывать сообщение "Приспешник работает" и пропускать стадию выбора
   - Если Приспешник еще не выбрал - позволить выбрать
3. При успешном выборе Приспешника отмечать, что он уже выбрал

## Файлы для изменения

### `resources/js/game/game.js`

#### Шаг 1: Добавить переменную для отслеживания выбора Приспешника
В объекте `saveNightGame` (примерно строка 94) добавить:
```javascript
saveNightGame: {
    // ... существующие поля
    prespechnikHasChosen: false, // Приспешник уже выбрал игрока
}
```

Также добавить эту переменную в `GAME_STATE_KEYS` (примерно строка 60):
```javascript
GAME_STATE_KEYS = [
    // ... существующие ключи
    'saveNightGame',
]
```

#### Шаг 2: Изменить логику в `_toggleNextRoleMafia()`
В методе `_toggleNextRoleMafia()` (примерно строка 3413) изменить логику для Приспешника:

```javascript
case 'Приспешник':
    // После указанных ночей Приспешник не может голосовать
    if (this.countNights > this.$store.globalData.countPrespechnik) {
        player.is_vote = 1;
        this._toggleNextRoleMafia();
        this._saveGameState();
        return;
    }
    
    // Если Приспешник уже выбрал игрока - имитируем работу
    if (this.saveNightGame.prespechnikHasChosen) {
        this.alertPage = {
            show: true,
            text: 'Приспешник работает'
        };
        player.is_vote = 1;
        this._toggleNextRoleMafia();
        this._saveGameState();
        return;
    }
    
    // Приспешник еще не выбрал - позволяем выбрать
    this.stageGame = GAME_STAGES.MAFIA_PRESPECHNIK;
    this._outputAlertBoard();
    this._saveGameState();
    return;
```

#### Шаг 3: Отметить, что Приспешник выбрал игрока
В методе `eventBtn()` для `GAME_STAGES.MAFIA_PRESPECHNIK` (примерно строка 1009) после успешного выбора добавить:

```javascript
if (!finishPrespechnik) {
    this.saveNightGame.idPlayerBlockedThePrespechnik = this.targetUser.id;
    targetUser.buff_role = [...targetUser.buff_role, "Приспешник"];
    
    // Отмечаем, что Приспешник выбрал игрока
    this.saveNightGame.prespechnikHasChosen = true;
}
```

#### Шаг 4: Сбросить флаг при назначении Приспешника
В методе `eventAssignPrispeshnik()` сбросить флаг при назначении Приспешника:
```javascript
eventAssignPrispeshnik(id) {
    let newPrispeshnik = this.findUserId(id);

    newPrispeshnik.role = 'Приспешник';
    this.saveNightGame.prespechnikHasChosen = false; // сбрасываем флаг при назначении Приспешника
    this.showModalNightKnowing = false;
    this.stageGame = GAME_STAGES.CHOOSE_FIRST_PLAYER;
    this._changeRole();
    this._startGame();
},
```

**Важно:** НЕ сбрасывать флаг в `_resetUsersStartGame()`, так как этот метод вызывается каждый день при выборе первого игрока, а флаг должен сохраняться в течение всей игры.

## Что сделать
1. Открой файл `resources/js/game/game.js`
2. В объекте `saveNightGame` (примерно строка 94) добавь поле `prespechnikHasChosen: false`
3. В методе `_toggleNextRoleMafia()` найди case 'Приспешник' (примерно строка 3413)
4. Измени логику как указано в Шаге 2
5. В методе `eventBtn()` для `GAME_STAGES.MAFIA_PRESPECHNIK` (примерно строка 1009) после успешного выбора добавь `this.saveNightGame.prespechnikHasChosen = true;`
6. В методе `eventAssignPrispeshnik()` добавь `this.saveNightGame.prespechnikHasChosen = false;` при назначении Приспешника
7. Проверь:
   - Приспешник может выбрать игрока только один раз в первые countPrespechnik ночей
   - Если Приспешник уже выбрал, в следующие ночи показывается сообщение "Приспешник работает"
   - После countPrespechnik ночей Приспешник не может голосовать

## Контекст
- `countPrespechnik` - количество ночей, в которые Приспешник может действовать (из globalData)
- `_isActiveSkillPrespechnik` - флаг активности скилла (используется для ограничения попыток)
- `saveNightGame.idPlayerBlockedThePrespechnik` - id игрока, которого запачкал Приспешник
- Сообщение "Приспешник работает" нужно показывать только ведущему (alertPage)
