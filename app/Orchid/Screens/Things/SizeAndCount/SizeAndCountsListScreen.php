<?php

namespace App\Orchid\Screens\Things\SizeAndCount;

use App\Models\Thing;
use App\Orchid\Layouts\Things\SizeAndCounts\SizeAndCountsListLayout;
use Orchid\Screen\Screen;

class SizeAndCountsListScreen extends Screen
{
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'things' => Thing::with('sizeAndCount')->get(),
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Размеры и количество';
    }

    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [];
    }

    /**
     * The screen's layout elements.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): array
    {
        return [
            SizeAndCountsListLayout::class
        ];
    }
}
