<?php

namespace App\Http\Controllers;

use App\Models\Basket;
use App\Models\Thing;
use App\Models\Category;
use App\Models\Receipt;
use App\Models\ReceiptItem;
use App\Models\ReturnModel;
use App\Models\Wishlist;
use Orchid\Attachment\Models\Attachment;


class ViewsController extends Controller
{
    public function index()
    {
        $things = Thing::all()->map(function ($thing) {
            $image = Attachment::find($thing->image);
            $thing->image = $image ? $image->url() : null;
            return $thing;
        });
        return view("index", [
            'things' => $things,
        ]);
    }

    public function register()
    {
        return view("pages.register");
    }

    public function login()
    {
        return view("pages.login");
    }

    public function basket()
    {
        $things = Basket::with('thing.sizeAndCount') // Подгружаем товары и размеры
        ->where('user_id', auth()->id())  // Фильтруем по текущему пользователю
        ->get();

        return view("pages.basket", [
            'things' => $things,
        ]);
    }

    public function profile()
    {
        $user = auth()->user()->load([
            'receipts.items.thing',
            'receipts.returnRequests.items.thing',
        ]);

        return view("pages.profile", [
            'user' => $user,
            'receipts' => $user->receipts,
        ]);
    }

    public function thing(thing $thing)
    {
        $image = Attachment::find($thing->image);
        $thing->image = $image ? $image->url() : null;
        return view("pages.thing", [
            'thing' => $thing,
        ]);
    }

    public function categories(Category $categories)
    {
        $categories = Category::all()->map(function ($category) {
            return $category;
        });

        return view("pages.category", [
            'categories' => $categories,
        ]);
    }

    public function category(Category $category)
    {
        return view("pages.things_of_the_category", [
            'category' => $category,
        ]);
    }

    public function refund(Receipt $receipt)
    {
        $items = $receipt->items;

        return view('pages.refund', compact('receipt', 'items'));
    }

    public function wishlist()
    {
        $wishlists = Wishlist::with('thing')->where('user_id', auth()->id())->get();

        return view('pages.wishlist', compact('wishlists'));
    }
}
