<?php

namespace App\Orchid\Layouts\Things\SizeAndCounts;

use App\Models\SizeAndCount;
use App\Models\Thing;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;
use Illuminate\Support\HtmlString;

class SizeAndCountsListLayout extends Table
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
                    return Link::make((string) $thing->id)
                        ->route('platform.thing.edit', $thing->id);
                }),

            TD::make('name', __('Название'))
                ->render(function (Thing $thing) {
                    return Link::make($thing->name)
                        ->route('platform.thing.edit', $thing->id);
                }),

            TD::make('sizes', 'Размеры')
                ->render(function (Thing $thing) {
                    $html = '<ul>';
                    foreach ($thing->sizeAndCount as $entry) {
                        $html .= "<li>{$entry->size}</li>";
                    }
                    $html .= '</ul>';
                    return new HtmlString($html);
                }),

            TD::make('counts', 'Количество')
                ->render(function (Thing $thing) {
                    $html = '<ul>';
                    foreach ($thing->sizeAndCount as $entry) {
                        $html .= "<li>{$entry->count}</li>";
                    }
                    $html .= '</ul>';
                    return new HtmlString($html);
                }),
        ];
    }
}
