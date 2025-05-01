<?php

namespace App\Orchid\Layouts\Things\Thing;

use App\Models\Thing;
use Illuminate\Support\Facades\Storage;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\Input;

class ThingsListLayout extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'things';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): iterable
    {
        return [
            TD::make('id', __('ID Товара'))
                ->render(function (Thing $thing) {
                    return Link::make($thing->id)
                        ->route('platform.thing.edit', $thing);
                }),
            TD::make('name', __('Название'))
                ->render(function (Thing $thing) {
                    return Link::make($thing->name)
                        ->route('platform.thing.edit', $thing);
                }),
            TD::make('description', __('Описание'))
                ->render(function (Thing $thing) {
                    return $thing->description;
                }),
            TD::make('price', __('Цена'))
                ->render(function (Thing $thing) {
                    return $thing->price;
                }),
            TD::make('rating', __('Рейтинг'))
                ->render(function (Thing $thing) {
                    return number_format($thing->averageRating(), 1, '.'); // Вызываем как метод
                }),
            TD::make('category_id', __('Категория'))
                ->render(function (Thing $thing) {
                    return $thing->category->name;
                }),
        ];
    }
}
