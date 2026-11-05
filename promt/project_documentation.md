# Документация проекта - Mafia Club

## Общая информация
- **Фреймворк**: Laravel 8
- **PHP**: ^7.3|^8.0
- **Назначение**: Система управления клубом мафии с играми, бронированиями, рейтингами и интеграцией Telegram

## Структура проекта

### Основные директории
- `app/` - основное приложение
  - `Http/Controllers/` - контроллеры
  - `Http/Services/` - бизнес-логика сервисов
  - `Http/Middleware/` - middleware
  - `Models/` - Eloquent модели
- `database/migrations/` - миграции БД
- `routes/` - маршруты
- `resources/views/` - шаблоны Blade

## Ключевые модели

### Account (Игрок)
- Связи: club, games, table, probabilities, achievements, immunities
- Поля: nickname, name, rating, role, character, table_id, club_id, telegram_id
- Особенности: поле `buff_role` хранится как JSON

### Game (Игра)
- Связи: club, photos, applications, tables, results, winners
- Поля: title, date, time, price, total_space, status, is_published
- Статусы: pending, closed

### Table (Стол)
- Связи: game, accounts, applications
- Поля: title, game_id, total_space, occupied_space, is_running, step, is_mafia_win
- Используется для рассадки игроков и проведения игры

### Club (Клуб)
- Связи: games, photos, videos, city, admin, accounts
- Поля: title, address, description
- Метод `games()` имеет сложную сортировку по дате и статусу

## Основные контроллеры

### PlayController
- `index($table_id)` - страница игры, инициализация игроков
- `modal($table_id)` - модальное окно оплаты
- `sendPaymentData()` - сохранение данных оплаты
- `killPlayer()` - удаление игрока из игры
- `makePublic($game_id)` - публикация результатов игры
- `makeOboroten()` - превращение в оборотня

### GameController (публичный)
- `index()` - список игр для игрока
- `show($id)` - детальная страница игры
- `booking()` - бронирование мест на игру
- `cancelBooking()` - отмена бронирования
- `results($id)` - результаты игры (топ-3)

### AccountController
- `show()` - личный кабинет игрока
- `update()` - обновление профиля
- `changeRole()` - изменение роли
- `activateImmunity()` - активация иммунитета

### Admin\TableController
- `create()` - создание стола
- `edit()` - редактирование рассадки
- `save()` - сохранение стола
- `update()` - обновление рассадки
- `randomRoles()` - случайная раздача ролей
- `makePublicQuestion()` - публикация промежуточных результатов
- `run()` / `stop()` - запуск/остановка игры

### Admin\GameController
- `index()` - список игр для админа
- `create()` / `store()` - создание игры
- `edit()` / `update()` - редактирование игры
- `confirmPlayers()` - подтверждение заявок
- `storeScreenshot()` - сохранение скриншота игры
- `skip()` - пропуск раунда

## Сервисы (app/Http/Services)

### MafiaRoleService
- Логика случайной раздачи ролей игрокам

### TelegramService
- Отправка ролей в Telegram
- Отправка уведомлений

### NightRatingService, DayRatingService
- Расчет рейтинга за ночь/день

### FinishService
- Завершение игры и подсчет результатов

### AchivmentService, StaticAchivmentService
- Система достижений

## Middleware

### AccountMiddleware
- Проверка авторизации игрока через сессию
- Поддержка входа через telegram_id

### AdminMiddleware
- Проверка авторизации админа через сессию

## Основные маршруты (routes/web.php)

### Публичные (с AccountMiddleware)
- `/` - главная страница клуба
- `/events` - список игр
- `/event/{id}` - детальная страница игры
- `/account` - личный кабинет
- `/rating` - рейтинг
- `/store` - магазин

### Игровые
- `/play/{table_id}` - страница игры
- `/modal/{table_id}` - модалка оплаты
- `/kill-player` - убить игрока

### Админские (с AdminMiddleware, префикс /admin)
- `/admin` - главная админка
- `/admin/games` - управление играми
- `/admin/clubs` - управление клубами
- `/admin/accounts` - управление игроками
- `/admin/table/create/{game_id}` - создание стола
- `/admin/table/edit/{id}` - редактирование стола
- `/admin/roles/{id}` - управление ролями

## Бизнес-логика

### Бронирование игр
- Игрок бронирует места через `GameController@booking`
- Проверка на дубликаты и доступность мест
- Учет доп. игроков (dops)
- Уведомление админов через Telegram

### Проведение игры
1. Создание стола через `TableController@create`
2. Рассадка игроков через `TableController@save`
3. Случайная раздача ролей через `TableController@randomRoles`
4. Запуск игры через `TableController@run`
5. Игра проходит на странице `/play/{table_id}`
6. Публикация результатов через `TableController@makePublicQuestion`
7. Скриншот результатов через `GameController@storeScreenshot`

### Рейтинг и результаты
- Рейтинг хранится в `Account.rating`
- `rating_before_game` - рейтинг до игры
- Результаты раундов в `GameResult`
- Итоговые победители в `GameWinner`

### Интеграция Telegram
- Webhook: `/telegram/webhook`
- Отправка ролей игрокам
- Уведомления о заявках
- Отправка результатов с фото

## Важно для исполнителя

### Запреты
- НЕ изменять бизнес-логику в Services без явного указания
- НЕ менять структуру БД без миграций
- НЕ трогать middleware авторизации

### Рекомендации
- Следить за связями в моделях при изменениях
- Проверять middleware при добавлении новых маршрутов
- Использовать существующие хелперы (AuthHelper, TgHelper)
- Сохранять файлы в `public/uploads/` для контента
