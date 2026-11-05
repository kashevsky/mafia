# Задача: Мститель (Фредди) не может убить мирного, даже если он запачкан Приспешником

## Описание задачи
Изменить логику Мстителя (Фредди): он не должен убивать мирного игрока, даже если этот игрок запачкан Приспешником. Сейчас Мститель убивает мирного если он запачкан Приспешником - это нужно убрать.

## Анализ проблемы
1. В методе `eventEndNight()` (строка 775) есть логика проверки кого пометил Мститель
2. В строках 793-807 есть условие: если помеченный игрок мирный (`character === 'Y'`) и запачкан Приспешником (`idPlayerBlockedThePrespechnik == userMarkFreddy.id`), то Мститель его убивает
3. Это условие нужно удалить - Мститель не должен убивать мирных игроков

## Решение
Удалить блок кода, который убивает мирного игрока запачканного Приспешником.

## Файлы для изменения

### `resources/js/game/game.js`

В методе `eventEndNight()` найти и удалить блок кода (примерно строки 793-807):

```javascript
} else if (userMarkFreddy.character === 'Y' && this.saveNightGame.idPlayerBlockedThePrespechnik == userMarkFreddy.id) {

    userMarkFreddy.is_alive = 0;
    this._isActiveSkillPrespechnik = false;

    this.arrUsersKillFredy.push(userMarkFreddy);


    this.resumeKillUsers.push({
        user: userMarkFreddy,
        text: "Убит",
        status: 'kill',
    });
    this.saveNightGame.idPlayerCheckTheFreddy = null;
}
```

## Что сделать
1. Открой файл `resources/js/game/game.js`
2. Найди метод `eventEndNight()` (примерно строка 643)
3. Найди блок кода с условием `userMarkFreddy.character === 'Y' && this.saveNightGame.idPlayerBlockedThePrespechnik == userMarkFreddy.id` (примерно строка 793)
4. Удали весь этот блок кода (строки 793-807)
5. Проверь:
   - Мститель убивает мафию (character === 'N')
   - Мститель НЕ убивает мирного, даже если он запачкан Приспешником

## Контекст
- Мститель (Фредди) помечает игрока ночью
- Если Мститель умирает ночью, то помеченный игрок умирает
- Сейчас Мститель убивает мирного если он запачкан Приспешником - это нужно убрать
- Мститель должен убивать только мафию (character === 'N')
