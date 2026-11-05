<style>
    .video-feed-section {
        padding: 16px;
    }

    .video-carousel {
        display: flex;
        gap: 16px;
        overflow-x: auto;
        padding-bottom: 16px;
        scrollbar-width: thin;
        scrollbar-color: #4a5568 #1a1b26;
    }

    .video-carousel::-webkit-scrollbar {
        height: 8px;
    }

    .video-carousel::-webkit-scrollbar-track {
        background: #2d3748;
        border-radius: 4px;
    }

    .video-carousel::-webkit-scrollbar-thumb {
        background: #4a5568;
        border-radius: 4px;
    }

    .video-item {
        flex: 0 0 320px;
        text-decoration: none;
        transition: transform 0.3s ease;
    }

    .video-item:hover {
        transform: scale(1.05);
    }

    .video-thumbnail {
        position: relative;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.4);
        margin-bottom: 12px;
        aspect-ratio: 16 / 9;
    }

    .video-thumbnail img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .play-button {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 60px;
        height: 60px;
        background: rgba(79, 70, 229, 0.9);
        background: #b91c1c;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: background 0.2s;
    }

    .video-item:hover .play-button {
        background: #667eea;
    }

    .video-duration {
        position: absolute;
        bottom: 8px;
        right: 8px;
        background: rgba(0, 0, 0, 0.7);
        color: white;
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 12px;
    }

    .video-info {
        padding: 0 4px;
    }

    .video-title {
        font-weight: 600;
        color: #e2e8f0;
        margin-bottom: 4px;
        line-height: 1.3;
    }

    .video-meta {
        color: #94a3b8;
        font-size: 12px;
    }

    /* Анимации */
    @keyframes slideIn {
        from {
            transform: translateX(100%);
            opacity: 0;
        }

        to {
            transform: translateX(0);
            opacity: 1;
        }
    }

    .video-item {
        animation: slideIn 0.4s ease forwards;
    }

    .video-item:nth-child(2) {
        animation-delay: 0.1s;
    }

    .video-item:nth-child(3) {
        animation-delay: 0.2s;
    }

    /* Адаптивность */
    @media (max-width: 768px) {
        .video-carousel {
            gap: 12px;
        }

        .video-item {
            flex: 0 0 280px;
        }
    }
</style>
<!-- Подключаем jQuery (требуется для Fancybox 3) -->
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>

<!-- Подключаем Fancybox CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.css" />

<!-- Подключаем Fancybox JS -->
<script src="https://cdn.jsdelivr.net/npm/@fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.js"></script>

<div class="video-feed-section">
    <div class="video-carousel">
        @foreach ($club->videos as $video)
            <a href="{{ $video->src }}" data-fancybox="video-gallery" data-caption="{{ $video->title }}"
                class="video-item">
                <div class="video-thumbnail">
                    <img src="{{ $video->preview }}" alt="Видео 1">
                    <div class="play-button">
                        <svg width="24" height="24" fill="white" viewBox="0 0 24 24">
                            <path d="M8 5v14l11-7z" />
                        </svg>
                    </div>
                    <div class="video-duration">{{ $video->duration }}</div>
                </div>
                <div class="video-info">
                    <div class="video-title">{{ $video->title }}</div>
                    <div class="video-meta">{{ $video->created_at_formatted }}</div>
                </div>
            </a>
        @endforeach
    </div>
</div>

<script>
    // Добавляем методы в объект publicProfile()
    openVideo(videoUrl) {
            this.isFullscreen = true;
            this.currentVideo = videoUrl + '?autoplay=1';
            document.body.style.overflow = 'hidden'; // Блокируем скролл страницы
        },

        closeVideo() {
            this.isFullscreen = false;
            this.currentVideo = null;
            document.body.style.overflow = 'auto'; // Возвращаем скролл
        }
</script>
<script>
    $(document).ready(function() {
        // Инициализация Fancybox для видео
        $('[data-fancybox="video-gallery"]').fancybox({
            // Настройки для видео
            type: 'video',
            video: {
                autoplay: true,
                ratio: 16 / 9 // Соотношение сторон 16:9
            },
            // Анимация открытия
            animationEffect: 'zoom-in',
            animationDuration: 300,
            // Анимация закрытия
            closeEffect: 'zoom-out',
            closeDuration: 200,
            // Кнопки навигации
            buttons: [
                'zoom',
                'fullScreen',
                'thumbs',
                'close'
            ],
            // Подпись под видео
            caption: function(instance, item) {
                return $(this).data('caption');
            },
            // Закруглённые углы
            slideShowArrows: true,
            arrows: true,
            clickContent: false,
            protect: true,
            // Стили для тёмной темы
            afterLoad: function(instance, current) {
                // Добавляем кастомные стили для тёмной темы
                $('.fancybox-slide').css({
                    'background-color': 'rgba(10, 10, 25, 0.9)',
                    'border-radius': '12px'
                });
            },
            // Закрытие по ESC
            keyboard: true,
            // Закрытие при клике вне видео
            clickOutside: true
        });

        // Дополнительная анимация для элементов ленты
        $('.video-item').each(function(index) {
            $(this).css('animation-delay', index * 0.1 + 's');
        });
    });
</script>
