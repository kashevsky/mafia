@extends('admin.layouts.main')
@section('content')
    <div class="page-header">
        <h1><i class="fas fa-edit"></i> Редактирование категории</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/admin/dashboard">Главная</a></li>
                <li class="breadcrumb-item"><a href="/admin/products">Категории</a></li>
                <li class="breadcrumb-item active">Редактирование</li>
            </ol>
        </nav>
    </div>

    <form action="{{ route('admin.category.update') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <input type="hidden" name="category_id" value="{{ $category->id }}">
        <!-- Основная информация -->
        <div class="content-card">
            <h2 class="section-title"><i class="fas fa-info-circle"></i> Основная информация</h2>


            <div class="row">
                <div class="col-md-8 mb-3">
                    <label for="title" class="form-label">Название категории *</label>
                    <input type="text" class="form-control" id="title" name="title" value="{{ $category->title }}"
                        required>
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

        <!-- Таблица товаров -->
        @if (count($category->products) == 0)
            <p style="text-align: center">Нет товаров</p>
        @else
            <div class="products-table">


                <table class="w-100">
                    <thead>
                        <tr class="table-header">
                            <th class="table-cell">Название товара</th>
                            <th class="table-cell">Цена</th>
                            <th class="table-cell">Категория</th>
                            <th class="table-cell" style="width: 180px;">Действия</th>
                        </tr>
                    </thead>

                    <tbody id="productsTableBody">


                        @foreach ($category->products as $product)
                            <tr>
                                <td class="table-cell product-name">{{ $product->title }}</td>
                                <td class="table-cell product-price">{{ $product->price }} ₽</td>
                                <td class="table-cell">Категория A</td>
                                <td class="table-cell">
                                    <div class="action-buttons">
                                        <a href="{{ route('admin.product.edit', $product->id) }}"
                                            class="btn-action btn-edit">
                                            <i class="fas fa-edit"></i> Редактировать
                                        </a>
                                        <button class="btn-action btn-delete" onclick="deleteProduct(1)">
                                            <i class="fas fa-trash"></i> Удалить
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach

                    </tbody>
                </table>

            </div>
        @endif
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
                <a href="{{ route('admin.product.create', $category->id)}}" class="btn btn-primary">
                    <i class="fas fa-check"></i> Добавить товар
                </a>
            </div>
        </div>
    </form>
@endsection
