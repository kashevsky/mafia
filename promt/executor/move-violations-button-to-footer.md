# Задача: Переместить кнопку "Нарушения" в footer

## Описание задачи
Переместить кнопку "Нарушения" из div class="board" в footer. Применить к кнопке такие же стили как у остальных кнопок в footer.

## Требуемое поведение
1. Удалить кнопку "Нарушения" из `resources/views/play.blade.php` (строка 137)
2. Добавить кнопку "Нарушения" в footer `resources/views/include/footer/index.blade.php`
3. Применить к кнопке класс стиля как у других кнопок в footer (btn-primary или btn-danger)

## Файлы для изменения

### 1. `resources/views/play.blade.php`
Удалить строку:
```blade
<button class="btn" @click="eventOpenModalViolations()">Нарушения</button>
```
(строка 137)

### 2. `resources/views/include/footer/index.blade.php`
Добавить кнопку в один из блоков `footer__btns` (например, во второй блок рядом с кнопками управления):
```blade
<button class="btn btn-primary" @click.stop="eventOpenModalViolations()">Нарушения</button>
```

## Что сделать
1. Открой файл `resources/views/play.blade.php`
2. Найди строку 137 с кнопкой "Нарушения"
3. Удали эту строку
4. Открой файл `resources/views/include/footer/index.blade.php`
5. Найди второй блок `footer__btns` (строка 19)
6. Добавь кнопку "Нарушения" с классом `btn btn-primary` после кнопки "Голоса" (строка 25)
7. Проверь что кнопка отображается в footer и имеет такой же стиль как другие кнопки

## Контекст
- Кнопка должна использовать класс `btn-primary` (синий) или `btn-danger` (красный) для соответствия стилю других кнопок в footer
- В footer уже есть кнопки с классами: `btn-green`, `btn-primary`, `btn-danger`
- Кнопка должна быть в блоке `footer__btns`
