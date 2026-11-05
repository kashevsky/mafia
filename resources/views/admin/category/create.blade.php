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

    <form action="{{ route('admin.category.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <input type="hidden" name="category_id">
        <!-- Основная информация -->
        <div class="content-card">
            <h2 class="section-title"><i class="fas fa-info-circle"></i> Основная информация</h2>


            <div class="row">
                <div class="col-md-8 mb-3">
                    <label for="title" class="form-label">Название категории *</label>
                    <input type="text" class="form-control" id="title" name="title"
                        required>
                </div>
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
