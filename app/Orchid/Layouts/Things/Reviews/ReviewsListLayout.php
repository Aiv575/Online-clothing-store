<?php

namespace App\Orchid\Layouts\Things\Reviews;

use App\Models\Review;
use Illuminate\Support\Facades\Storage;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;
use Illuminate\Support\Str;

class ReviewsListLayout extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'reviews';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): iterable
    {
        return [
            TD::make('id', 'ID Отзыва')
                ->render(fn(Review $review) => $review->id),
            TD::make('id', 'ID Пользователя')
                ->render(fn(Review $review) => $review->user->id),
            TD::make('user.name', 'Покупатель')
                ->render(fn(Review $review) => $review->user->name),
            TD::make('comment', 'Комментарий')
                ->width('300px')
                ->render(function (Review $review) {
                    return Str::limit($review->comment, 150);
                }),

            TD::make('image', 'Фото')
                ->render(function (Review $review) {
                    $img = $review->image
                            ? '<img src="' . asset('storage/' . $review->image) . '" style="height:150px; border-radius:4px;">'
                            : '';
                            return "{$img}";
                })
                ->width('150px')
                ->cantHide(),
            TD::make('id', 'ID Товара')
                ->render(fn(Review $review) => $review->thing->id),
            TD::make('rating', 'Оценка')
                ->render(fn(Review $review) => $review->rating),
            TD::make('created_at', __('Дата и время создания'))
                ->render(fn(Review $review) => $review->created_at),
        ];
    }
}
