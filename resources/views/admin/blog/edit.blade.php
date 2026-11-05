@extends('admin.layouts.main')
@section('content')
    <style>
        /* Контейнер загрузки одного изображения */
        .single-upload {
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .single-upload:hover {
            background-color: #f0f9ff;
            border-color: #3b82f6;
            transform: translateY(-2px);
        }

        /* Превью одного изображения */
        .single-preview {
            min-height: 100px;
        }

        .image-preview-item {
            position: relative;
            display: inline-block;
            margin: 10px 10px 0 0;
        }

        .image-preview-item img {
            width: 200px;
            height: 150px;
            object-fit: cover;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .remove-image {
            position: absolute;
            top: -8px;
            right: -8px;
            background: #ef4444;
            color: white;
            border: none;
            width: 24px;
            height: 24px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 12px;
            transition: background 0.3s ease;
        }

        .remove-image:hover {
            background: #dc2626;
        }

        /* Стили для состояния загрузки */
        .uploading .image-upload-container {
            opacity: 0.7;
            pointer-events: none;
        }

        /* Адаптивность */
        @media (max-width: 768px) {
            .image-preview-item img {
                width: 150px;
                height: 112px;
            }

            .remove-image {
                top: -6px;
                right: -6px;
                width: 20px;
                height: 20px;
            }
        }
    </style>
    <script>
        // Функция для предпросмотра загруженного изображения
        function previewMainImage(event) {
            const file = event.target.files[0];
            if (!file) return;

            const reader = new FileReader();
            const previewContainer = document.getElementById('mainImagePreview');

            reader.onload = function(e) {
                // Очищаем контейнер
                previewContainer.innerHTML = '';

                // Создаём элемент предпросмотра
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

        // Функция для удаления изображения
        function removeMainImage() {
            // Очищаем поле загрузки
            document.getElementById('main-image').value = '';

            // Очищаем превью
            document.getElementById('mainImagePreview').innerHTML = '';

            // Удаляем скрытое поле keep_image, если оно есть
            const keepImageField = document.querySelector('input[name="keep_image"]');
            if (keepImageField) {
                keepImageField.remove();
            }
        }

        // Обработчик для drag and drop
        const uploadContainer = document.querySelector('.image-upload-container.single-upload');

        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            uploadContainer.addEventListener(eventName, preventDefaults, false);
        });

        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }

        ['dragenter', 'dragover'].forEach(eventName => {
            uploadContainer.addEventListener(eventName, highlight, false);
        });

        ['dragleave', 'drop'].forEach(eventName => {
            uploadContainer.addEventListener(eventName, unhighlight, false);
        });

        function highlight() {
            uploadContainer.style.backgroundColor = '#f0f9ff';
            uploadContainer.style.borderColor = '#3b82f6';
        }

        function unhighlight() {
            uploadContainer.style.backgroundColor = '';
            uploadContainer.style.borderColor = '';
        }

        uploadContainer.addEventListener('drop', handleDrop, false);

        function handleDrop(e) {
            const dt = e.dataTransfer;
            const files = dt.files;
            document.getElementById('main-image').files = files;
            previewMainImage({
                target: {
                    files
                }
            });
        }
    </script>
    <div class="page-header">
        <h1><i class="fas fa-edit"></i> Редактирование товара</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/admin/dashboard">Главная</a></li>
                <li class="breadcrumb-item"><a href="/admin/products">Блог</a></li>
                <li class="breadcrumb-item active">Создать запись</li>
            </ol>
        </nav>
    </div>

    <form action="{{ route('admin.blog.update') }}" method="POST" enctype="multipart/form-data" method="POST">
        @csrf


        <!-- Основная информация -->
        <div class="content-card">
            <h2 class="section-title"><i class="fas fa-info-circle"></i> Основная информация</h2>

            <div class="row">
                <div class="col-md-8 mb-3">
                    <label for="title" class="form-label">Заголовок</label>
                    <input type="text" class="form-control" id="title" name="title" value="{{ $blog->title }}">
                </div>
                <input type="hidden" name="id" value="{{ $blog->id }}">

                <div class="col-md-12 mb-3">
                    <label for="description" class="form-label">Текст</label>
                    <textarea class="form-control summernote" id="text" name="text">{{ $blog->text }}</textarea>
                </div>

                <div class="col-md-8 mb-3">
                    <label for="title" class="form-label">Автор</label>
                    <input type="text" class="form-control" id="author" name="author" value="{{ $blog->author }}">
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
                        loadSummernote();
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



                {{-- <div class="col-md-4 mb-3">
                            <label for="status" class="form-label">Статус</label>
                            <select class="form-select" id="status" name="status">
                                <option value="active" {{ old('status', $product->status ?? '') == 'active' ? 'selected' : '' }}>Активен</option>
                                <option value="draft" {{ old('status', $product->status ?? '') == 'draft' ? 'selected' : '' }}>Черновик</option>
                                <option value="inactive" {{ old('status', $product->status ?? '') == 'inactive' ? 'selected' : '' }}>Неактивен</option>
                            </select>
                        </div> --}}
                {{--
                        <div class="col-md-4 mb-3">
                            <label for="featured" class="form-label">Рекомендуемый товар</label>
                            <select class="form-select" id="featured" name="featured">
                                <option value="0" {{ old('featured', $product->featured ?? 0) == 0 ? 'selected' : '' }}>Нет</option>
                                <option value="1" {{ old('featured', $product->featured ?? 0) == 1 ? 'selected' : '' }}>Да</option>
                            </select>
                        </div> --}}
            </div>
        </div>

        <!-- Главное фото -->
        <div class="content-card">
            <h2 class="section-title"><i class="fas fa-image"></i> Главное фото</h2>

            <div class="image-upload-container single-upload" onclick="document.getElementById('main-image').click()">
                <i class="fas fa-cloud-upload-alt"></i>
                <h4>Загрузить главное фото</h4>
                <p class="text-muted">Нажмите или перетащите файл сюда</p>
                <input type="file" id="main-image" name="image" accept="image/*" style="display: none;"
                    onchange="previewMainImage(event)">
            </div>

            <div class="image-preview single-preview" id="mainImagePreview">
                <!-- Существующее изображение, если есть -->
                @if (isset($blog) && $blog->src)
                    <div class="image-preview-item">
                        <img src="{{ $blog->src }}" alt="Главное фото статьи">
                        <button type="button" class="remove-image" onclick="removeMainImage()">
                            <i class="fas fa-times"></i>
                        </button>
                        <!-- Скрытое поле для отметки, что изображение сохраняется -->
                        <input type="hidden" name="keep_image" value="1">
                    </div>
                @endif
            </div>
        </div>


        <!-- Кнопки действий -->
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
