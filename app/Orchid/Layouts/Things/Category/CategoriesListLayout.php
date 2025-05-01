<?php

namespace App\Orchid\Layouts\Things\Category;

use App\Models\Category;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\TD;

class CategoriesListLayout extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'categories';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): iterable
    {
        return [
            TD::make('id', __('ID Категории'))
                ->render(function (Category $category) {
                    return Link::make($category->id)
                        ->route('platform.category.edit', $category);
                }),
            TD::make('name', __('Название'))
                ->render(function (Category $category) {
                    return Link::make($category->name)
                        ->route('platform.category.edit', $category);
                }),
            TD::make('count_things', __('Количество видов'))
                ->render(function (Category $category) {
                    return $category->things->count();
                }),
        ];
    }
}
