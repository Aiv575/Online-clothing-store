@include('includes.header')

<div class="container mt-4">
    <div class="row">
        <div class="col-md-12">
            <h1 class="text-center mb-4">Корзина</h1>

            @if($things->isEmpty())
                <div class="alert alert-info text-center">Ваша корзина пуста.</div>
            @else
                <table class="table text-center align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Название товара</th>
                            <th>Цена</th>
                            <th>Количество</th>
                            <th>Размер</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                    @php
                        $total = 0;
                    @endphp
                    @foreach($things as $thing)
                        @php
                            $total += $thing->thing->price * $thing->count;
                        @endphp
                        <tr>
                            <td>{{ $thing->thing->name }}</td>
                            <td>{{ $thing->thing->price * $thing->count }} ₽</td>
                            <td>
                                <div class="d-flex justify-content-center align-items-center gap-2">
                                    <form action="{{ route('cart.decrease', $thing->thing->id) }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="size" value="{{ $thing->size }}">
                                        <button type="submit" class="btn btn-outline-danger">−</button>
                                    </form>

                                    <span class="px-2">{{ $thing->count }}</span>

                                    <form action="{{ route('cart.increase', $thing->thing->id) }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="size" value="{{ $thing->size }}">
                                        <button type="submit" class="btn btn-outline-success">+</button>
                                    </form>
                                </div>
                            </td>
                            <td>{{ $thing->size }}</td>
                            <td>
                                <form action="{{ route('cart.remove', $thing->thing->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <input type="hidden" name="size" value="{{ $thing->size }}">
                                    <button type="submit" class="btn btn-sm btn-outline-danger">Удалить</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>

                <div class="mb-3 text-center">
                    <form action="{{ route('cart.clear') }}" method="POST" class="d-inline-block">
                        @csrf
                        <button type="submit" class="btn btn-danger">Очистить корзину</button>
                    </form>
                </div>
                
                <div class="text-end">
                    @php
                        $finalTotal = session('discounted_price') ?? $total;
                    @endphp

                    <p class="fw-bold fs-5">
                        Итоговая сумма:
                        <span class="{{ session('discounted_price') ? 'text-success' : '' }}">
                            {{ $finalTotal }} ₽
                        </span>
                    </p>

                    @if(session('discounted_price'))
                        <p class="text-muted">Вы сэкономили: {{ $total - session('discounted_price') }} ₽</p>
                    @endif

                    <!-- Форма для ввода промокода -->
                    <form action="{{ route('cart.applyPromoCode') }}" method="POST" class="mb-3">
                        @csrf
                        <div class="input-group">
                            <input type="text" name="promo_code" class="form-control" placeholder="Введите промокод" required>
                            <button type="submit" class="btn btn-secondary">Применить</button>
                        </div>
                    </form>
                </div>

                @if(session('promo_code'))
                    <input type="hidden" name="promo_code" value="{{ session('promo_code') }}">
                @endif

                <form action="{{ route('cart.checkout') }}" method="POST">
                    @csrf
                    <div class="form-group mb-3">
                        <label for="address">Адрес доставки</label>
                        <input type="text" class="form-control" id="address" name="address" value="{{ Auth::user()->address }}" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Заказать</button>
                </form>
            @endif
        </div>
    </div>
</div>

@include('includes.footer')
