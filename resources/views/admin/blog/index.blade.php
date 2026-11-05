@extends('admin.layouts.main')
@section('content')
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <h1>Управление товарами (список)</h1>
            <a href="{{ route('admin.blog.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Добавить запись
            </a>
        </div>
        <nav aria-label="breadcrumb" class="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="/admin/dashboard">Главная</a></li>
                <li class="breadcrumb-item active" aria-current="page">Блог</li>
            </ol>
        </nav>
    </div>

    <!-- Фильтры и поиск -->
    <div class="filters">




        <div class="filter-group search-box">
            <label class="form-label">Поиск</label>
            <input type="text" id="searchInput" placeholder="Введите название товара...">
            <i class="fas fa-search"></i>
        </div>
    </div>

    <!-- Таблица товаров -->
    <div class="products-table">
        <table class="w-100">
            <thead>
                <tr class="table-header">
                    <th class="table-cell">Название</th>
                    <th class="table-cell" style="width: 180px;">Действия</th>
                </tr>
            </thead>
            <tbody id="productsTableBody">
                @foreach ($blogs as $blog)
                    <tr>
                        <td class="table-cell product-name">{{ $blog->title }}</td>
                        <td class="table-cell">
                            <div class="action-buttons">
                                <a href="{{ route('admin.blog.edit', $blog->id) }}" class="btn-action btn-edit">
                                    <i class="fas fa-edit"></i> Редактировать
                                </a>
                                <a href="{{ route('admin.blog.delete', $blog->id)}}" class="btn-action btn-delete" onclick="deleteProduct(1)">
                                    <i class="fas fa-trash"></i> Удалить
                                </a>
                            </div>
                        </td>
                    </tr>
                @endforeach

            </tbody>
        </table>
    </div>
@endsection
