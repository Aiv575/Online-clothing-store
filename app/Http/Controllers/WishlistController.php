<?php

namespace App\Http\Controllers;

use App\Models\Thing;

class WishlistController extends Controller
{
    public function store(Thing $thing)
    {
        // Проверяем, что товар не добавлен в список желаний
        auth()->user()->addToWishlists($thing);

        return redirect()->back()->with('message', 'Товар добавлен в список желаний!');
    }

    public function destroy(Thing $thing)
    {
        // Удаляем товар из списка желаний
        auth()->user()->removeFromWishlists($thing);

        return redirect()->back()->with('message', 'Товар удалён из списка желаний!');
    }
}
