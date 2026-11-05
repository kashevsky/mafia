# Задача: Создать модальное окно для проставления нарушений игрокам

## Описание задачи
Создать новое модальное окно для проставления нарушений игрокам. В окне должен быть список всех игроков с счетчиками нарушений и кнопкой "Наказать". Максимальное количество нарушений на игрока - 4. Выбывшие из игры игроки не кликабельны. Все данные должны сохраняться в localStorage через GAME_STATE_KEYS.

## Требуемое поведение
1. Модальное окно открывается по кнопке (добавить кнопку на игровой экран)
2. Показывается список всех игроков с:
   - Номером игрока
   - Именем игрока
   - Счетчиком нарушений (0-4)
   - Кнопкой "Наказать" (увеличивает счетчик на 1)
3. Выбывшие игроки (`is_alive === 0`) не кликабельны
4. Максимум 4 нарушения на игрока
5. При достижении 4 нарушений кнопка "Наказать" отключается
6. Все изменения сохраняются в localStorage

## Файлы для изменения

### 1. `resources/js/game/game.js`
- Добавить переменную `showModalViolations` в объект return (строка ~140)
- Добавить переменную `showBtnModalViolations` для кнопки открытия модального окна
- Добавить ключ `showModalViolations` в массив `GAME_STATE_KEYS` (строка ~54)
- Добавить метод `eventOpenModalViolations()` для открытия модального окна
- Добавить метод `eventAddViolation(userId)` для добавления нарушения игроку
- Добавить метод `_saveGameState()` для сохранения состояния (уже существует, убедиться что работает)

### 2. `resources/views/play.blade.php`
- Добавить кнопку для открытия модального окна нарушений
- Подключить модальное окно через `@include`

### 3. `resources/views/include/modal-violations/index.blade.php` (новый файл)
- Создать папку `modal-violations` в `resources/views/include/`
- Создать файл `index.blade.php` с модальным окном

## Детальная реализация

### Шаг 1: Добавить переменные в game.js

В объекте return функции `initGame()` (примерно строка 140) добавить:
```javascript
showModalViolations: false, // модальное окно нарушений
showBtnModalViolations: false, // кнопка открытия модального окна нарушений
```

В массив `GAME_STATE_KEYS` (примерно строка 54) добавить:
```javascript
'showModalViolations',
```

### Шаг 2: Добавить методы в game.js

Добавить метод для открытия модального окна:
```javascript
eventOpenModalViolations() {
    this.showModalViolations = true;
    this._saveGameState();
},
```

Добавить метод для добавления нарушения:
```javascript
eventAddViolation(userId) {
    const user = this.users.find(u => u.id === userId);
    if (!user) return;
    
    // Проверяем что игрок жив
    if (!user.is_alive) return;
    
    // Проверяем что нарушений меньше 4
    if (!user.foul) user.foul = 0;
    if (user.foul >= 4) return;
    
    // Увеличиваем счетчик нарушений
    user.foul++;
    
    this._saveGameState();
},
```

### Шаг 3: Создать модальное окно

Создать файл `resources/views/include/modal-violations/index.blade.php`:
```blade
<template x-if="showModalViolations">

    <div class="modal-layout">
        <div class="modal" @click.outside="showModalViolations=false">

            <div class="modal__inner">

                <div class="modal__head">
                    <h2>Нарушения игроков</h2>
                </div>

                <div class="modal__body">
                    <div class="violations-wrap">
                        <template x-for="user in users" :key="user.id">
                            <div class="violation-item" 
                                 :class="!user.is_alive ? 'disabled' : ''">
                                <div class="violation-info">
                                    <span x-text="`№${user.number} - ${user.nickname || user.name}`"></span>
                                    <span class="violation-count" x-text="user.foul || 0"></span>
                                </div>
                                <button class="btn" 
                                        :disabled="!user.is_alive || (user.foul || 0) >= 4"
                                        @click="eventAddViolation(user.id)">
                                    Наказать
                                </button>
                            </div>
                        </template>
                    </div>

                    <button class="btn-green" @click="showModalViolations=false">Закрыть</button>
                </div>

            </div>
        </div>
    </div>
</template>
```

### Шаг 4: Добавить кнопку в play.blade.php

В файле `resources/views/play.blade.php` добавить кнопку для открытия модального окна (в подходящем месте, например рядом с другими кнопками управления):
```blade
<button class="btn" @click="eventOpenModalViolations()">Нарушения</button>
```

### Шаг 5: Подключить модальное окно в play.blade.php

В файле `resources/views/play.blade.php` (примерно строка 180) добавить:
```blade
{{-- модальное окно нарушений --}}
@include('include.modal-violations.index')
```

### Шаг 6: Добавить стили

В файле `resources/scss/pages/main.scss` добавить стили для модального окна нарушений:
```scss
.violations-wrap {
    display: flex;
    flex-direction: column;
    gap: 10px;
    margin-bottom: 20px;
}

.violation-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 8px;
    
    &.disabled {
        opacity: 0.5;
        pointer-events: none;
    }
}

.violation-info {
    display: flex;
    gap: 10px;
    align-items: center;
}

.violation-count {
    font-weight: bold;
    color: #ff0000;
}
```

## Что сделать
1. Открой файл `resources/js/game/game.js`
2. В объекте return функции `initGame()` добавь переменные `showModalViolations: false` и `showBtnModalViolations: false`
3. В массив `GAME_STATE_KEYS` добавь `'showModalViolations'`
4. Добавь метод `eventOpenModalViolations()` для открытия модального окна
5. Добавь метод `eventAddViolation(userId)` для добавления нарушения игроку
6. Создай папку `resources/views/include/modal-violations/`
7. Создай файл `resources/views/include/modal-violations/index.blade.php` с кодом модального окна
8. Открой файл `resources/views/play.blade.php`
9. Добавь кнопку для открытия модального окна
10. Добавь `@include('include.modal-violations.index')` для подключения модального окна
11. Открой файл `resources/scss/pages/main.scss`
12. Добавь стили для модального окна нарушений
13. Проверь:
    - Кнопка открывает модальное окно
    - Список игроков отображается с номерами и именами
    - Счетчик нарушений работает (0-4)
    - Кнопка "Наказать" увеличивает счетчик
    - При 4 нарушениях кнопка отключается
    - Выбывшие игроки не кликабельны
    - Данные сохраняются в localStorage

## Контекст
- Alpine.js используется для реактивности
- Модальные окна используют `<template x-if="variableName">`
- Переменные должны быть объявлены в объекте return для реактивности
- GAME_STATE_KEYS - массив ключей для сохранения в localStorage
- Метод `_saveGameState()` сохраняет все ключи из GAME_STATE_KEYS в localStorage
- Игроки имеют свойство `is_alive` (0 или 1)
- Новое свойство `foul` будет добавлено к объекту игрока для хранения количества нарушений
