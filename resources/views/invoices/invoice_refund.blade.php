<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Чек на возврат</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 14px; }
        .container { padding: 20px; }
        .header { font-size: 20px; text-align: center; font-weight: bold; margin-bottom: 20px; }
        .block { margin-bottom: 15px; }
        .things, .total { margin-top: 20px; width: 100%; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #000; padding: 8px; text-align: center; }
        .total { font-size: 18px; font-weight: bold; text-align: right; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">Чек на возврат</div>

        <div class="block">
            <p><strong>Номер заказа:</strong> {{ $return->receipt->id }}</p>
            <p><strong>Номер возврата:</strong> {{ $return->id }}</p>
            <p><strong>Дата возврата:</strong> {{ optional($return->updated_at)->format('d.m.Y H:i') }}</p>
            <p><strong>Покупатель:</strong> {{ $return->user->name ?? 'Неизвестен' }}</p>
        </div>
        <div class="thing">
            <table>
                <thead>
                    <tr>
                        <th>Товар</th>
                        <th>Кол-во</th>
                        <th>Размер</th>
                        <th>Цена</th>
                        <th>Сумма</th>
                        <th>Причина</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($return->items as $item)
                        <tr>
                            <td>{{ $item->thing->name }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td>{{ $item->size }}</td>
                            <td>{{ number_format($item->price, 2, ',', ' ') }} ₽</td>
                            <td>{{ number_format($item->quantity * $item->price, 2, ',', ' ') }} ₽</td>
                            <td>{{ $item->reason }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="total">
            <p>ИТОГО: {{ number_format($return->total_refund, 2, ',', ' ') }} ₽</p>
        </div>
    </div>
</body>
</html>
