<?php

namespace App\Orchid\Screens\Things\Thing;

use App\Models\Thing;
use App\Orchid\Layouts\Things\Thing\ThingsListLayout;
use Orchid\Screen\Screen;
use Orchid\Screen\Actions\Link;

class ThingsListScreen extends Screen
{
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'things' => Thing::paginate()
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Товары';
    }

    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Link::make('Создать новый')
                ->icon('pencil')
                ->route('platform.thing.edit')
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
            ThingsListLayout::class
        ];
    }
}
