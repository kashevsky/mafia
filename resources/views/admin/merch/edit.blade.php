@extends('admin.layouts.main')
<style>
    .green {
        background-color: green !important;
    }

    .image-preview-item {
        position: relative;
        display: inline-block;
        margin: 10px;
        transition: opacity 0.3s ease;
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
        z-index: 10;
    }
</style>
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

    @if(session('success'))
        <div class="alert alert-success mt-3 mb-3">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('admin.merch.update', $merch->id) }}" method="POST" enctype="multipart/form-data">
        @csrf

        <input type="hidden" name="category_id" value="{{ $merch->category_id }}">

        <!-- Основная информация -->
        <div class="content-card">
            <h2 class="section-title"><i class="fas fa-info-circle"></i> Основная информация</h2>

            <div class="row">
                <div class="col-md-8 mb-3">
                    <label for="title" class="form-label">Заголовок</label>
                    <input type="text" class="form-control" id="title" name="title"
                        value="{{ old('title', $merch->title) }}" required>
                </div>
                <div class="col-md-8 mb-3">
                    <label for="price" class="form-label">Цена</label>
                    <input type="number" class="form-control" id="price" name="price"
                        value="{{ old('price', $merch->price) }}" required>
                </div>
                <div class="col-md-8 mb-3">
                    <label for="description" class="form-label">Описание</label>
                    <textarea class="form-control summernote" id="description" name="description">{{ old('description', $merch->description) }}</textarea>
                </div>
            </div>
        </div>

        <!-- Фото -->
        <div class="content-card">
            <h2 class="section-title"><i class="fas fa-image"></i> Фото</h2>

            <!-- Существующие изображения -->
            <div class="image-preview" id="imagePreview">
                @if (isset($merch) && $merch->src)
                    <div class="image-preview-item" id="preview-item-{{ $merch->id }}">
                        <img src="{{ $merch->src }}" alt="Product" style="max-width: 200px; max-height: 200px;">
                        <button type="button" class="remove-image" onclick="markImageForDeletion('{{ $merch->id }}')">
                            <i class="fas fa-times"></i>
                        </button>
                        <!-- Скрытый чекбокс удаления -->
                        <input type="checkbox" name="delete_images[]" value="{{ $merch->src }}"
                            id="delete-checkbox-{{ $merch->id }}" style="display: none;">
                    </div>
                @endif
            </div>

            <!-- Загрузка нового фото -->
            <div class="image-upload-container single-upload" onclick="document.getElementById('main-image-2').click()" style="cursor: pointer;">
                <i class="fas fa-cloud-upload-alt"></i>
                <h4>Загрузить фото</h4>
                <p class="text-muted">Нажмите или перетащите файл сюда</p>
                <input type="file" id="main-image-2" name="about_src_2" accept="image/*" style="display: none;"
                    onchange="previewMainImage2(event)">
            </div>
        </div>

        <!-- Кнопки действий -->
        <div class="content-card">
            <div class="action-buttons">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Сохранить изменения
                </button>
            </div>
        </div>
    </form>

    <script>
        function markImageForDeletion(id) {
            const checkbox = document.getElementById('delete-checkbox-' + id);
            if (checkbox) {
                checkbox.checked = true;
            }

            const imageItem = document.getElementById('preview-item-' + id);
            if (imageItem) {
                imageItem.style.opacity = '0.3';
                imageItem.style.pointerEvents = 'none';
            }
        }

        function previewMainImage2(event) {
            const file = event.target.files[0];
            if (!file) return;

            const reader = new FileReader();
            reader.onload = function(e) {
                let preview = document.getElementById('imagePreview');

                const existingPreview = preview.querySelector('.new-image-preview');
                if (existingPreview) {
                    existingPreview.remove();
                }

                const newPreview = document.createElement('div');
                newPreview.className = 'image-preview-item new-image-preview';
                newPreview.innerHTML = `
                    <img src="${e.target.result}" alt="New Product" style="max-width: 200px; max-height: 200px;">
                    <button type="button" class="remove-image" onclick="removeNewImagePreview()">
                        <i class="fas fa-times"></i>
                    </button>
                `;
                preview.appendChild(newPreview);
            };
            reader.readAsDataURL(file);
        }

        function removeNewImagePreview() {
            const preview = document.getElementById('imagePreview');
            const newPreview = preview.querySelector('.new-image-preview');
            if (newPreview) {
                newPreview.remove();
            }
            document.getElementById('main-image-2').value = '';
        }
    </script>
@endsection
