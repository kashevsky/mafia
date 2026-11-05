@extends('admin.layouts.main')
@section('content')
    <div class="page-header">
        <h1><i class="fas fa-edit"></i> Редактирование информации</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/admin/dashboard">Главная</a></li>
                <li class="breadcrumb-item"><a href="/admin/products">Информация</a></li>
                <li class="breadcrumb-item active">Редактирование</li>
            </ol>
        </nav>
    </div>

    <form action="{{ route('admin.info.update') }}" method="POST" enctype="multipart/form-data" method="POST">
        @csrf

        <div class="content-card">
            <h2 class="section-title"><i class="fas fa-info-circle"></i> Основная информация</h2>

            <div class="row">
                {{-- <div class="col-md-8 mb-3">
                    <label for="title" class="form-label">Название товара *</label>
                    <input type="text" class="form-control" id="title" name="title" value="" required>
                </div> --}}
                <div class="col-md-8 mb-3">
                    <label for="phone" class="form-label">Телефон</label>
                    <input type="text" class="form-control" id="phone" name="phone" value="{{ $info->phone }}"
                        required>
                </div>
                <div class="col-md-8 mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="text" class="form-control" id="email" name="email" value="{{ $info->email }}"
                        required>
                </div>
                <div class="col-md-12 mb-3">
                    <label for="description" class="form-label">Текст о нас</label>
                    <textarea class="form-control summernote" id="description" name="about_text">{{ $info->about_text }}</textarea>
                </div>
                <div class="col-md-12 mb-3">
                    <label for="description" class="form-label">Текст для доставки</label>
                    <textarea class="form-control" id="description" name="delivery_text">{{ $info->delivery_text }}</textarea>
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
            </div>
        </div>


        <div class="content-card">
            <h2 class="section-title"><i class="fas fa-image"></i>Фото о нас 1</h2>
            <div class="image-upload-container single-upload" onclick="document.getElementById('main-image').click()">
                <i class="fas fa-cloud-upload-alt"></i>
                <h4>Загрузить фото</h4>
                <p class="text-muted">Нажмите или перетащите файл сюда</p>
                <input type="file" id="main-image" name="about_src" accept="image/*" style="display: none;"
                    onchange="previewMainImage(event)">
            </div>

            <div class="image-preview single-preview" id="mainImagePreview">
                <!-- Существующее изображение, если есть -->
                @if (isset($info) && $info->about_src)
                    <div class="image-preview-item">
                        <img src="{{ $info->about_src }}" alt="Главное фото статьи">
                        <button type="button" class="remove-image" onclick="removeMainImage()">
                            <i class="fas fa-times"></i>
                        </button>
                        <!-- Скрытое поле для отметки, что изображение сохраняется -->
                        <input type="hidden" name="keep_image" value="1">
                    </div>
                @endif
            </div>
        </div>


        <div class="content-card">
            <h2 class="section-title"><i class="fas fa-image"></i>Фото о нас 2</h2>
            <div class="image-upload-container single-upload" onclick="document.getElementById('main-image-2').click()">
                <i class="fas fa-cloud-upload-alt"></i>
                <h4>Загрузить фото</h4>
                <p class="text-muted">Нажмите или перетащите файл сюда</p>
                <input type="file" id="main-image-2" name="about_src_2" accept="image/*" style="display: none;"
                    onchange="previewMainImage2(event)">
            </div>
            <div class="image-preview single-preview" id="mainImagePreview2">
                <!-- Существующее изображение, если есть -->
                @if (isset($info) && $info->about_src_2)
                    <div class="image-preview-item">
                        <img src="{{ $info->about_src_2 }}" alt="Главное фото статьи">
                        <button type="button" class="remove-image" onclick="removeMainImage2()">
                            <i class="fas fa-times"></i>
                        </button>
                        <!-- Скрытое поле для отметки, что изображение сохраняется -->
                        <input type="hidden" name="keep_image2" value="1">
                    </div>
                @endif
            </div>
        </div>

        <script>
            // Функция для предпросмотра первого изображения
            function previewMainImage(event) {
                const file = event.target.files[0];
                if (!file) return;

                const reader = new FileReader();
                const previewContainer = document.getElementById('mainImagePreview');

                reader.onload = function(e) {
                    previewContainer.innerHTML = '';
                    const previewItem = document.createElement('div');
                    previewItem.className = 'image-preview-item';

                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.alt = 'Предпросмотр главного фото';

                    const removeBtn = document.createElement('button');
                    removeBtn.className = 'remove-image';
                    removeBtn.innerHTML = '<i class="fas fa-times"></i>';
                    removeBtn.type = 'button';
                    removeBtn.onclick = removeMainImage;

                    previewItem.appendChild(img);
                    previewItem.appendChild(removeBtn);
                    previewContainer.appendChild(previewItem);
                };

                reader.readAsDataURL(file);
            }

            // Функция для удаления первого изображения
            function removeMainImage() {
                document.getElementById('main-image').value = '';
                document.getElementById('mainImagePreview').innerHTML = '';

                const keepImageField = document.querySelector('input[name="keep_image"]');
                if (keepImageField) {
                    keepImageField.remove();
                }
            }

            // --- ДОБАВЛЕННЫЕ ФУНКЦИИ ДЛЯ ВТОРОГО ИЗОБРАЖЕНИЯ ---

            // Функция для предпросмотра второго изображения
            function previewMainImage2(event) {
                const file = event.target.files[0];
                if (!file) return;

                const reader = new FileReader();
                const previewContainer = document.getElementById('mainImagePreview2');

                reader.onload = function(e) {
                    previewContainer.innerHTML = '';
                    const previewItem = document.createElement('div');
                    previewItem.className = 'image-preview-item';

                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.alt = 'Предпросмотр второго фото';

                    const removeBtn = document.createElement('button');
                    removeBtn.className = 'remove-image';
                    removeBtn.innerHTML = '<i class="fas fa-times"></i>';
                    removeBtn.type = 'button';
                    removeBtn.onclick = removeMainImage2;

                    previewItem.appendChild(img);
                    previewItem.appendChild(removeBtn);
                    previewContainer.appendChild(previewItem);
                };

                reader.readAsDataURL(file);
            }

            // Функция для удаления второго изображения
            function removeMainImage2() {
                document.getElementById('main-image-2').value = '';
                document.getElementById('mainImagePreview2').innerHTML = '';

                const keepImageField2 = document.querySelector('input[name="keep_image2"]');
                if (keepImageField2) {
                    keepImageField2.remove();
                }
            }

            // --- ОБЩАЯ ОБРАБОТКА DRAG AND DROP ДЛЯ ОБОИХ КОНТЕЙНЕРОВ ---

            function preventDefaults(e) {
                e.preventDefault();
                e.stopPropagation();
            }

            function highlight(e) {
                e.currentTarget.style.backgroundColor = '#f0f9ff';
                e.currentTarget.style.borderColor = '#3b82f6';
            }

            function unhighlight(e) {
                e.currentTarget.style.backgroundColor = '';
                e.currentTarget.style.borderColor = '';
            }

            function handleDrop(e) {
                preventDefaults(e);
                const dt = e.dataTransfer;
                const files = dt.files;

                // Определяем, в какой контейнер был сделан drop
                const container = e.currentTarget;
                const inputId = container.querySelector('input[type="file"]').id;

                // Устанавливаем файлы в соответствующее поле ввода
                document.getElementById(inputId).files = files;

                // Вызываем соответствующую функцию предпросмотра
                if (inputId === 'main-image') {
                    previewMainImage({
                        target: {
                            files
                        }
                    });
                } else if (inputId === 'main-image-2') {
                    previewMainImage2({
                        target: {
                            files
                        }
                    });
                }
            }

            // Инициализация drag and drop для обоих контейнеров
            const uploadContainers = document.querySelectorAll('.image-upload-container.single-upload');

            uploadContainers.forEach(container => {
                ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                    container.addEventListener(eventName, preventDefaults, false);
                });

                ['dragenter', 'dragover'].forEach(eventName => {
                    container.addEventListener(eventName, highlight, false);
                });

                ['dragleave', 'drop'].forEach(eventName => {
                    container.addEventListener(eventName, unhighlight, false);
                });

                container.addEventListener('drop', handleDrop, false);
            });
        </script>

        <div class="content-card">
            <div class="action-buttons">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Сохранить изменения
                </button>
                <a href="/admin/products" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Отменить
                </a>
                <button type="submit" name="save_and_continue" value="1" class="btn btn-primary">
                    <i class="fas fa-check"></i> Сохранить и продолжить
                </button>
            </div>
        </div>
    </form>
@endsection
