@include('includes.header')

<div class="container">

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <h2 class="my-4">Возврат по заказу #{{ $receipt->id }}</h2>

    <form method="POST" action="{{ route('receipt.return.process', $receipt->id) }}" enctype="multipart/form-data">
        @csrf

        <input type="hidden" name="receipt_id" value="{{ $receipt->id }}">

        <div id="items-repeater">
            @foreach ($items as $index => $item)
                <div class="card mb-4 p-3 border rounded">
                    <h5>{{ $item->thing->name }} — {{ $item->quantity }} шт. по {{ $item->price }} ₽</h5>

                    <input type="hidden" name="items[{{ $index }}][thing_id]" value="{{ $item->thing->id }}">
                    <input type="hidden" name="items[{{ $index }}][size]" value="{{ $item->size }}">

                    <div class="form-group">
                        <label>Размер</label>
                        <input type="text" class="form-control" value="{{ $item->size }}" readonly>
                    </div>

                    <div class="form-group">
                        <label for="items[{{ $index }}][quantity]">Количество для возврата</label>
                        <input type="number" name="items[{{ $index }}][quantity]" class="form-control" min="1" max="{{ $item->quantity }}" required>
                    </div>

                    <div class="form-group">
                        <label for="items[{{ $index }}][reason]">Причина возврата</label>
                        <textarea name="items[{{ $index }}][reason]" class="form-control" required></textarea>
                    </div>

                    <div class="form-group">
                        <label for="items[{{ $index }}][image]">Фотография товара (необязательно)</label>
                        <input type="file" class="form-control-file" name="items[{{ $index }}][image]">
                    </div>
                </div>
            @endforeach
        </div>

        <button type="submit" class="btn btn-primary">Отправить запрос</button>
    </form>
</div>

@include('includes.footer')
