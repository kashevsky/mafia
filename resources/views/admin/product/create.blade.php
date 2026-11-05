@extends('admin.layouts.main')
@section('content')

    <div class="page-header">
        <h1><i class="fas fa-edit"></i> Редактирование товара</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/admin/dashboard">Главная</a></li>
                <li class="breadcrumb-item"><a href="/admin/products">Товары</a></li>
                <li class="breadcrumb-item active">Редактирование</li>
            </ol>
        </nav>
    </div>

    <form action="{{ route('admin.product.store') }}" method="POST" enctype="multipart/form-data" method="POST">
        @csrf


        <!-- Основная информация -->
        <div class="content-card">
            <h2 class="section-title"><i class="fas fa-info-circle"></i> Основная информация</h2>

            <div class="row">
                <div class="col-md-8 mb-3">
                    <label for="title" class="form-label">Название товара *</label>
                    <input type="text" class="form-control" id="title" name="title" required>
                </div>

                <div class="col-md-4 mb-3">
                    <label for="price" class="form-label">Цена</label>
                    <input type="text" class="form-control" id="price" name="price">
                </div>


                <div class="col-md-6 mb-3">
                    <label for="category_id" class="form-label">Категория</label>

                    <select class="form-select" id="category_id" name="category_id">
                        <option value="">Выберите категорию</option>
                        @foreach ($categories ?? [] as $category_)
                            <option value="{{ $category_->id }}"
                                {{ isset($category) && $category->id == $category_->id ? 'selected' : '' }}>
                                {{ $category_->title }}
                            </option>
                        @endforeach
                    </select>

                </div>
                <div class="col-md-12 mb-3">
                    <label for="description" class="form-label">Полное описание</label>
                    <textarea class="form-control summernote" id="description" name="description"></textarea>
                </div>
                @isset($category)
                    <input type="hidden" name="category_id" value="{{ $category->id }}">
                @endisset
                <div class="col-md-12 mb-3">
                    <label for="application" class="form-label">Применение</label>
                    <textarea class="form-control summernote" id="application" name="application"></textarea>
                </div>
                <div class="col-md-12 mb-3">
                    <label for="compound" class="form-label">Состав</label>
                    <textarea class="form-control summernote" id="compound" name="compound"></textarea>
                </div>
                <div class="col-md-12 mb-3">
                    <label for="contraindications" class="form-label">Противопоказания</label>
                    <textarea class="form-control summernote" id="contraindications" name="contraindications"></textarea>
                </div>
                <div class="col-md-12 mb-3">
                    <label for="storage" class="form-label">Условия хранения</label>
                    <textarea class="form-control summernote" id="storage" name="storage"></textarea>
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

        <!-- Фотографии товара -->
        <div class="content-card">
            <h2 class="section-title"><i class="fas fa-images"></i> Фотографии товара</h2>

            <div class="image-upload-container" onclick="document.getElementById('product-images').click()">
                <i class="fas fa-cloud-upload-alt"></i>
                <h4>Загрузить фотографии</h4>
                <p class="text-muted">Нажмите или перетащите файлы сюда</p>
                <input type="file" id="product-images" name="slides[]" multiple accept="image/*" style="display: none;">
            </div>

            <div class="image-preview" id="imagePreview">

                <!-- Существующие изображения -->
                @if (isset($product) && $product->slides)
                    @foreach ($product->slides as $slide)
                        <div class="image-preview-item">
                            <img src="/{{ $slide->src }}" alt="Product">
                            <a href="{{ route('admin.product.slide.delete', $slide->id) }}" type="button"
                                class="remove-image">
                                <i class="fas fa-times"></i>
                            </a>
                            <input type="hidden" name="existing_images[]" value="{{ $slide->id }}">
                        </div>
                    @endforeach
                @endif
            </div>
        </div>

        {{-- <!-- Рекомендуемые товары -->
        <div class="content-card">
            <h2 class="section-title"><i class="fas fa-star"></i> Рекомендуемые товары</h2>

            <div class="mb-3">
                <input type="text" class="form-control" id="searchRelated" placeholder="Поиск товаров...">
            </div>

            <div class="product-selector" id="relatedProducts">
                @foreach ($allProducts ?? [] as $prod)
                    <div class="product-item">
                        <input type="checkbox" name="related_products[]" value="{{ $prod->id }}"
                            {{ in_array($prod->id, $product->related_products ?? []) ? 'checked' : '' }}>
                        <img src="{{ asset('storage/' . $prod->image) }}" alt="{{ $prod->title }}">
                        <div>
                            <strong>{{ $prod->title }}</strong>
                            <div class="text-muted small">{{ $prod->price }} руб.</div>
                        </div>
                    </div>
                @endforeach

                <!-- Пример статических товаров -->
                <div class="product-item">
                    <input type="checkbox" name="related_products[]" value="1">
                    <img src="https://images.unsplash.com/photo-1556912173-3bb406ef7e77?w=100&h=100&fit=crop"
                        alt="Товар 1">
                    <div>
                        <strong>Кухня "Модерн"</strong>
                        <div class="text-muted small">150 000 руб.</div>
                    </div>
                </div>
                <div class="product-item">
                    <input type="checkbox" name="related_products[]" value="2">
                    <img src="https://images.unsplash.com/photo-1556909114-f6e7ad7d3136?w=100&h=100&fit=crop"
                        alt="Товар 2">
                    <div>
                        <strong>Стол обеденный</strong>
                        <div class="text-muted small">45 000 руб.</div>
                    </div>
                </div>
                <div class="product-item">
                    <input type="checkbox" name="related_products[]" value="3">
                    <img src="https://images.unsplash.com/photo-1555041469-a586c61ea9bc?w=100&h=100&fit=crop"
                        alt="Товар 3">
                    <div>
                        <strong>Диван угловой</strong>
                        <div class="text-muted small">85 000 руб.</div>
                    </div>
                </div>
                <div class="product-item">
                    <input type="checkbox" name="related_products[]" value="4">
                    <img src="https://images.unsplash.com/photo-1538688525198-9b88f6f53126?w=100&h=100&fit=crop"
                        alt="Товар 4">
                    <div>
                        <strong>Шкаф-купе</strong>
                        <div class="text-muted small">120 000 руб.</div>
                    </div>
                </div>
            </div>
        </div> --}}

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
