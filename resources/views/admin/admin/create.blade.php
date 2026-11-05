@extends('admin.layouts.main')
<style>
    .green {
        background-color: green !important;
    }
</style>
@section('content')
    <div class="page-header">
        <h1><i class="fas fa-edit"></i>Создание админа</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/admin/dashboard">Главная</a></li>
                <li class="breadcrumb-item"><a href="/admin/products">Админы</a></li>
                <li class="breadcrumb-item active">Создать</li>
            </ol>
        </nav>
    </div>

    <form action="{{ route('admin.admin.store') }}" method="POST" enctype="multipart/form-data">
        @csrf


        <!-- Основная информация -->
        <div class="content-card">
            <h2 class="section-title"><i class="fas fa-info-circle"></i> Основная информация</h2>


            <div class="row">
                <div class="col-md-8 mb-3">
                    <label for="name" class="form-label">Имя *</label>
                    <input type="text" class="form-control" id="name" name="name" required>
                </div>
                <div class="col-md-8 mb-3">
                    <label for="password" class="form-label">Пароль *</label>
                    <input type="text" class="form-control" id="password" name="password" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="club_id" class="form-label">Какой клуб администрирует?</label>
                    <select class="form-select" id="club_id" name="club_id" required>
                        <option value="">Выберите клуб</option>
                        @foreach ($clubs ?? [] as $club)
                            <option value="{{ $club->id }}">
                                {{ $club->title }} = {{ $club->id }}
                            </option>
                        @endforeach


                    </select>
                </div>


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

        {{-- <!-- Фотографии товара -->
        <div class="content-card">
            <h2 class="section-title"><i class="fas fa-images"></i> Фотография</h2>

            <div class="image-upload-container" onclick="document.getElementById('product-images').click()">
                <i class="fas fa-cloud-upload-alt"></i>
                <h4>Загрузить фотографию</h4>
                <p class="text-muted">Нажмите или перетащите файлы сюда</p>
                <input type="file" id="product-images" name="image" accept="image/*" style="display: none;">
            </div>

            <div class="image-preview" id="imagePreview">
                <!-- Существующие изображения -->
                @if (isset($product) && $product->images)
                    @foreach ($product->images as $image)
                        <div class="image-preview-item">
                            <img src="{{ asset('storage/' . $image->path) }}" alt="Product">
                            <button type="button" class="remove-image" onclick="removeExistingImage({{ $image->id }})">
                                <i class="fas fa-times"></i>
                            </button>
                            <input type="hidden" name="existing_images[]" value="{{ $image->id }}">
                        </div>
                    @endforeach
                @endif
            </div>
        </div> --}}


        <br><br>
        <!-- Кнопки действий -->
        <div class="content-card">
            <div class="action-buttons">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Сохранить изменения
                </button>
                <a href="/admin/products" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Отменить
                </a>
            </div>
        </div>
    </form>
@endsection
