
document.addEventListener('DOMContentLoaded', function (event) {
    initRipple();
});

function initRipple() {
    // Вспомогательная функция для создания и запуска волны
    function createRipple(e, button) {
        const rect = button.getBoundingClientRect();

        // Вычисляем координаты относительно кнопки
        const x = e.clientX - rect.left;
        const y = e.clientY - rect.top;

        // Определяем максимальный размер
        const size = Math.max(rect.width, rect.height);

        // Создаем элемент волны
        const ripple = document.createElement('span');
        ripple.classList.add('ripple');

        // Задаем размеры и координаты
        ripple.style.width = ripple.style.height = `${size}px`;
        ripple.style.left = `${x - size / 2}px`;
        ripple.style.top = `${y - size / 2}px`;

        // Удаляем старые волны перед добавлением новой
        const oldRipple = button.querySelector('.ripple');
        if (oldRipple) {
            oldRipple.remove();
        }

        button.appendChild(ripple);

        // Удаляем элемент после завершения анимации
        ripple.addEventListener('animationend', () => {
            ripple.remove();
        });
    }

    // Навешиваем обработчики событий
    document.querySelectorAll('.ripple-button').forEach(button => {
        if (!button) return;

        // Эффект при клике
        button.addEventListener('click', function (e) {
            createRipple(e, this);
        });

        // Эффект при наведении (наведении курсора в область кнопки)
        button.addEventListener('mouseenter', function (e) {
            createRipple(e, this);
        });
    });
}
