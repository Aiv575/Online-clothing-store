@include('includes.header')

<div class="container mt-4">
    <div class="row">
        <div class="col-md-12">
            <h1 class="text-center mb-4">Ваш список желаний</h1>

            @if($wishlists->isEmpty())
                <div class="alert alert-info text-center">У вас пока нет товаров в избранном.</div>
            @endif

            <div class="row">
                @foreach($wishlists as $item)
                    @php $thing = $item->thing; @endphp

                    <div class="col-md-4 mb-4">
                        <div class="card h-100 shadow-sm">
                            <!-- Изображение товара с улучшенной адаптивностью -->
                            <img class="card-img-top" src="{{ $thing->imageAttachment->url() }}" alt="{{ $thing->name }}" style="height: 300px; object-fit: cover;">

                            <div class="card-body">
                                <h2 class="card-title text-center">{{ $thing->name }}</h2>

                                <!-- Контейнер для отображения цены и кнопки для добавления в избранное -->
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <p class="card-text mb-0">Цена: {{ $thing->price }} ₽</p>

                                    @auth
                                        <!-- Условие отображения пустого или заполненного сердца -->
                                        @if(Auth::user()->isWishlist($thing))
                                            <!-- Заполненное сердце - товар уже в избранном -->
                                            <form action="{{ route('wishlists.remove', $thing->id) }}" method="POST" class="favorite-form ms-3">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn-favorite border-0 bg-transparent" title="Удалить из избранного">
                                                    <i class="fas fa-heart" style="color: red; font-size: 1.6rem;"></i>
                                                </button>
                                            </form>
                                        @else
                                            <!-- Пустое сердце - товар не в избранном -->
                                            <form action="{{ route('wishlists.add', $thing->id) }}" method="POST" class="favorite-form ms-3">
                                                @csrf
                                                <button type="submit" class="btn-favorite border-0 bg-transparent" title="Добавить в избранное">
                                                    <i class="far fa-heart" style="color: grey; font-size: 1.6rem;"></i>
                                                </button>
                                            </form>
                                        @endif
                                    @endauth
                                </div>

                                <!-- Кнопка для перехода на страницу товара -->
                                <a href="{{ route('thing.show', $thing->id) }}" class="btn btn-primary w-100">Подробнее</a>
                            </div>
                        </div>
                    </div>

                @endforeach
            </div>
        </div>
    </div>
</div>

@include('includes.footer')
