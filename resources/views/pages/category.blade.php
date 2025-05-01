@include('includes.header')

<div class="container mt-4">
    <div class="row">
        <div class="col-md-12">
            <h1 class="text-center mb-4">Категории</h1>
        </div>

        @foreach ($categories as $category)
            <div class="col-md-4 mb-4">
                <div class="card h-100 shadow-sm">
                    <!-- Изображение категории с улучшенной адаптивностью -->
                    @if($category->random_thing_image)
                        <img class="card-img-top" src="{{ $category->random_thing_image }}" alt="{{ $category->name }}" style="height: 300px; object-fit: cover;">
                    @else
                        <div class="card-img-top bg-secondary" style="height: 300px; display: flex; align-items: center; justify-content: center;">
                            <span class="text-white">Нет товаров</span>
                        </div>
                    @endif

                    <div class="card-body">
                        <h2 class="card-title text-center">{{ $category->name }}</h2>

                        <!-- Кнопка для перехода на страницу категории -->
                        <a href="{{ route('category.show', $category) }}" class="btn btn-primary w-100">Подробнее</a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>

@include('includes.footer')
