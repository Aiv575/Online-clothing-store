<?php

namespace App\Orchid\Screens\Things\Category;

use App\Models\Category;
use App\Orchid\Layouts\Things\Category\CategoriesListLayout;
use Orchid\Screen\Screen;
use Orchid\Screen\Actions\Link;

class CategoriesListScreen extends Screen
{
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'categories' => Category::paginate()
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Категории товаров';
    }

    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Link::make('Создать новую')
                ->icon('pencil')
                ->route('platform.category.edit')
        ];
    }

    /**
     * The screen's layout elements.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): iterable
    {
        return [
            CategoriesListLayout::class
        ];
    }
}
