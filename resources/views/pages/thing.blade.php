@include('includes.header')

<div class="container mt-4">
    <div class="row">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center my-4 position-relative">
                <h1 class="m-0 text-center w-100">{{ $thing->name }}</h1>

                @auth
                    @if(Auth::user()->isWishlist($thing))
                        <form action="{{ route('wishlists.remove', $thing->id) }}" method="POST" class="position-absolute end-0">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-favorite border-0 bg-transparent" title="Удалить из избранного">
                                <i class="fas fa-heart" style="color: red; font-size: 1.8rem;"></i>
                            </button>
                        </form>
                    @else
                        <form action="{{ route('wishlists.add', $thing->id) }}" method="POST" class="position-absolute end-0">
                            @csrf
                            <button type="submit" class="btn-favorite border-0 bg-transparent" title="Добавить в избранное">
                                <i class="far fa-heart" style="color: grey; font-size: 1.8rem;"></i>
                            </button>
                        </form>
                    @endif
                @endauth
            </div>

            <div class="card h-100">
                <div class="card-body text-center">
                    <img class="card-img-top mb-3" src="{{ $thing->image }}" alt="{{ $thing->name }}" style="max-width: 300px; height: auto; object-fit: cover; margin: 0 auto;">
                    <p>Рейтинг:
                        @php
                            $averageRating = $thing->averageRating(); // Получаем средний рейтинг
                        @endphp
                        {{ $averageRating ? number_format($averageRating, 1) : 'Нет оценок' }} ★
                    </p>
                    <p class="card-text">Категория: {{ $thing->category->name }}</p>
                    <p class="card-text">Описание: {{ $thing->description }}</p>
                    <p class="card-text">Цена: {{ $thing->price }} ₽</p>

                    @auth
                        @php
                            $selectedSize = session('selected_size');
                            $inBasket = \App\Models\Basket::where('user_id', auth()->id())
                                ->where('thing_id', $thing->id)
                                ->where('size', $selectedSize)
                                ->first();
                        @endphp

                        {{-- Выбор размера --}}
                        <form action="{{ route('cart.updateSize', $thing) }}" method="POST">
                            @csrf
                            <div class="mb-4 text-center">
                                <label for="size" class="mb-2"><strong>Выберите размер:</strong></label>
                                <div class="btn-group d-flex flex-wrap justify-content-center" role="group">
                                    @foreach($thing->sizeAndCount as $sizeAndCount)
                                        <button type="submit" name="size" value="{{ $sizeAndCount->size }}" class="btn btn-outline-primary m-1 {{ $selectedSize == $sizeAndCount->size ? 'active' : '' }}">
                                            {{ $sizeAndCount->size }} ({{ $sizeAndCount->count }} в наличии)
                                        </button>
                                    @endforeach
                                </div>
                            </div>
                        </form>

                        {{-- Управление количеством или добавление в корзину --}}
                        @if ($inBasket)
                            <div class="d-flex justify-content-center align-items-center gap-2 mt-3">
                                <form action="{{ route('cart.decrease', $thing->id) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="size" value="{{ $selectedSize }}">
                                    <button type="submit" class="btn btn-outline-danger">−</button>
                                </form>

                                <span class="px-2">{{ $inBasket->count }}</span>

                                <form action="{{ route('cart.increase', $thing->id) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="size" value="{{ $selectedSize }}">
                                    <button type="submit" class="btn btn-outline-success">+</button>
                                </form>
                            </div>

                            <div class="text-center mt-3">
                                <a href="{{ route('basket') }}" class="btn btn-primary">Оформить</a>
                            </div>
                        @else
                            <form action="{{ route('cart.add', $thing->id) }}" method="POST" class="mt-3">
                                @csrf
                                <input type="hidden" name="size" value="{{ $selectedSize }}">
                                <button type="submit" class="btn btn-primary w-100">Добавить в корзину</button>
                            </form>
                        @endif
                    @endauth

                </div>
            </div>
        </div>
                <div class="col-md-12">
                    @auth
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Оставьте отзыв</h4>
                                <form action="{{ route('thing.review', $thing->id) }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <input type="hidden" name="thing_id" value="{{ $thing->id }}">

                                    <div class="form-group">
                                        <label>Оценка:</label>
                                        <select name="rating" class="form-control" required>
                                            <option value="5">★★★★★</option>
                                            <option value="4">★★★★☆</option>
                                            <option value="3">★★★☆☆</option>
                                            <option value="2">★★☆☆☆</option>
                                            <option value="1">★☆☆☆☆</option>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label>Комментарий:</label>
                                        <textarea name="comment" class="form-control" required></textarea>
                                    </div>

                                    <div class="form-group">
                                        <label for="image">Фотография (необязательно)</label>
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" id="image" name="image">
                                            <label class="custom-file-label" for="image">Выберите файл</label>
                                        </div>
                                    </div>

                                    <button type="submit" class="btn btn-primary">Оставить отзыв</button>
                                </form>
                            </div>
                        </div>
                    @endauth
                </div>
            </div>
            <br>

            <!-- Отзывы -->
            @foreach($thing->reviews as $review)
                <div class="card mb-3">
                    <div class="card-body">
                        <h5>{{ $review->user->name }} — ★ {{ $review->rating }}</h5>
                        <p>{{ $review->comment }}</p>

                        @if($review->image)
                            <img src="{{ asset('storage/' . $review->image) }}" style="max-width: 200px;">
                        @endif

                        {{-- Проверяем, поставил ли текущий пользователь лайк на этот отзыв --}}
                        @php
                            $userLiked = $review->likes->where('user_id', auth()->id())->first();
                        @endphp
                        <br>
                        <br>
                        {{-- Форма для лайка отзыва --}}
                        <form action="{{ route('review.like', $review->id) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="border-0 bg-transparent p-0" title="Поставить лайк">
                                <i class="{{ $userLiked ? 'fas' : 'far' }} fa-thumbs-up"
                                   style="font-size: 1.4rem; color: {{ $userLiked ? '#0d6efd' : '#6c757d' }};"></i>
                                <span class="ms-1 text-muted">{{ $review->likes->count() }}</span>
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

@include('includes.footer')
