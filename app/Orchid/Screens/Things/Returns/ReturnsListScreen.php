<?php

namespace App\Orchid\Screens\Things\Returns;


use App\Models\ReturnRequest;
use App\Orchid\Layouts\Things\Returns\ReturnsListLayout;
use Orchid\Screen\Screen;

class ReturnsListScreen extends Screen
{
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'returns' => ReturnRequest::paginate()
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Возвраты';
    }


    /**
     * The screen's layout elements.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): iterable
    {
        return [
            ReturnsListLayout::class
        ];
    }
}
