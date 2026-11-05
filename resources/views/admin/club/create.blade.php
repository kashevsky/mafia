@extends('admin.layouts.main')
@section('content')
<style>
    .green {
        background-color: green !important;
    }

    .image-preview-container {
        margin-top: 20px;
        text-align: center;
    }

    .image-preview-container img {
        max-width: 100%;
        max-height: 300px;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    .image-preview-container p {
        color: #6c757d;
        font-style: italic;
    }

    .video-item {
        padding: 15px;
        margin-bottom: 15px;
        border: 1px solid #dee2e6;
        border-radius: 8px;
        background-color: #f8f9fa;
        position: relative;
    }

    .video-item-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 10px;
    }

    .video-item-number {
        font-weight: bold;
        color: #495057;
    }

    .remove-video-btn {
        background-color: #dc3545;
        color: white;
        border: none;
        padding: 5px 15px;
        border-radius: 5px;
        cursor: pointer;
        font-size: 14px;
    }

    .remove-video-btn:hover {
        background-color: #c82333;
    }

    .add-video-btn {
        background-color: #28a745;
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 5px;
        cursor: pointer;
        font-size: 16px;
        margin-top: 10px;
    }

    .add-video-btn:hover {
        background-color: #218838;
    }

    .video-preview-container {
        margin-top: 10px;
        text-align: center;
    }

    .video-preview-container video {
        max-width: 100%;
        max-height: 300px;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    .video-upload-container {
        border: 2px dashed #007bff;
        border-radius: 8px;
        padding: 20px;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s;
        background-color: white;
    }

    .video-upload-container:hover {
        background-color: #f0f8ff;
        border-color: #0056b3;
    }

    .video-upload-container i {
        font-size: 48px;
        color: #007bff;
        margin-bottom: 10px;
    }

    .videos-list-container {
        margin-top: 20px;
    }

    .preview-upload-container {
        border: 2px dashed #fd7e14;
        border-radius: 8px;
        padding: 20px;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s;
        background-color: #fff8f0;
    }

    .preview-upload-container:hover {
        background-color: #ffe8cc;
        border-color: #e8590c;
    }

    .preview-upload-container i {
        font-size: 40px;
        color: #fd7e14;
        margin-bottom: 8px;
    }

    .preview-upload-label {
        font-weight: 600;
        color: #495057;
        margin-bottom: 4px;
    }
</style>
@section('content')
    <div class="page-header">
        <h1><i class="fas fa-edit"></i> Создание клуба</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/admin/dashboard">Главная</a></li>
                <li class="breadcrumb-item"><a href="/admin/products">Клубы</a></li>
                <li class="breadcrumb-item active">Создать</li>
            </ol>
        </nav>
    </div>

    <form action="{{ route('admin.club.store') }}" method="POST" enctype="multipart/form-data">
        @csrf


        <!-- Основная информация -->
        <div class="content-card">
            <h2 class="section-title"><i class="fas fa-info-circle"></i> Основная информация</h2>


            <div class="row">
                <div class="col-md-8 mb-3">
                    <label for="title" class="form-label">Название клуба *</label>
                    <input type="text" class="form-control" id="title" name="title" required>
                </div>
                <div class="col-md-8 mb-3">
                    <label for="totem" class="form-label">Тотем *</label>
                    <input type="text" class="form-control" id="title" name="totem" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="category_id" class="form-label">Город</label>
                    <select class="form-select" id="city_id" name="city_id">
                        <option value="">Выберите город</option>
                        @foreach ($cities ?? [] as $city)
                            <option value="{{ $city->id }}"
                                {{ old('category_id', $product->city_id ?? '') == $city->id ? 'selected' : '' }}>
                                {{ $city->title }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-12 mb-3">
                    <label for="description" class="form-label">Полное описание</label>
                    <textarea class="form-control summernote" id="description" name="description"></textarea>
                </div>
                <!-- jQuery (обязательно первым) -->
                <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

                <!-- Bootstrap JS -->
                <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

                <!-- Summernote (после jQuery) -->
                <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>

                <!-- Скрипт инициализации с диагностикой -->
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        console.log('=== ДИАГНОСТИКА ЗАГРУЖЕННЫХ БИБЛИОТЕК ===');

                        // Проверка jQuery
                        if (typeof jQuery === 'undefined') {
                            console.error('❌ ОШИБКА: jQuery не загружен!');
                            return;
                        }
                        console.log('✅ jQuery загружен успешно');

                        // Проверка Summernote
                        if (typeof $.fn.summernote === 'undefined') {
                            console.error('❌ ОШИБКА: Summernote не загружен!');

                            // Дополнительная диагностика
                            console.log('Доступные плагины jQuery:', Object.keys($.fn));
                            console.log('Все свойства jQuery:', $.fn);

                            // Попытка загрузить Summernote повторно
                            loadSummernote();
                            return;
                        }
                        console.log('✅ Summernote загружен успешно');

                        // Инициализация редакторов
                        initSummernoteEditors();
                    });

                    function initSummernoteEditors() {
                        const summernoteElements = document.querySelectorAll('.summernote');

                        if (summernoteElements.length === 0) {
                            console.log('⚠️ Элементы с классом .summernote не найдены');
                            return;
                        }

                        console.log(`✅ Найдено ${summernoteElements.length} элементов для инициализации`);


                        summernoteElements.forEach(function(element) {
                            $(element).summernote({
                                lang: 'ru-RU',
                                height: 300,
                                placeholder: 'Введите текст здесь...',
                                toolbar: [
                                    ['style', ['style']],
                                    ['font', ['bold', 'underline', 'clear']],
                                    ['color', ['color']],
                                    ['para', ['ul', 'ol', 'paragraph']],
                                    ['table', ['table']],
                                    ['insert', ['link', 'picture', 'video']],
                                    ['view', ['fullscreen', 'codeview', 'help']]
                                ]
                            });
                        });
                    }

                    function loadSummernote() {
                        const script = document.createElement('script');
                        script.src = 'https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js';
                        script.onload = function() {
                            console.log('✅ Summernote загружен повторно');
                            initSummernoteEditors();
                        };
                        script.onerror = function() {
                            console.error('❌ Не удалось загрузить Summernote даже повторно!');
                        };
                        document.head.appendChild(script);
                    }
                </script>
                <div class="content-card">
                    <h2 class="section-title"><i class="fas fa-image"></i>Аватар</h2>
                    <div class="image-upload-container single-upload"
                        onclick="document.getElementById('main-image-2').click()">
                        <i class="fas fa-cloud-upload-alt"></i>
                        <h4>Загрузить фото</h4>
                        <p class="text-muted">Нажмите или перетащите файл сюда</p>
                        <input type="file" id="main-image-2" name="avatar" accept="image/*" style="display: none;"
                            onchange="previewMainImage2(event)">
                    </div>
                </div>
                <div class="image-preview-container" id="main-image-2-preview">
                    <p>Превью изображения появится здесь после выбора файла</p>
                </div>
                <script>
                    function previewMainImage2(event) {
                        const file = event.target.files[0];
                        const previewContainer = document.getElementById('main-image-2-preview');

                        // Очищаем предыдущий превью
                        previewContainer.innerHTML = '';

                        if (file) {
                            // Проверяем, что файл — изображение
                            if (!file.type.match('image.*')) {
                                previewContainer.innerHTML = '<p style="color: red;">Пожалуйста, выберите файл изображения</p>';
                                return;
                            }

                            const reader = new FileReader();

                            reader.onload = function(e) {
                                const img = document.createElement('img');
                                img.src = e.target.result;
                                img.alt = 'Превью аватара клуба';
                                img.style.maxWidth = '100%';
                                img.style.maxHeight = '300px';
                                img.style.borderRadius = '8px';
                                img.style.boxShadow = '0 2px 10px rgba(0, 0, 0, 0.1)';

                                previewContainer.appendChild(img);
                            };

                            reader.readAsDataURL(file);
                        } else {
                            previewContainer.innerHTML = '<p>Превью изображения появится здесь после выбора файла</p>';
                        }
                    }
                </script>


            </div>
        </div>
        <br><br>

        <!-- Секция видеороликов -->
        <div class="content-card">
            <h2 class="section-title"><i class="fas fa-video"></i> Видеоролики клуба</h2>

            <div class="videos-list-container" id="videos-container">
                <!-- Здесь будут добавляться видеоролики -->
            </div>

            <button type="button" class="add-video-btn" onclick="addVideoItem()">
                <i class="fas fa-plus"></i> Добавить видеоролик
            </button>
        </div>

        <script>
            let videoCounter = 0;

            function addVideoItem() {
                videoCounter++;
                const videosContainer = document.getElementById('videos-container');

                const videoItem = document.createElement('div');
                videoItem.className = 'video-item';
                videoItem.id = 'video-item-' + videoCounter;

                videoItem.innerHTML = `
    <div class="video-item-header">
        <span class="video-item-number">Видеоролик #${videoCounter}</span>
        <button type="button" class="remove-video-btn" onclick="removeVideoItem(${videoCounter})">
            <i class="fas fa-trash"></i> Удалить
        </button>
    </div>

    <div class="row">
        <div class="col-md-12 mb-3">
            <label for="video_title_${videoCounter}" class="form-label">Заголовок видео *</label>
            <input type="text" class="form-control" id="video_title_${videoCounter}"
                   name="videos[${videoCounter}][title]" required>
        </div>

        <div class="col-md-12 mb-3">
            <label class="form-label">Видеофайл *</label>
            <div class="video-upload-container" onclick="document.getElementById('video_file_${videoCounter}').click()">
                <i class="fas fa-cloud-upload-alt"></i>
                <h4>Загрузить видео</h4>
                <p class="text-muted">Нажмите или перетащите видеофайл сюда</p>
                <input type="file" id="video_file_${videoCounter}"
                       name="videos[${videoCounter}][file]"
                       accept="video/*"
                       style="display: none;"
                       onchange="previewVideo(${videoCounter}, event)" required>
            </div>
        </div>

        <div class="col-md-12">
            <div class="video-preview-container" id="video_preview_${videoCounter}">
                <p style="color: #6c757d; font-style: italic;">Превью видео появится здесь после выбора файла</p>
            </div>
        </div>

        <!-- Блок для превью картинки -->
        <div class="col-md-12 mb-3">
            <p class="preview-upload-label"><i class="fas fa-image"></i> Превью-изображение для видео</p>
            <div class="preview-upload-container"
                onclick="document.getElementById('preview_image_${videoCounter}').click()">
                <i class="fas fa-image"></i>
                <div style="font-size:16px; font-weight:600; color:#fd7e14; margin-bottom:4px;">Загрузить превью для видео</div>
                <p class="text-muted" style="margin:0;">Нажмите или перетащите изображение сюда (JPG, PNG)</p>
                <input type="file" id="preview_image_${videoCounter}"
                       name="videos[${videoCounter}][preview]"
                       accept="image/*"
                       style="display: none;"
                       onchange="previewVideoPreview(${videoCounter}, event)">
            </div>
        </div>

        <div class="col-md-12">
            <div class="image-preview-container" id="preview_preview_${videoCounter}">
                <p style="color: #6c757d; font-style: italic;">Превью изображения появится здесь после выбора файла</p>
            </div>
        </div>
    </div>
`;


                videosContainer.appendChild(videoItem);
            }

            function removeVideoItem(itemId) {
                const videoItem = document.getElementById('video-item-' + itemId);
                if (videoItem) {
                    if (confirm('Вы уверены, что хотите удалить этот видеоролик?')) {
                        videoItem.remove();
                    }
                }
            }

            function previewVideo(itemId, event) {
                const file = event.target.files[0];
                const previewContainer = document.getElementById('video_preview_' + itemId);

                // Очищаем предыдущий превью
                previewContainer.innerHTML = '';

                if (file) {
                    // Проверяем, ч��о файл — видео
                    if (!file.type.match('video.*')) {
                        previewContainer.innerHTML = '<p style="color: red;">Пожалуйста, выберите видеофайл</p>';
                        return;
                    }

                    const reader = new FileReader();

                    reader.onload = function(e) {
                        const video = document.createElement('video');
                        video.src = e.target.result;
                        video.controls = true;
                        video.style.maxWidth = '100%';
                        video.style.maxHeight = '300px';
                        video.style.borderRadius = '8px';
                        video.style.boxShadow = '0 2px 10px rgba(0, 0, 0, 0.1)';

                        const fileInfo = document.createElement('p');
                        fileInfo.style.marginTop = '10px';
                        fileInfo.style.color = '#495057';
                        fileInfo.textContent = `Файл: ${file.name} (${(file.size / 1024 / 1024).toFixed(2)} МБ)`;

                        previewContainer.appendChild(video);
                        previewContainer.appendChild(fileInfo);
                    };

                    reader.readAsDataURL(file);
                } else {
                    previewContainer.innerHTML =
                        '<p style="color: #6c757d; font-style: italic;">Превью видео появится здесь после выбора файла</p>';
                }
            }

            function previewVideoPreview(itemId, event) {
                const file = event.target.files[0];
                const previewContainer = document.getElementById('preview_preview_' + itemId);

                // Очищаем предыдущий превью
                previewContainer.innerHTML = '';

                if (file) {
                    // Проверяем, что файл — изображение
                    if (!file.type.match('image.*')) {
                        previewContainer.innerHTML = '<p style="color: red;">Пожалуйста, выберите файл изображения</p>';
                        return;
                    }

                    const reader = new FileReader();

                    reader.onload = function(e) {
                        const img = document.createElement('img');
                        img.src = e.target.result;
                        img.alt = 'Превью для видеоролика';
                        img.style.maxWidth = '100%';
                        img.style.maxHeight = '200px';
                        img.style.borderRadius = '8px';
                        img.style.boxShadow = '0 2px 10px rgba(0, 0, 0, 0.1)';

                        const fileInfo = document.createElement('p');
                        fileInfo.style.marginTop = '10px';
                        fileInfo.style.color = '#495057';
                        fileInfo.textContent = `Файл: ${file.name} (${(file.size / 1024).toFixed(2)} КБ)`;

                        previewContainer.appendChild(img);
                        previewContainer.appendChild(fileInfo);
                    };

                    reader.readAsDataURL(file);
                } else {
                    previewContainer.innerHTML =
                        '<p style="color: #6c757d; font-style: italic;">Превью изображения появится здесь после выбора файла</p>';
                }
            }
        </script>

        <br><br>

        <div class="form-actions">
            <button type="submit" class="btn btn-success">
                <i class="fas fa-save"></i> Сохранить клуб
            </button>
            <a href="/admin/products" class="btn btn-secondary">
                <i class="fas fa-times"></i> Отменить
            </a>
        </div>
    </form>
@endsection
