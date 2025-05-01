<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Чек на покупку</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; }
        .container { width: 100%; padding: 20px; }
        .header { text-align: center; font-size: 20px; font-weight: bold; }
        .order-info, .things, .total { margin-top: 20px; width: 100%; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #000; padding: 8px; text-align: center; }
        .total { font-size: 18px; font-weight: bold; text-align: right; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">Чек на покупку</div>

        <div class="order-info">
            <p><strong>Номер заказа:</strong> {{ $receipt->id }}</p>
            <p>Дата и время заказа: {{ $receipt->created_at->format('d.m.Y H:i') }}</p>
            <p>Дата и время доставки: {{ $receipt->updated_at->format('d.m.Y H:i') }}</p>
            <p><strong>Покупатель:</strong> {{ $receipt->user ? $receipt->user->name : 'Неизвестный пользователь' }}</p>
        </div>

        <div class="things">
            <table>
                <thead>
                    <tr>
                        <th>Товар</th>
                        <th>Кол-во</th>
                        <th>Цена</th>
                        <th>Сумма</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($receipt->items as $item)
                        <tr>
                            <td>{{ $item->thing->name }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td>{{ number_format($item->price, 2, ',', ' ') }} ₽</td>
                            <td>{{ number_format($item->quantity * $item->price, 2, ',', ' ') }} ₽</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="total">
            @if ($receipt->promoCode)
            <p>Промокод ({{ $receipt->promoCode->code }}) -{{ number_format($receipt->original_total - $receipt->total, 2, ',', ' ') }} ₽</p>
        @endif
        </div>

        <div class="total">
            <p>ИТОГО: {{ number_format($receipt->total, 2, ',', ' ') }} ₽</p>
        </div>
    </div>
</body>
</html>
