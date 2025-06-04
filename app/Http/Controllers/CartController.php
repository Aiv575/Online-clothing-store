<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Basket;
use App\Models\Thing;
use App\Models\Receipt;
use App\Models\ReceiptItem;
use App\Models\SizeAndCount;
use App\Models\PromoCode;

class CartController extends Controller
{
    public function updateSize(Request $request)
    {
        $size = $request->input('size');

        // Сохраняем выбранный размер в сессии
        $request->session()->put('selected_size', $size);

        return redirect()->back();
    }

    public function add(Request $request, Thing $thing)
    {
        $size = $request->input('size');

        if (!$size) {
            return redirect()->back()->with('error', 'Пожалуйста, выберите размер товара');
        }

        $stock = SizeAndCount::where('thing_id', $thing->id)
                         ->where('size', $size)
                         ->first();

        $basket = Basket::where('user_id', $request->user()->id)
                        ->where('thing_id', $thing->id)
                        ->where('size', $size)
                        ->first();

        $currentCountInBasket = $basket ? $basket->count : 0;

        if ($stock && $currentCountInBasket + 1 > $stock->count) {
            return redirect()->back()->with('error', 'Недостаточно товара на складе для выбранного размера');
        }

        if ($basket) {
            $basket->count += 1;
            $basket->total = $basket->count * $thing->price;
            $basket->save();
        } else {
            Basket::create([
                'user_id' => $request->user()->id,
                'thing_id' => $thing->id,
                'total' => $thing->price,
                'count' => 1,
                'size' => $size
            ]);
        }
        return redirect()->back()->with('success', 'Товар добавлен в корзину');
    }

    public function decrease(Request $request, Thing $thing)
    {
        $size = $request->input('size');

        $basket = Basket::where('user_id', $request->user()->id)
                        ->where('thing_id', $thing->id)
                        ->where('size', $size)
                        ->first();
        if ($basket) {
            if ($basket->count > 1) {
                $basket->count -= 1;
                $basket->total = $basket->count * $thing->price;
                $basket->save();
            } else {
                $basket->delete();
            }
        }
        return redirect()->back();
    }

    public function increase(Request $request, Thing $thing)
    {
        $size = $request->input('size');

        $stock = SizeAndCount::where('thing_id', $thing->id)
                         ->where('size', $size)
                         ->first();

        $basket = Basket::where('user_id', $request->user()->id)
                        ->where('thing_id', $thing->id)
                        ->where('size', $size)
                        ->first();

        $currentCountInBasket = $basket ? $basket->count : 0;

        if ($stock && $currentCountInBasket + 1 > $stock->count) {
            return redirect()->back()->with('error', 'Недостаточно товара на складе для выбранного размера');
        }

        if ($basket) {
            $basket->count += 1;
            $basket->total = $basket->count * $thing->price;
            $basket->save();
        }

        return redirect()->back();
    }

    public function checkout(Request $request)
    {
        $validatedData = $request->validate([
            'address' => 'required|string|max:255'
        ]);

        // Получаем текущего пользователя
        $user = $request->user();

        // Обновляем адрес пользователя
        $user->update([
            'address' => $validatedData['address']
        ]);

        $basket = Basket::where('user_id', $request->user()->id)->get();
        if ($basket->isEmpty()) {
            return redirect()->back()->with('error', 'Ваша корзина пуста');
        }

        $originalTotal = 0;
        foreach ($basket as $item) {
            $originalTotal += $item->count * $item->thing->price;
        }

        // Ищем промокод
        $promoCodeValue = session('promo_code');
        $promoCode = PromoCode::where('code', $promoCodeValue)->first();

        $discountedTotal = $originalTotal;

        if ($promoCode && $promoCode->isValid()) {
            $discountedTotal = $promoCode->applyDiscount($originalTotal);
        }

        // Сохраняем чек с оригинальной и финальной суммой
        $receipt = Receipt::create([
            'user_id' => $request->user()->id,
            'address' => $request->address,
            'created_at' => now(),
            'total' => $discountedTotal,
            'original_total' => $originalTotal,
            'promo_code_id' => $promoCode ? $promoCode->id : null,
        ]);

        // Сохраняем позиции
        foreach ($basket as $item) {
            ReceiptItem::create([
                'receipt_id' => $receipt->id,
                'thing_id' => $item->thing_id,
                'quantity' => $item->count,
                'size' => $item->size,
                'price' => $item->thing->price,
            ]);

            // Уменьшаем количество
            $stock = SizeAndCount::where('thing_id', $item->thing_id)
                                ->where('size', $item->size)
                                ->first();
            if ($stock) {
                $newCount = $stock->count - $item->count;
                if ($newCount < 0) {
                    return redirect()->back()->with('error', "Недостаточно товара: {$item->thing->title} ({$item->size})");
                }
                $stock->count = $newCount;
                $stock->save();
            }
        }

        // Чистим корзину
        Basket::where('user_id', $request->user()->id)->delete();

        // Убираем промокод из сессии
        session()->forget('promo_code');

        session()->forget('discounted_price');

        return redirect()->route('index')->with('success', 'Заказ оформлен');
    }


    public function clear(Request $request)
    {
        // Удаляем все товары из корзины текущего пользователя
        Basket::where('user_id', $request->user()->id)->delete();

        // Перенаправляем обратно с сообщением об успехе
        return redirect()->back()->with('success', 'Корзина очищена');
    }

    public function remove($thing)
    {
        // Получаем товар из корзины по ID пользователя
        $cartItem = Basket::where('thing_id', $thing)->where('user_id', auth()->id())->first();

        if ($cartItem) {
            // Удаляем товар из корзины
            $cartItem->delete();
        }

        // Возвращаем пользователя обратно в корзину
        return redirect()->route('basket')->with('success', 'Товар удален из корзины');
    }

    public function applyPromoCode(Request $request)
    {
        $promoCode = PromoCode::where('code', $request->promo_code)->first();

        if (!$promoCode || !$promoCode->isValid()) {
            return redirect()->back()->with('error', 'Недействительный или просроченный промокод');
        }

        $totalPrice = $this->calculateTotalPrice();
        $discountedPrice = $promoCode->applyDiscount($totalPrice);

        // Сохраняем скидку и промокод в сессии
        session([
            'discounted_price' => $discountedPrice,
            'promo_code' => $promoCode->code,
        ]);

        return redirect()->back()->with('success', 'Промокод применён');
    }

    public function calculateTotalPrice()
    {
        $basket = Basket::where('user_id', auth()->id())->get();

        $total = 0;
        foreach ($basket as $item) {
            $total += $item->count * $item->thing->price;
        }

        return $total;
    }
}
