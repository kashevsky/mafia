# Задача: Выводить сообщение "Жертв нет" если ночью никого не убили

## Описание проблемы
Сейчас если ночью никого не убили, модальное окно с результатами ночи не показывается вообще. Нужно выводить сообщение "Жертв нет" в этом случае.

## Анализ проблемы
1. В методе `eventEndNight()` (строка 641 в `resources/js/game/game.js`) формируется массив `resumeKillUsers` с результатами ночи
2. В начале метода массив очищается: `this.resumeKillUsers = []`
3. В течение метода добавляются записи если кто-то убит или спасен
4. В `play.blade.php` (строка 140) есть условие `<template x-if="resumeKillUsers.length">` - модальное окно показывается только если массив не пустой
5. Если ночью никого не убили, массив остается пустым и модальное окно не показывается

## Решение
В конце метода `eventEndNight()` добавить проверку: если массив `resumeKillUsers` пустой, добавить запись с сообщением "Жертв нет".

## Файлы для изменения

### `resources/js/game/game.js`
В методе `eventEndNight()` после всех проверок (примерно строка 827, перед `this._sendDataGame()`) добавить:

```javascript
// Если ночью никого не убили и не спасли - добавляем сообщение "Жертв нет"
if (this.resumeKillUsers.length === 0) {
    this.resumeKillUsers.push({
        user: null,
        text: "Жертв нет",
        status: 'save',
    });
}
```

## Что сделать
1. Открой файл `resources/js/game/game.js`
2. Найди метод `eventEndNight()` (примерно строка 641)
3. Найди строку `this._sendDataGame();` (примерно строка 829)
4. Перед этой строкой добавь проверку:
   ```javascript
   // Если ночью никого не убили и не спасли - добавляем сообщение "Жертв нет"
   if (this.resumeKillUsers.length === 0) {
       this.resumeKillUsers.push({
           user: null,
           text: "Жертв нет",
           status: 'save',
       });
   }
   ```
5. В файле `resources/views/play.blade.php` найди шаблон для отображения результатов (строка 148)
6. Измени условие для отображения информации о пользователе, чтобы не было ошибки если `user === null`:
   ```blade
   <template x-for="resume in resumeKillUsers">
       <div class="contents">
           <div class="resume">
               <template x-if="resume.user">
                   <span class="resume__number" x-text="`№ ${resume.user.number}`"></span>
                   <span class="resume__name">
                       <span x-text="resume.user.nickname || resume.user.name"></span>
                       <template x-if="resume.user.nickname && resume.user.name">
                           <span x-text="`(${resume.user.name})`"></span>
                       </template>
                   </span>
               </template>
               <span class="resume__status" :class="resume.status"
                   x-text ="`- ${resume.text}`"></span>
           </div>
       </div>
   </template>
   ```
7. Проверь:
   - Если ночью кто-то убит - показывается список убитых
   - Если ночью никого не убили - показывается сообщение "Жертв нет"

## Контекст
- Метод `eventEndNight()` вызывается в конце ночи
- Массив `resumeKillUsers` используется для отображения результатов ночи в модальном окне
- Структура элемента массива: `{ user: object, text: string, status: 'kill'|'save' }`
- Для сообщения "Жертв нет" используем `user: null` и `status: 'save'`
- В Blade шаблоне нужно добавить проверку `x-if="resume.user"` чтобы не было ошибки при доступе к свойствам null
