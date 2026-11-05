# Задача: Создать выпадающее меню (sidebar) для кнопок управления

## Описание задачи
Создать боковое меню (sidebar), которое выезжает с левой стороны экрана при нажатии на кнопку в footer. В меню должны быть перемещены кнопки: "Дать иммунитет", "Голоса", "Нарушения", "Завершить игру", "Удалить игрока". Кнопка вызова меню должна быть в footer с иконкой и названием по смыслу.

## Требуемое поведение
1. В footer добавить кнопку для открытия меню с иконкой и названием (например, "Меню" или "Управление")
2. При нажатии на кнопку меню плавно выезжает с левой стороны экрана
3. В меню должны быть кнопки:
   - Дать иммунитет (открывает `showModalImmunity`)
   - Голоса (открывает `showModalVote`)
   - Нарушения (открывает `showModalViolations`)
   - Завершить игру (открывает `showModalFinishGame`)
   - Удалить игрока (открывает `showModalDeleteUser`)
4. Кнопка "Спрятать роли" остается в footer (не переносится в меню)
5. При повторном нажатии на кнопку меню или клике вне меню - меню закрывается

## Файлы для изменения

### 1. `resources/js/game/game.js`
- Добавить переменную `showSidebarMenu` в объект return (строка ~140)
- Добавить метод `eventToggleSidebarMenu()` для открытия/закрытия меню

### 2. `resources/views/include/footer/index.blade.php`
- Удалить кнопки из второго блока `footer__btns` (кроме "Спрятать роли"):
  - Удалить кнопку "Дать иммунитет"
  - Удалить кнопку "Голоса"
  - Удалить кнопку "Нарушения"
  - Удалить кнопку "Завершить игру"
  - Удалить кнопку "Удалить игрока"
- Добавить кнопку для открытия меню с иконкой
- Оставить кнопку "Спрятать роли" в footer

### 3. `resources/views/play.blade.php`
- Добавить компонент sidebar меню перед закрывающим тегом `@endsection`

### 4. `resources/scss/pages/main.scss`
- Добавить стили для sidebar меню
- Добавить стили для кнопки меню в footer

## Детальная реализация

### Шаг 1: Добавить переменные в game.js

В объекте return функции `initGame()` (примерно строка 140) добавить:
```javascript
showSidebarMenu: false, // боковое меню управления
```

Добавить метод для переключения меню:
```javascript
eventToggleSidebarMenu() {
    this.showSidebarMenu = !this.showSidebarMenu;
},
```

### Шаг 2: Обновить footer

В файле `resources/views/include/footer/index.blade.php`:

Второй блок `footer__btns` (строка 19) заменить на:
```blade
<div class="footer__btns">
    <button class="btn btn-primary" @click="eventToggleSidebarMenu()">
        <img src="images/icons/icon-gavel.png" width="20" height="20" alt="Меню">
        Меню
    </button>

    <button class="btn btn-primary" @click="hideRoles()"
        x-text="isHiddenRoles ? 'Спрятать роли' : 'Показать роли'"></button>
</div>
```

### Шаг 3: Создать sidebar меню

В файле `resources/views/play.blade.php` перед `@endsection` добавить:
```blade
{{-- sidebar меню --}}
<template x-if="showSidebarMenu">
    <div class="sidebar-overlay" @click="showSidebarMenu = false"></div>
</template>

<template x-if="showSidebarMenu">
    <div class="sidebar">
        <div class="sidebar__header">
            <h2>Управление</h2>
            <button class="sidebar__close" @click="showSidebarMenu = false">✕</button>
        </div>
        <div class="sidebar__content">
            <template x-if="countDays===0">
                <button class="btn btn-primary sidebar__btn" 
                        @click.stop="showModalImmunity = true; showSidebarMenu = false" 
                        x-show="whatDay === 'День'">
                    Дать иммунитет
                </button>
            </template>

            <button class="btn btn-primary sidebar__btn" 
                    @click.stop="showModalVote = true; showSidebarMenu = false" 
                    x-show="whatDay === 'День'">
                Голоса
            </button>

            <button class="btn btn-primary sidebar__btn" 
                    @click.stop="eventOpenModalViolations(); showSidebarMenu = false">
                Нарушения
            </button>

            <button class="btn btn-danger sidebar__btn" 
                    @click.stop="showModalFinishGame = true; showSidebarMenu = false">
                Завершить игру
            </button>

            <button class="btn btn-danger sidebar__btn" 
                    @click.stop="showModalDeleteUser = true; showSidebarMenu = false">
                Удалить игрока
            </button>
        </div>
    </div>
</template>
```

### Шаг 4: Добавить стили

В файле `resources/scss/pages/main.scss` добавить:
```scss
// Sidebar menu
.sidebar-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    z-index: 200;
}

.sidebar {
    position: fixed;
    top: 0;
    left: 0;
    width: 300px;
    height: 100%;
    background-color: #131b26;
    z-index: 201;
    transform: translateX(-100%);
    transition: transform 0.3s ease-in-out;
    border-right: 1px solid #2c3e50;
    display: flex;
    flex-direction: column;

    &[x-cloak] {
        display: none;
    }

    &[x-show="true"] {
        transform: translateX(0);
    }

    &__header {
        padding: 2rem;
        border-bottom: 1px solid #2c3e50;
        display: flex;
        justify-content: space-between;
        align-items: center;

        h2 {
            margin: 0;
            font-size: 2rem;
            color: #fff;
        }
    }

    &__close {
        background: none;
        border: none;
        color: #fff;
        font-size: 2rem;
        cursor: pointer;
        padding: 0.5rem;
    }

    &__content {
        padding: 2rem;
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    &__btn {
        width: 100%;
        text-align: left;
        padding: 1rem 1.5rem;
    }
}

// Кнопка меню в footer
.footer__btns .btn {
    display: flex;
    align-items: center;
    gap: 0.5rem;

    img {
        width: 1.5rem;
        height: 1.5rem;
    }
}
```

## Что сделать
1. Открой файл `resources/js/game/game.js`
2. В объекте return функции `initGame()` добавь переменную `showSidebarMenu: false`
3. Добавь метод `eventToggleSidebarMenu()` для переключения меню
4. Открой файл `resources/views/include/footer/index.blade.php`
5. Во втором блоке `footer__btns` удали кнопки: "Дать иммунитет", "Голоса", "Нарушения", "Завершить игру", "Удалить игрока"
6. Добавь кнопку "Меню" с иконкой для открытия sidebar
7. Оставь кнопку "Спрятать роли" в footer
8. Открой файл `resources/views/play.blade.php`
9. Перед `@endsection` добавь код sidebar меню с кнопками
10. Открой файл `resources/scss/pages/main.scss`
11. Добавь стили для sidebar меню и кнопки меню в footer
12. Проверь:
    - Кнопка "Меню" отображается в footer с иконкой
    - При нажатии меню плавно выезжает с левой стороны
    - Все кнопки в меню работают и открывают соответствующие модальные окна
    - При клике вне меню или на кнопку закрытия - меню закрывается
    - Кнопка "Спрятать роли" осталась в footer

## Контекст
- Alpine.js используется для реактивности
- Используй `x-show` для показа/скрытия меню
- CSS transition для плавной анимации выезда меню
- Иконка `icon-gavel.png` уже существует в `public/images/icons/`
- Меню должно быть поверх всего контента (z-index: 201)
- Overlay (затемнение) должен быть под меню (z-index: 200)
