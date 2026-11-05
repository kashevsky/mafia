<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Ваша роль в игре</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f4f6f9; padding: 20px; margin: 0;">
    <div style="max-width: 500px; margin: 0 auto; background-color: #ffffff; border-radius: 12px; padding: 25px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); text-align: center;">

        <h2 style="color: #2c3e50; margin-bottom: 5px;">Игра начинается!</h2>

        <div style="margin: 25px 0;">
            <img src="{{ $photoUrl }}" alt="{{ $role }}" style="width: 220px; max-width: 100%; border-radius: 12px; box-shadow: 0 6px 12px rgba(0,0,0,0.1);">
        </div>

        <h1 style="color: #e67e22; font-size: 26px; margin-bottom: 30px;">Ваша роль: {{ $role }}</h1>

        <!-- Кнопка: Правила роли -->
        <div style="margin-bottom: 12px;">

        </div>

        <!-- Кнопка: Чат клуба -->
        <div>
            <a href="{{ $chatUrl }}" target="_blank" style="display: inline-block; width: 80%; background-color: #2980b9; color: white; text-decoration: none; padding: 12px 20px; font-size: 16px; font-weight: bold; border-radius: 8px;">💬 Чат клуба</a>
        </div>

    </div>
</body>
</html>
