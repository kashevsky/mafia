@extends('admin.layouts.main')

<style>
    .green {
        background-color: green !important;
    }

    .image-preview-item {
        position: relative;
        display: inline-block;
        margin: 10px;
    }

    .remove-image {
        position: absolute;
        top: 5px;
        right: 5px;
        background: red;
        color: white;
        border: none;
        border-radius: 50%;
        width: 25px;
        height: 25px;
        cursor: pointer;
    }
</style>

@section('content')
    <div class="page-header">
        <h1><i class="fas fa-plus"></i> Создание товара</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/admin/dashboard">Главная</a></li>
                <li class="breadcrumb-item"><a href="/admin/products">Товары</a></li>
                <li class="breadcrumb-item active">Создать</li>
            </ol>
        </nav>
    </div>

    @if(session('success'))
        <div class="alert alert-success mt-3 mb-3">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('admin.merch.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <!-- Основная информация -->
        <div class="content-card">
            <h2 class="section-title"><i class="fas fa-info-circle"></i> Основная информация</h2>

            <div class="row">
                <div class="col-md-8 mb-3">
                    <label for="title" class="form-label">Заголовок *</label>
                    <input type="text" class="form-control" id="title" name="title"
                        value="{{ old('title') }}" required>
                </div>

                <div class="col-md-8 mb-3">
                    <label for="price" class="form-label">Цена *</label>
                    <input type="number" class="form-control" id="price" name="price"
                        value="{{ old('price') }}" required>
                </div>

                <div class="col-md-12 mb-3">
                    <label for="description" class="form-label">Описание</label>
                    <textarea class="form-control summernote" id="description" name="description">{{ old('description') }}</textarea>
                </div>
            </div>
        </div>

        <!-- Фото -->
        <div class="content-card">
            <h2 class="section-title"><i class="fas fa-image"></i> Фото товара</h2>

            <!-- Превью загружаемых изображений -->
            <div class="image-preview" id="imagePreview">
                <p style="color: #6c757d; font-style: italic;">Превью изображений появится здесь после выбора файлов</p>
            </div>

            <!-- Загрузка фото -->
            <div class="image-upload-container single-upload" onclick="document.getElementById('main-image').click()" style="cursor: pointer;">
                <i class="fas fa-cloud-upload-alt"></i>
                <h4>Загрузить фото</h4>
                <p class="text-muted">Нажмите или перетащите файл сюда</p>
                <input type="file" id="main-image" name="src" accept="image/*" style="display: none;"
                    onchange="previewMainImage(event)" required>
            </div>
        </div>

        <!-- Кнопки действий -->
        <div class="content-card">
            <div class="action-buttons">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Создать товар
                </button>
                <a href="/admin/products" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Отмена
                </a>
            </div>
        </div>
    </form>

    <script>
        function previewMainImage(event) {
            const file = event.target.files[0];
            if (!file) return;

            if (!file.type.match('image.*')) {
                alert('Пожалуйста, выберите файл изображения');
                return;
            }

            const reader = new FileReader();
            reader.onload = function(e) {
                const previewContainer = document.getElementById('imagePreview');
                previewContainer.innerHTML = '';

                const newPreview = document.createElement('div');
                newPreview.className = 'image-preview-item';
                newPreview.innerHTML = `
                    <img src="${e.target.result}" alt="Product" style="max-width: 200px; max-height: 200px;">
                    <button type="button" class="remove-image" onclick="removeNewImagePreview()">
                        <i class="fas fa-times"></i>
                    </button>
                `;
                previewContainer.appendChild(newPreview);
            };
            reader.readAsDataURL(file);
        }

        function removeNewImagePreview() {
            const preview = document.getElementById('imagePreview');
            const newPreview = preview.querySelector('.image-preview-item');
            if (newPreview) {
                newPreview.remove();
            }
            document.getElementById('main-image').value = '';
            preview.innerHTML = '<p style="color: #6c757d; font-style: italic;">Превью изображений появится здесь после выбора файлов</p>';
        }
    </script>
@endsection
