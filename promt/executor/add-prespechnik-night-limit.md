# Добавить ограничение по ночам для Приспешника

## Описание проблемы
Приспешник сейчас может действовать бесконечно, пока шериф или детектив не проверят запачканного игрока. Нужно добавить ограничение: приспешник может действовать только в первые `countPrespechnik` ночей (по умолчанию 1 ночь).

## Требуемое поведение
- Приспешник не вызывается на голосование если `countNights > countPrespechnik`
- После того как ночей больше чем `countPrespechnik`, приспешник пропускается при переключении ролей мафии
- Свойство `countPrespechnik` уже добавлено в blade-шаблон и в game.js (значение 1)

## Файлы для изменения
`resources/js/game/game.js`

## Анализ проблемы

### Текущая логика
Сейчас есть проверка на строке 3416 в методе `_toggleNextRoleMafia`:
```javascript
case 'Приспешник':
    // После 3 ночей Приспешник не может голосовать
    if (this.countNights > 3) {
        player.is_vote = 1;
        this._toggleNextRoleMafia();
        this._saveGameState();
        return;
    }
```

Эта проверка блокирует вызов приспешника после 3 ночей, но использует жестко заданное число 3.

### Новая логика
Нужно изменить проверку с `this.countNights > 3` на `this.countNights > this.countPrespechnik`, чтобы использовать динамическое значение из конфигурации.

## Конкретное решение с кодом

### Изменение 1: Добавить countPrespechnik в GAME_STATE_KEYS
В массив `GAME_STATE_KEYS` (строка 54-60) добавить `'countPrespechnik'`:

Было:
```javascript
const GAME_STATE_KEYS = [
    'stageGame', 'users', '_userStartGameId', 'countDays', 'countNights',
    '_quantityPossibleDoctor', '_quantityBlockDon', '_quantityCheckDzhurnalist',
    '_isActiveSkillPrespechnik', 'saveNightGame', '_directionVote', 'isMafiaVote',
    // ...
```

Стало:
```javascript
const GAME_STATE_KEYS = [
    'stageGame', 'users', '_userStartGameId', 'countDays', 'countNights',
    '_quantityPossibleDoctor', '_quantityBlockDon', '_quantityCheckDzhurnalist',
    '_isActiveSkillPrespechnik', 'countPrespechnik', 'saveNightGame', '_directionVote', 'isMafiaVote',
    // ...
```

### Изменение 2: Добавить проверку по ночям для приспешника
В логике приспешника (строка 1000) изменить условие:

Было:
```javascript
if (!this._isActiveSkillPrespechnik) {
    finishPrespechnik = true;
    this.alertPage = {
        show: true,
        text: `Шансы закончились!`
    }
}
```

Стало:
```javascript
if (!this._isActiveSkillPrespechnik || this.countNights > this.countPrespechnik) {
    finishPrespechnik = true;
    this.alertPage = {
        show: true,
        text: `Шансы закончились!`
    }
}
```

### Изменение 3: Добавить countPrespechnik в отправку данных
В методе `_sendDataGame` (примерно строка 2248-2263) добавить `countPrespechnik`:

Найти где отправляются другие параметры (например, `quantityPossibleDoctor`) и добавить:
```javascript
countPrespechnik: this.countPrespechnik, // количество первых ночей когда может действовать Приспешник
```

## Шаги для выполнения

1. Открыть файл `resources/js/game/game.js`
2. Найти массив `GAME_STATE_KEYS` (строка 54)
3. Добавить `'countPrespechnik'` после `'_isActiveSkillPrespechnik'`
4. Найти строку 1000 (проверка шансов приспешника)
5. Изменить условие: добавить `|| this.countNights > this.countPrespechnik`
6. Найти метод `_sendDataGame` (примерно строка 2248)
7. Добавить отправку `countPrespechnik` в объект данных
8. Протестировать изменения

## Контекст изменений
- Свойство `countPrespechnik` уже добавлено в blade-шаблон (`resources/views/layouts/index.blade.php`)
- Свойство `countPrespechnik` уже добавлено в game.js (строка 91)
- Значение по умолчанию: 1 ночь
- Изменения касаются только ночной фазы игры
- Приспешник - мафиозная роль
